<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\EventStats;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SeatTicket;
use App\Models\Ticket;
use App\Services\Order as OrderService;
use Cookie;
use DB;
use Illuminate\Http\Request;
use Log;
use Validator;
use Exception;

/*
Attendize.com   - Event Management & Ticketing
 */

class EventTicketsController extends MyBaseController
{

    /**
     * Complete an order
     *
     * @param $ticket_id
     * @param bool|true $return_json
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function postSalesTickets(Request $request, $ticket_id, $return_json = true)
    {
        
        try {
            DB::beginTransaction();
            $price_neto = $services_fee_neto = 0;
            $nombre = $request->get('first_name');
            $apellido = $request->get('last_name');
            $email = $request->get('email');
            $cantidad = $request->get('quantity');
            $email_ticket = $request->get('email_ticket');
            $getasientos = explode(',', $request->get('asientos_marcados')); 
            $payment_method = $request->get('payment_method');
            $ticket = Ticket::find($ticket_id);
            $ticket_quantity_remaining = $ticket->quantity_remaining;
            $max_per_person = min($ticket_quantity_remaining, $ticket->max_per_person);

            $rules['first_name'] = ['required'];
            $rules['last_name'] = ['required'];
            $rules['email'] = ['required', 'email'];
            $rules['quantity'] = ['required', 'numeric', 'min:' . $ticket->min_per_person, 'max:' . $max_per_person];

            $quantity_available_validation_messages = [
                'quantity.max' => 'The maximum number of tickets you can register is ' . $ticket_quantity_remaining,
                'quantity.min' => 'You must select at least ' . $ticket->min_per_person . ' tickets.',
            ];

            if (env('APP_ENV')=='production') {
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'messages' => $validator->messages()->toArray(),
                    ]);
                }
            }

            if ($cantidad == 0) {
                return response()->json([
                    'status' => 'success',
                    'messages' => "Seleccione la Cantidad de Entrada.",
                ]);
            }

            $order_total = $cantidad * $ticket->price_neto;
            $booking_fee = $cantidad * $ticket->booking_fee;
            $organiser_booking_fee = $cantidad * $ticket->organiser_booking_fee;
            $services_fee = $cantidad * $ticket->price_service;
            $services_fee_neto = $ticket->price_service;
            $price_neto = $ticket->price_neto;

            $event_id = $ticket->event_id;

            $order = new Order();

            $ticket_order = session()->get('ticket_order_' . $event_id);
            // $request_data = $ticket_order['request_data'][0];

            $event = Event::findOrFail($event_id);
            $attendee_increment = 1;

            // $ticket_questions = isset($request_data['ticket_holder_questions']) ? $request_data['ticket_holder_questions'] : [];

            /*
             * Create the order
             */
            $order->first_name = strip_tags($nombre);
            $order->last_name = strip_tags($apellido);
            $order->email = $email;
            $order->order_status_id = 1;
            $order->amount = $order_total;
            $order->booking_fee = $booking_fee;
            $order->organiser_booking_fee = $organiser_booking_fee;
            $order->services_fee = $services_fee;
            $order->discount = 0.00;
            $order->account_id = $event->account->id;
            $order->event_id = $event_id;
            $order->is_payment_received = 1;
            $order->seating = $request->get('asientos_marcados');
            $order->payment_method = $payment_method;

            // Calculating grand total including tax
            $orderService = new OrderService($order_total, $booking_fee, $event);
            $orderService->calculateFinalCosts();

            $order->taxamt = $orderService->getTaxAmount();
            $order->save();


            if ($ticket->select_seat == 1) {
                foreach ($request->get('sillas') as  $value) {
                    if(!empty($value))
                    {
                        $seatTicket = SeatTicket::find($value);
                        $seatTicket->is_available = 3;
                        $seatTicket->save();
                    }                     
                }
            }

            if ($ticket->select_seat == 1) {
                foreach ($getasientos as  $value) {
                    if(!empty($value))
                    {
                        $seatTicket = SeatTicket::find($value);
                        $seatTicket->is_available = 3;
                        $seatTicket->save();
                    }                     
                }
            }

            /*
             * Update the event sales volume
             */
            $event->increment('sales_volume', $orderService->getGrandTotal());
            $event->increment('organiser_fees_volume', ($order->organiser_booking_fee+$order->services_fee));

