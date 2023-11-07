<?php

namespace App\Http\Controllers;

use App\Attendize\Utils;
use App\Models\Affiliate;
use App\Models\Event;
use App\Models\EventAccessCodes;
use App\Models\EventStats;
use Auth;
use Cookie;
use Illuminate\Http\Request;
use Mail;
use Validator;

class EventViewController extends Controller
{
    /**
     * Show the homepage for an event
     *
     * @param Request $request
     * @param $event_id
     * @param string $slug
     * @param bool $preview
     * @return mixed
     */
    public function showEventHome(Request $request, $event_id, $slug = '', $preview = false)
    {
        $event = Event::findOrFail($event_id); 
        $abecedario = range('A', 'Z'); 

        if (!Utils::userOwns($event) && !$event->is_live) {
            return view('Public.ViewEvent.EventNotLivePage');
        }

        $tickets_all = [];
        $tickets = $event->tickets()->where('is_hidden',false)->orderBy('group_zone', 'desc')->get();
         
        if($event_id==52){
            $grupo = [
                [
                    'grupo' => 'BARRERA',
                    'secciones' => [ 
                        'BARRERA SOMBRA Fila 1',
                        'BARRERA SOMBRA Fila 2',
                        'BARRERA SOMBRA Fila 3',
                        'BARRERA SOMBRA Fila 4',
                        'BARRERA SOL Fila 1',
                        'BARRERA SOL Fila 2',
                        'BARRERA SOL Fila 3',
                        'BARRERA SOL Fila 4',
                        'BARRERA SOL Fila 1',
                    ]
                ],
                [
                    'grupo' => '1ER TENDIDO',
                    'secciones' => [ 
                        '1er TENDIDO SOMBRA Fila 1',
                        '1er TENDIDO SOMBRA Fila 2',
                        '1er TENDIDO SOMBRA Fila 3',
                        '1er TENDIDO SOMBRA Fila 4',
                        '1er TENDIDO SOMBRA Fila 5',
                        '1er TENDIDO SOMBRA Fila 6',
                        '1er TENDIDO SOL Fila 1',
                        '1er TENDIDO SOL Fila 2',
                        '1er TENDIDO SOL Fila 3',
                        '1er TENDIDO SOL Fila 4',
                        '1er TENDIDO SOL Fila 5',
                        '1er TENDIDO SOL Fila 6',
                    ]
                ],
                [
                    'grupo' => '2DO TENDIDO',
                    'secciones' => [ 
                        'Segundo Tendido Sombra',
                        'Segundo Tendido Sol',

                        ]
                ],
                [
                    'grupo' => 'GENERAL',
                    'secciones' => [ 
                        'General Sombra',
                        'General Sol',
                        ]
                ],
                [
                    'grupo' => 'ALTO GENERAL',
                    'secciones' => [ 
                        'TENDIDO ALTO', 
                    ]
                ] 
            ];
            
            
            foreach ($grupo as $keygrupo => $valuegrupo) {
                foreach ($valuegrupo['secciones'] as $key => $valuesecciones) {
                    $tickets_all[$valuegrupo['grupo']][$valuesecciones][] =$event->tickets()->where('is_hidden',false)->where('group_zone',$valuesecciones)->orderBy('group_zone', 'desc')->get();
                }
            }
 
        }else{
             
            $grupo = [
                [
                    'grupo' => 'GENERAL', 
                    'secciones' => $event->tickets_group_zone(true)
                ], 
            ];
             
            foreach ($grupo as $keygrupo => $valuegrupo) {
                foreach ($valuegrupo['secciones'] as $key => $valuesecciones) {
                    $tickets_all[$valuegrupo['grupo']][$valuesecciones][] =$event->tickets()->where('is_hidden',false)->where('group_zone',$valuesecciones)->orderBy('group_zone', 'desc')->get();
                }
            }
 
        }
        
        $data = [
            'event' => $event,
            'tickets' => $tickets,
            'tickets_all' => $tickets_all,
            'is_embedded' => 0,
            'abecedario' => $abecedario
        ];
        /*
         * Don't record stats if we're previewing the event page from the backend or if we own the event.
         */
        if (!$preview && !Auth::check()) {
            $event_stats = new EventStats();
            $event_stats->updateViewCount($event_id);
        }

        /*
         * See if there is an affiliate referral in the URL
         */
        if ($affiliate_ref = $request->get('ref')) {
            $affiliate_ref = preg_replace("/\W|_/", '', $affiliate_ref);

            if ($affiliate_ref) {
                $affiliate = Affiliate::firstOrNew([
                    'name'       => $request->get('ref'),
                    'event_id'   => $event_id,
                    'account_id' => $event->account_id,
                ]);

                ++$affiliate->visits;

                $affiliate->save();

                Cookie::queue('affiliate_' . $event_id, $affiliate_ref, 60 * 24 * 60);
            }
        }

        return view('Public.ViewEvent.EventPage', $data);
    }

    /**
     * Show preview of event homepage / used for backend previewing
     *
     * @param $event_id
     * @return mixed
     */
    public function showEventHomePreview($event_id)
    {
        return showEventHome($event_id, true);
    }

    /**
     * Sends a message to the organiser
     *
     * @param Request $request
     * @param $event_id
     * @return mixed
     */
    public function postContactOrganiser(Request $request, $event_id)
    {
        $rules = [
            'name'    => 'required',
            'email'   => ['required', 'email'],
            'message' => ['required'],
        ];

        if (env('APP_ENV')=='production') {
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => 'error',
                    'messages' => $validator->messages()->toArray(),
                ]);
            }
        }

        $event = Event::findOrFail($event_id);

        $data = [
            'sender_name'     => $request->get('name'),
            'sender_email'    => $request->get('email'),
            'message_content' => strip_tags($request->get('message')),
            'event'           => $event,
        ];

        Mail::send('Emails.messageReceived', $data, function ($message) use ($event, $data) {
            $message->to($event->organiser->email, $event->organiser->name)
                ->from(config('attendize.outgoing_email_noreply'), $data['sender_name'])
                ->replyTo($data['sender_email'], $data['sender_name'])
                ->subject(trans("Email.message_regarding_event", ["event"=>$event->title]));
        });

        return response()->json([
            'status'  => 'success',
            'message' => trans("Controllers.message_successfully_sent"),
        ]);
    }

    public function showCalendarIcs(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);

        $icsContent = $event->getIcsForEvent();

        return response()->make($icsContent, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="event.ics'
        ]);
    }

    /**
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postShowHiddenTickets(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);

        $accessCode = strtoupper(strip_tags($request->get('access_code')));
        if (!$accessCode) {
            return response()->json([
                'status' => 'error',
                'message' => trans('AccessCodes.valid_code_required'),
            ]);
        }

        $unlockedHiddenTickets = $event->tickets()
            ->where('is_hidden', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->filter(function($ticket) use ($accessCode) {
                // Only return the hidden tickets that match the access code
                return ($ticket->event_access_codes()->where('code', $accessCode)->get()->count() > 0);
            });

        if ($unlockedHiddenTickets->count() === 0) {
            return response()->json([
                'status' => 'error',
                'message' => trans('AccessCodes.no_tickets_matched'),
            ]);
        }

        // Bump usage count
        EventAccessCodes::logUsage($event_id, $accessCode);

        return view('Public.ViewEvent.Partials.EventHiddenTicketsSelection', [
            'event' => $event,
            'tickets' => $unlockedHiddenTickets,
            'is_embedded' => 0,
        ]);
    }
}