            /*
             * Update affiliates stats stats
             */
            if (Cookie::get('affiliate_' . $event_id)) {
                $affiliate = Affiliate::where('name', '=', Cookie::get('affiliate_' . $event_id))
                    ->where('event_id', '=', $event_id)->first();
                $affiliate->increment('sales_volume', $order->amount + $order->organiser_booking_fee);
                $affiliate->increment('tickets_sold', $cantidad);
            }

            /*
             * Update the event stats
             */
            $event_stats = EventStats::updateOrCreate([
                'event_id' => $event_id,
                'date' => DB::raw('CURRENT_DATE'),
            ]);
            $event_stats->increment('tickets_sold', $cantidad);

            // if (!is_null($ticket_order) && $ticket_order['order_requires_payment']) {
            // if (!is_null($ticket_order)) {
                $event_stats->increment('sales_volume', $order->amount);
                $event_stats->increment('organiser_fees_volume', $order->services_fee);
            // }

            /*
             * Add the attendees
             */
            //foreach ($ticket_order['tickets'] as $attendee_details) {

            /*
             * Update ticket's quantity sold
             */

            /*
             * Update some ticket info
             */
            $ticket->increment('quantity_sold', $cantidad);
            $ticket->increment('sales_volume', $order_total);
            $ticket->increment('organiser_fees_volume',$services_fee);

            /*
             * Insert order items (for use in generating invoices)
             */
            $orderItem = new OrderItem();
            $orderItem->title = $ticket->title;
            $orderItem->quantity = $cantidad;
            $orderItem->order_id = $order->id;
            $orderItem->unit_price = $price_neto;
            $orderItem->unit_booking_fee = $booking_fee + $organiser_booking_fee + $services_fee_neto;
            $orderItem->save();

            /*
             * Create the attendees
             */
            for ($i = 0; $i < $cantidad; $i++) {

                $attendee = new Attendee();
                $attendee->first_name = strip_tags($nombre);
                $attendee->last_name = strip_tags($apellido);
                $attendee->email = $email;
                $attendee->event_id = $event_id;
                $attendee->order_id = $order->id;
                $attendee->ticket_id = $ticket_id;
                $attendee->account_id = $event->account->id;
                $attendee->reference_index = $attendee_increment;
                $attendee->seat = ($ticket->select_seat == 1) ? $getasientos[$i] : null;
                $attendee->save();

                /* Keep track of total number of attendees */
                $attendee_increment++;
            }
            //}

            //save the order to the database
            DB::commit();

            // Queue up some tasks - Emails to be sent, PDFs etc.
            Log::info('Firing the event');
            //event(new OrderCompletedEvent($order));

            if ($return_json) {
                return response()->json([
                    'status' => 'success',
                    'redirectUrl' => route('showEventTickets', [
                        'event_id' => $event_id,
                    ]),
                    'redirectUrlPdf' => route('showOrderTickets', [
                        'order_reference' => $order->order_reference,
                    ])."?download=1",
                ]);
            }
        } catch (Exception $e) {

            Log::error($e);
            DB::rollBack();

            return response()->json([
                'line' => $e->getLine(),
                'error' => $e->getMessage(),
                'status' => 'error',
                'message' => 'Whoops! There was a problem processing your order. Please try again.',
            ]);
        }
    }

    /**
     * Show the 'new sales ticket' modal
     *
     * @param Request $request
     * @param $ticket_id
     * @return string|View
     */
    public function newSalesTickets(Request $request, $ticket_id, $price)
    {   
        $abecedario = range('A', 'Z'); 
        return view('ManageEvent.Modals.NewSaleTicket', [
            'ticket_id' => $ticket_id,
            'price' => $price,
            'seats' => SeatTicket::where("ticket_id", $ticket_id)->get(),
            'detalles' => Ticket::find($ticket_id),
            'abecedario' => $abecedario
        ]);
    }

    /**
     * @param Request $request
     * @param $event_id
     * @return mixed
     */
    public function showTickets(Request $request, $event_id)
    {
        $allowed_sorts = [
            'created_at' => trans("Controllers.sort.created_at"),
            'title' => trans("Controllers.sort.title"),
            'quantity_sold' => trans("Controllers.sort.quantity_sold"),
            'sales_volume' => trans("Controllers.sort.sales_volume"),
            'sort_order' => trans("Controllers.sort.sort_order"),
        ];

        // Getting get parameters.
        $q = $request->get('q', '');
        $view = $request->get('view', 'full');
        $sort_by = $request->get('sort_by');
        if (isset($allowed_sorts[$sort_by]) === false) {
            $sort_by = 'sort_order';
        }

        // Find event or return 404 error.
        $event = Event::scope()->find($event_id);
        if ($event === null) {
            abort(404);
        }

        // Get tickets for event.
        $tickets = empty($q) === false
            ? $event->tickets()->where('title', 'like', '%' . $q . '%')->orderBy($sort_by, 'asc')->get()
            : $event->tickets()->orderBy($sort_by, 'asc')->get();


        $tickets_all = [];
        foreach ($tickets as $key => $value) {
            $tickets_all[$value->group_zone][] = $value;
        }
            
            // dd($tickets, $tickets_all);
        // Return view.
        return view('ManageEvent.Tickets', compact('event', 'tickets','tickets_all', 'sort_by', 'q', 'allowed_sorts','view'));
    }

    /**
     * Show the edit ticket modal
     *
     * @param $event_id
     * @param $ticket_id
     * @return mixed
     */
    public function showEditTicket($event_id, $ticket_id)
    {
        $ticket = Ticket::scope()->find($ticket_id);      
        $data = [
            'event' => Event::scope()->find($event_id),
            'ticket' => $ticket,
            'quantity_row' => $ticket->select_seat ? implode("-", json_decode($ticket->quantity_row)) : null,
            'seat_white' => $ticket->select_seat ? implode("-", json_decode($ticket->seat_white)) : null,
        ];

        return view('ManageEvent.Modals.EditTicket', $data);
    }

    /**
     * Show the create ticket modal
     *
     * @param $event_id
     * @return \Illuminate\Contracts\View\View
     */
    public function showCreateTicket($event_id)
    {
        
        return view('ManageEvent.Modals.CreateTicket', [
            'event' => Event::scope()->find($event_id),
        ]);
    }


    /**
     * Avert Seat
     *     
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAvertSeat(Request $request)
    {
        $status = $request->get('status');
        $id_seat = $request->get('id');

        $ticket = SeatTicket::find($id_seat);

        // Esta Apartado y pasa a disponible
        if ($ticket->is_available == 0) {
            $ticket->is_available = 1;
            $ticket->save();            
        }

        // Asiento ya vendido 
        if ($ticket->is_available == 3) {
            return response()->json([
                'status' => 'error2',
                'message' => "Asiento ya Comprado."
            ]);
        }

        $ticket->is_available = $status;

        if ($ticket->save()) {
            return response()->json([
                'status' => 'success',
                'message' => "Asiento Apartado o Liberado con Exito"
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => "Hay un error, vuelva a intentarlo",
        ]);
    }

    /**
     * Creates a ticket
     *
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreateTicket(Request $request, $event_id)
    {

        try {
            DB::beginTransaction();

            $ticket = Ticket::createNew();

            if (!$ticket->validate($request->all())) {
                return response()->json([
                    'status' => 'error',
                    'messages' => $ticket->errors(),
                ]);
            }

            $limite = trim($request->get('quantity_available'));
            $select_seat = $request->get('select_seat');

            if ($select_seat) {
                $sillas_blancas = trim($request->get('asientos_blanco'));
                $quantity_row = trim($request->get('quantity_row'));

                if (empty($quantity_row)  or empty($sillas_blancas)) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Cantidad de sillas o filas vacios.',
                    ]);
                }

                $filas = explode("-", $quantity_row);
                $s_blancas  = explode("-", $sillas_blancas);

                $suma_filas = 0;
                foreach ($filas as  $value) {
                    $suma_filas += $value;
                }

                if ($suma_filas != $limite) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'El total por filas es distinto al limite.',
                    ]);
                }
            }

            $ticket->event_id = $event_id;
            $ticket->title = strip_tags($request->get('title'));
            $ticket->quantity_available = $limite;
            $ticket->start_sale_date = $request->get('start_sale_date');
            $ticket->seat_zone = $request->get('seat_zone');
            $ticket->end_sale_date = $request->get('end_sale_date');
            $ticket->price_neto = $request->get('price_neto');
            $ticket->price_service = $request->get('price_service');
            $ticket->price_paypal = $request->get('price_paypal');
            $ticket->price = $request->get('price_neto')+$request->get('price_service');
            $ticket->min_per_person = $request->get('min_per_person');
            $ticket->max_per_person = $request->get('max_per_person');
            $ticket->description = strip_tags($request->get('description'));
            $ticket->is_hidden = $request->get('is_hidden') ? 1 : 0;
            $ticket->group_zone = $request->get('group_zone') =='' ? 'General' : $request->get('group_zone');
            $ticket->quantity_row =  $select_seat ? json_encode($filas) : null;
            $ticket->seat_white =  $select_seat ? json_encode($s_blancas) : null;
            $ticket->select_seat = $select_seat ? 1 : 0;
            $ticket->save();

            if ($select_seat) {
                $asiento = 1;
                // Guarda las sillas disponibles para el tipo de ticket
                for ($i = 1; $i <= count($filas); $i++) {
                    $cantPorFila = $filas[$i - 1];
                    for ($j = 1; $j <= $cantPorFila; $j++) {
                        $seat = new SeatTicket();
                        $seat->row = $i;
                        $seat->column = $asiento;
                        $seat->ticket_id = $ticket->id;
                        $seat->is_available = in_array($asiento, $s_blancas) ? 2 : 1;
                        $seat->save();
                        $asiento += 1;
                    }
                }
            }

            // Attach the access codes to the ticket if it's hidden and the code ids have come from the front
            if ($ticket->is_hidden) {
                $ticketAccessCodes = $request->get('ticket_access_codes', []);
                if (empty($ticketAccessCodes) === false) {
                    // Sync the access codes on the ticket
                    $ticket->event_access_codes()->attach($ticketAccessCodes);
                }
            }

            session()->flash('message', 'Successfully Created Ticket');

            //save the order to the database
            DB::commit();

            return response()->json([
                'status' => 'success',
                'id' => $ticket->id,
                'message' => trans("Controllers.refreshing"),
                'redirectUrl' => route('showEventTickets', [
                    'event_id' => $event_id,
                ]),
            ]);
        } catch (Exception $e) {

            Log::error($e);
            DB::rollBack();

            return response()->json([
                'status' => 'success',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Pause ticket / take it off sale
     *
     * @param Request $request
     * @return mixed
     */
    public function postPauseTicket(Request $request)
    {
        $ticket_id = $request->get('ticket_id');

        $ticket = Ticket::scope()->find($ticket_id);

        $ticket->is_paused = ($ticket->is_paused == 1) ? 0 : 1;

        if ($ticket->save()) {
            return response()->json([
                'status' => 'success',
                'message' => trans("Controllers.ticket_successfully_updated"),
                'id' => $ticket->id,
            ]);
        }

        Log::error('Ticket Failed to pause/resume', [
            'ticket' => $ticket,
        ]);

        return response()->json([
            'status' => 'error',
            'id' => $ticket->id,
            'message' => trans("Controllers.whoops"),
        ]);
    }

    /**
     * Deleted a ticket
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDeleteTicket(Request $request)
    {
        $ticket_id = $request->get('ticket_id');

        $ticket = Ticket::scope()->find($ticket_id);

        /*
         * Don't allow deletion of tickets which have been sold already.
         */
        if ($ticket->quantity_sold > 0) {
            return response()->json([
                'status' => 'error',
                'message' => trans("Controllers.cant_delete_ticket_when_sold"),
                'id' => $ticket->id,
            ]);
        }

        if ($ticket->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => trans("Controllers.ticket_successfully_deleted"),
                'id' => $ticket->id,
            ]);
        }

        Log::error('Ticket Failed to delete', [
            'ticket' => $ticket,
        ]);

        return response()->json([
            'status' => 'error',
            'id' => $ticket->id,
            'message' => trans("Controllers.whoops"),
        ]);
    }

    /**
     * Edit a ticket
     *
     * @param Request $request
     * @param $event_id
     * @param $ticket_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEditTicket(Request $request, $event_id, $ticket_id)
    {
        $ticket = Ticket::scope()->findOrFail($ticket_id);

        /*
         * Add validation message
         */
        $validation_messages['quantity_available.min'] = trans("Controllers.quantity_min_error");
        $ticket->messages = $validation_messages + $ticket->messages;

        if (!$ticket->validate($request->all())) {
            return response()->json([
                'status' => 'error',
                'messages' => $ticket->errors(),
            ]);
        }

        $limite = trim($request->get('quantity_available'));
        $select_seat = $request->get('select_seat');

        if ($select_seat) {
            $sillas_blancas = trim($request->get('seat_white'));
            $quantity_row = trim($request->get('quantity_row'));

            if (empty($quantity_row)  or empty($sillas_blancas)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Cantidad de sillas o filas vacios.',
                ]);
            }

            $filas = explode("-", $quantity_row);
            $s_blancas  = explode("-", $sillas_blancas);

            $suma_filas = 0;
            foreach ($filas as  $value) {
                $suma_filas += $value;
            }

            if ($suma_filas != $limite) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'El total por filas es distinto al limite.',
                ]);
            }
        }

        $seat_anterior = $ticket->select_seat;

        // Check if the ticket visibility changed on update
        $ticketPreviouslyHidden = (bool) $ticket->is_hidden;
        $ticket->title = $request->get('title');
        $ticket->quantity_available = !$request->get('quantity_available') ? null : $request->get('quantity_available');
        $ticket->price_neto = $request->get('price_neto');
        $ticket->price_service = $request->get('price_service');
        $ticket->price_paypal = $request->get('price_paypal');
        $ticket->price = $request->get('price_neto')+$request->get('price_service');
        $ticket->seat_zone = $request->get('seat_zone');
        $ticket->start_sale_date = $request->get('start_sale_date');
        $ticket->end_sale_date = $request->get('end_sale_date');
        $ticket->description = $request->get('description');
        $ticket->min_per_person = $request->get('min_per_person');
        $ticket->max_per_person = $request->get('max_per_person');
        $ticket->is_hidden = $request->get('is_hidden') ? 1 : 0;
        $ticket->quantity_row =  $select_seat ? json_encode($filas) : null;
        $ticket->seat_white =  $select_seat ? json_encode($s_blancas) : null;
        $ticket->select_seat = $select_seat ? 1 : 0;
        $ticket->group_zone = $request->get('group_zone') =='' ? 'General' : $request->get('group_zone');
        $ticket->save();

        if ($select_seat && $seat_anterior == 0) {
            $asiento = 1;
            // Guarda las sillas disponibles para el tipo de ticket
            for ($i = 1; $i <= count($filas); $i++) {
                $cantPorFila = $filas[$i - 1];
                for ($j = 1; $j <= $cantPorFila; $j++) {
                    $seat = new SeatTicket();
                    $seat->row = $i;
                    $seat->column = $asiento;
                    $seat->ticket_id = $ticket->id;
                    $seat->is_available = in_array($asiento, $s_blancas) ? 2 : 1;
                    $seat->save();
                    $asiento += 1;
                }
            }
        }

        // Attach the access codes to the ticket if it's hidden and the code ids have come from the front
        if ($ticket->is_hidden) {
            $ticketAccessCodes = $request->get('ticket_access_codes', []);
            if (empty($ticketAccessCodes) === false) {
                // Sync the access codes on the ticket
                $ticket->event_access_codes()->detach();
                $ticket->event_access_codes()->attach($ticketAccessCodes);
            }
        } else if ($ticketPreviouslyHidden) {
            // Delete access codes on ticket if the visibility changed to visible
            $ticket->event_access_codes()->detach();
        }

        return response()->json([
            'status' => 'success',
            'id' => $ticket->id,
            'message' => trans("Controllers.refreshing"),
            'redirectUrl' => route('showEventTickets', [
                'event_id' => $event_id,
            ]),
        ]);
    }

    /**
     * Updates the sort order of tickets
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postUpdateTicketsOrder(Request $request)
    {
        $ticket_ids = $request->get('ticket_ids');
        $sort = 1;

        foreach ($ticket_ids as $ticket_id) {
            $ticket = Ticket::scope()->find($ticket_id);
            $ticket->sort_order = $sort;
            $ticket->save();
            $sort++;
        }

        return response()->json([
            'status' => 'success',
            'message' => trans("Controllers.ticket_order_successfully_updated"),
        ]);
    }

    

    public function newSalesTicketsSales($ticket_id)
    {   
        $ticket = Ticket::find($ticket_id);
        return view('ManageEvent.Modals.NewSaleTicketPage', [
            'ticket_id' => $ticket_id,
            'price' => $ticket->price,
            'seats' => SeatTicket::where("ticket_id", $ticket_id)->get(),
            'detalles' => $ticket,
            'event' => $ticket->event
        ]);
    }
    
}
