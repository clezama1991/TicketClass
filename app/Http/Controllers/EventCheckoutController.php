<?php

namespace App\Http\Controllers;
use App\Jobs\SendOrderTickets;

use App\Events\OrderCompletedEvent;
use App\Models\Account;
use App\Models\AccountPaymentGateway;
use App\Models\Affiliate;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\EventStats;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentGateway;
use App\Models\QuestionAnswer;
use App\Models\ReservedTickets;
use App\Models\Ticket;
use App\Models\OrderComments;
use App\Services\Order as OrderService;
use Carbon\Carbon;
use Cookie;
use DB;
use Illuminate\Http\Request;
use Log;
use Omnipay;
use PDF;
use PhpSpec\Exception\Exception;
use Validator;
use App\Models\SeatTicket;
use Mail;

use Openpay;
use OpenpayApiError;
use OpenpayApiAuthError;
use OpenpayApiRequestError;
use OpenpayApiConnectionError;
use OpenpayApiTransactionError;
use Illuminate\Http\JsonResponse;
 
class EventCheckoutController extends Controller
{
    /**
     * Is the checkout in an embedded Iframe?
     *
     * @var bool
     */
    protected $is_embedded;

    /**
     * EventCheckoutController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        /*
         * See if the checkout is being called from an embedded iframe.
         */
        $this->is_embedded = $request->get('is_embedded') == '1';
    }

    /**
     * Validate a ticket request. If successful reserve the tickets and redirect to checkout
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function postValidateTickets(Request $request, $event_id)
    {
        // dd($request);
        /*
         * Order expires after X min
         */
        try {
            //code...
        $order_expires_time = Carbon::now()->addMinutes(config('attendize.checkout_timeout_after'));

        $event = Event::findOrFail($event_id);

        if (!$request->has('tickets')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No tickets selected',
            ]);
        }

        $ticket_ids = $request->get('tickets');
        $asientos_ids = $request->get('asientos');
         /*
         * Remove any tickets the user has reserved
         */
        ReservedTickets::where('session_id', '=', session()->getId())->delete();

        /*
         * Go though the selected tickets and check if they're available
         * , tot up the price and reserve them to prevent over selling.
         */

        $validation_rules = [];
        $validation_messages = [];
        $tickets = [];
        $order_total = 0;
        $order_total_neto = 0;
        $order_total_price_service = 0;
        $order_neto_price_online = 0;
        $amount_payment = 0;
        $total_ticket_quantity = 0;
        $booking_fee = 0;
        $organiser_booking_fee = 0;
        $quantity_available_validation_rules = [];

        foreach ($ticket_ids as $ticket_id) {
            $current_ticket_quantity = (int)$request->get('ticket_' . $ticket_id);

            if ($current_ticket_quantity < 1) {
                continue;
            }

            $total_ticket_quantity = $total_ticket_quantity + $current_ticket_quantity;
            $ticket = Ticket::find($ticket_id);
            $ticket_quantity_remaining = $ticket->quantity_remaining;
            $max_per_person = min($ticket_quantity_remaining, $ticket->max_per_person);

            $quantity_available_validation_rules['ticket_' . $ticket_id] = [
                'numeric',
                'min:' . $ticket->min_per_person,
                'max:' . $max_per_person
            ];

            $quantity_available_validation_messages = [
                'ticket_' . $ticket_id . '.max' => 'The maximum number of tickets you can register is ' . $ticket_quantity_remaining,
                'ticket_' . $ticket_id . '.min' => 'You must select at least ' . $ticket->min_per_person . ' tickets.',
            ];

            if (env('APP_ENV')=='production') {
                $validator = Validator::make(['ticket_' . $ticket_id => (int)$request->get('ticket_' . $ticket_id)],
                    $quantity_available_validation_rules, $quantity_available_validation_messages);

                if ($validator->fails()) {
                    return response()->json([
                        'status'   => 'error',
                        'messages' => $validator->messages()->toArray(),
                    ]);
                }
            }


            $price_tickets_online = ($ticket->price + $ticket->price_paypal);

            $order_total = $order_total + ($current_ticket_quantity * $price_tickets_online);
            $order_neto_price_online = ($current_ticket_quantity * ($ticket->price_neto + $ticket->price_paypal));
            $order_neto_price_without_paypal = ($current_ticket_quantity * $ticket->price_neto);
            $order_total_neto = $order_total_neto + ($current_ticket_quantity * $ticket->price_neto);
            $order_total_price_service = $order_total_price_service + ($current_ticket_quantity * $ticket->price_service);
            $booking_fee = $booking_fee + ($current_ticket_quantity * $ticket->booking_fee);
            $organiser_booking_fee = $organiser_booking_fee + ($current_ticket_quantity * $ticket->organiser_booking_fee);

            $tickets[] = [
                'ticket'                => $ticket,
                
                'asientos_ids'      => $asientos_ids,
                'qty'                   => $current_ticket_quantity,
                'price'                 => ($current_ticket_quantity * $price_tickets_online),
                'booking_fee'           => $order_total_price_service,
                'order_total_price_service'           => $order_total_price_service,
                'organiser_booking_fee' => ($current_ticket_quantity * $ticket->organiser_booking_fee),
                'full_price'            => $price_tickets_online + $ticket->total_booking_fee,
            ];

            /*
             * Reserve the tickets for X amount of minutes
             */
            $reservedTickets = new ReservedTickets();
            $reservedTickets->ticket_id = $ticket_id;
            $reservedTickets->event_id = $event_id;
            $reservedTickets->quantity_reserved = $current_ticket_quantity;
            $reservedTickets->seat_reserved = $asientos_ids;
            $reservedTickets->expires = $order_expires_time;
            $reservedTickets->session_id = session()->getId();
            $reservedTickets->save();

            for ($i = 0; $i < $current_ticket_quantity; $i++) {
                /*
                 * Create our validation rules here
                 */
                $validation_rules['ticket_holder_first_name.' . $i . '.' . $ticket_id] = ['required'];
                $validation_rules['ticket_holder_last_name.' . $i . '.' . $ticket_id] = ['required'];
                $validation_rules['ticket_holder_email.' . $i . '.' . $ticket_id] = ['required', 'email'];

                $validation_messages['ticket_holder_first_name.' . $i . '.' . $ticket_id . '.required'] = 'Ticket holder ' . ($i + 1) . '\'s first name is required';
                $validation_messages['ticket_holder_last_name.' . $i . '.' . $ticket_id . '.required'] = 'Ticket holder ' . ($i + 1) . '\'s last name is required';
                $validation_messages['ticket_holder_email.' . $i . '.' . $ticket_id . '.required'] = 'Ticket holder ' . ($i + 1) . '\'s email is required';
                $validation_messages['ticket_holder_email.' . $i . '.' . $ticket_id . '.email'] = 'Ticket holder ' . ($i + 1) . '\'s email appears to be invalid';

                /*
                 * Validation rules for custom questions
                 */
                foreach ($ticket->questions as $question) {
                    if ($question->is_required && $question->is_enabled) {
                        $validation_rules['ticket_holder_questions.' . $ticket_id . '.' . $i . '.' . $question->id] = ['required'];
                        $validation_messages['ticket_holder_questions.' . $ticket_id . '.' . $i . '.' . $question->id . '.required'] = "This question is required";
                    }
                }
            }
        }

        if (empty($tickets)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No tickets selected.',
            ]);
        }

        if (config('attendize.enable_dummy_payment_gateway') == TRUE) {
            $activeAccountPaymentGateway = new AccountPaymentGateway();
            $activeAccountPaymentGateway->fill(['payment_gateway_id' => config('attendize.payment_gateway_dummy')]);
            $paymentGateway = $activeAccountPaymentGateway;
        } else {
            $activeAccountPaymentGateway = $event->account->getGateway($event->account->payment_gateway_id);
            //if no payment gateway configured and no offline pay, don't go to the next step and show user error
            if (empty($activeAccountPaymentGateway) && !$event->enable_offline_payments) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'No payment gateway configured',
                ]);
            }
            $paymentGateway = $activeAccountPaymentGateway ? $activeAccountPaymentGateway->payment_gateway : false;
        }

        /*
         * The 'ticket_order_{event_id}' session stores everything we need to complete the transaction.
         */
        session()->put('ticket_order_' . $event->id, [
            'validation_rules'        => $validation_rules,
            'validation_messages'     => $validation_messages,
            'event_id'                => $event->id,
            'tickets'                 => $tickets,
            'total_ticket_quantity'   => $total_ticket_quantity,
            'order_started'           => time(),
            'expires'                 => $order_expires_time,
            'reserved_tickets_id'     => $reservedTickets->id,
            'order_total'             => $order_total,
            'order_total_neto'             => $order_total_neto,
            'order_neto_price_online'             => $order_neto_price_online,
            'order_neto_price_without_paypal'             => $order_neto_price_without_paypal,
            'order_total_price_service'             => $order_total_price_service,
            'booking_fee'             => $booking_fee,
            'organiser_booking_fee'   => $organiser_booking_fee,
            'total_booking_fee'       => $booking_fee + $organiser_booking_fee,
            'order_requires_payment'  => (ceil($order_total) == 0) ? false : true,
            'account_id'              => $event->account->id,
            'affiliate_referral'      => Cookie::get('affiliate_' . $event_id),
            'account_payment_gateway' => $activeAccountPaymentGateway,
            'payment_gateway'         => $paymentGateway
        ]);

        /*
         * If we're this far assume everything is OK and redirect them
         * to the the checkout page.
         */
        if ($request->ajax()) {
                return response()->json([
                    'status'      => 'success',
                    'redirectUrl' => route('showEventCheckout', [
                            'event_id'    => $event_id,
                            'is_embedded' => $this->is_embedded,
                        ]) . '#order_form',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => $th->getMessage(),
            ]);
        }

        /*
         * Maybe display something prettier than this?
         */
        exit('Please enable Javascript in your browser.');
    }

    /**
     * Show the checkout page
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showEventCheckout(Request $request, $event_id)
    {

        $OPENPAY_ID = null;
        $OPENPAY_SK = null;            
        $OPENPAY_PRODUCTION_MODE = false;

        $order_session = session()->get('ticket_order_' . $event_id);

        if (!$order_session || $order_session['expires'] < Carbon::now()) {
            $route_name = $this->is_embedded ? 'showEmbeddedEventPage' : 'showEventPage';
            return redirect()->route($route_name, ['event_id' => $event_id]);
        }

        $secondsToExpire = Carbon::now()->diffInSeconds($order_session['expires']);

        $event = Event::findorFail($order_session['event_id']);

        
        $activeAccountPaymentGateway = $event->account->getGateway($event->account->payment_gateway_id);

        if(isset($activeAccountPaymentGateway->config['production_mode'])){
            if($activeAccountPaymentGateway->config['production_mode']=='0'){
                $OPENPAY_ID = $activeAccountPaymentGateway->config['apiId0'];
                $OPENPAY_SK = $activeAccountPaymentGateway->config['apiKeyPublic0'];            
                $OPENPAY_PRODUCTION_MODE = false;            
            }else{
                $OPENPAY_ID = $activeAccountPaymentGateway->config['apiId1'];
                $OPENPAY_SK = $activeAccountPaymentGateway->config['apiKeyPublic1'];            
                $OPENPAY_PRODUCTION_MODE = true;            
            }
        }


        $orderService = new OrderService($order_session['order_total'], $order_session['total_booking_fee'], $event);
        $orderService->calculateFinalCosts();

        $data = $order_session + [
                'event'           => $event,
                'secondsToExpire' => $secondsToExpire,
                'is_embedded'     => $this->is_embedded,
                'orderService'    => $orderService,
                'OPENPAY_ID'    => $OPENPAY_ID,
                'OPENPAY_SK'    => $OPENPAY_SK,
                'OPENPAY_PRODUCTION_MODE'    => $OPENPAY_PRODUCTION_MODE
                ];

        if ($this->is_embedded) {
            return view('Public.ViewEvent.Embedded.EventPageCheckout', $data);
        }

        return view('Public.ViewEvent.EventPageCheckout', $data);
    }

    /**
     * Create the order, handle payment, update stats, fire off email jobs then redirect user
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function postCompletedOrder(Request $request)
    {

        $transaction_id = $request->get('id');
		
        $openpay = Openpay::getInstance('mgzijadvcenoigcrf4bo','sk_6e2174165e644e42b17d32409e44069c'); 
		$charge = $openpay->charges->get($transaction_id); 
		
		$order = Order::where('transaction_id', '=', $transaction_id)->first();
		
		if($charge->status=='completed'){

            OrderComments::create([
                'event_id' => $order->id,
                'comment'     => 'Orden completada por Openpay',
            ]);

			$order->is_completed_payment = true;
			$order->save();

            $order = Order::where('transaction_id', '=', $transaction_id)->first();

            $file_name = $order->order_reference;
            $file_path = public_path(config('attendize.event_pdf_tickets_path')) . '/' . $file_name . '.pdf';
            $file_exists = file_exists($file_path);
            $data = [
                'order'        => $order,
                'attendee'        => $order->attendees,
                'message_content' => 'jeje bien',
                'subject'         => 'Compra Exitosa',
                'event'           => $order->event,
                'email_logo'      => $order->event->organiser->full_logo_path,
                'file_path'      => $file_path,
                'file_exists'      => $file_exists,
            ];

            Mail::send('Emails.messageTicketsSalesCompleted', $data, function ($message) use ($order, $data, $file_path,$file_exists) {
                $message->to($order->email, $order->first_name)
                    ->subject($data['subject'] . ' - Evento '.$order->event->title);
                    if($file_exists){
                        $message->attach($file_path);
                    }
            });
 
            OrderComments::create([
                'event_id' => $order->id,
                'comment'     => 'Correo enviado en la Orden',
            ]);

			$url = route('showOrderDetails', [
				'is_embedded'     => $this->is_embedded,
				'order_reference' => $order->order_reference,
			]);

		}else{
		
            OrderComments::create([
                'event_id' => $order->id,
                'comment'     => 'Orden Anulada por OpenPay por '.$charge->status,
            ]);

			$attendees = Attendee::where('order_id','=',$order->id)->get();

			foreach ($attendees ?? [] as $value) {

				$attendee_id = $value->id;
				$attendee = Attendee::findOrFail($attendee_id);
				$error_message = false;


				$attendee->ticket->decrement('quantity_sold');
				$attendee->ticket->decrement('sales_volume', $attendee->ticket->price);
				$attendee->ticket->decrement('organiser_fees_volume', $attendee->ticket->price_service);
				$attendee->ticket->event->decrement('sales_volume', $attendee->ticket->price);
				$attendee->ticket->event->decrement('organiser_fees_volume', $attendee->ticket->price_service);
				$attendee->is_cancelled = 1;
				$attendee->save();

				$eventStats = EventStats::where('event_id', $attendee->event_id)->where('date', $attendee->created_at->format('Y-m-d'))->first();
				if($eventStats){
					$eventStats->decrement('tickets_sold',  1);
					$eventStats->decrement('sales_volume',  $attendee->ticket->price);
					$eventStats->decrement('organiser_fees_volume',  $attendee->ticket->price_service);
				}

				$order->order_status_id = 4;
				$order->is_cancelled = true;
				$order->delete_by = 'OpenPay por '.$charge->status;
				$order->save();
				$order->delete();

				if(!is_null($order->seating)){
					$array=explode(',',$order->seating); 
					$seatings = SeatTicket::whereIn('id',$array)->get();
					foreach ($seatings as $key => $seating) {
						$seating->is_available = 1;
						$seating->save();
					}
				}
			}
			
			session()->flash('message', 'El Pago no fue completado satisfactoriamente');
			
			$url = route('showEventCheckout', [
				'event_id'     => $order->event_id,
				'is_payment_failed' => 1,
			]);
		}
		
		return redirect($url);
    }



    public function postCancelOrder(Request $request)
    {
        
        $transaction_id = $request->get('id');
        $order = Order::where('transaction_id', '=', $transaction_id)->first();
        
        $attendees = Attendee::where('order_id','=',$order)->get();

        foreach ($attendees as $key => $value) {
            $attendee_id = $value->id;
            $attendee = Attendee::scope()->findOrFail($attendee_id);
            $error_message = false; //Prevent "variable doesn't exist" error message
 

            $attendee->ticket->decrement('quantity_sold');
            $attendee->ticket->decrement('sales_volume', $attendee->ticket->price);
            $attendee->ticket->event->decrement('sales_volume', $attendee->ticket->price);
            $attendee->is_cancelled = 1;
            $attendee->save();

            $eventStats = EventStats::where('event_id', $attendee->event_id)->where('date', $attendee->created_at->format('Y-m-d'))->first();
            if($eventStats){
                $eventStats->decrement('tickets_sold',  1);
                $eventStats->decrement('sales_volume',  $attendee->ticket->price);
            }
 
            OrderComments::create([
                'event_id' => $order->id,
                'comment'     => 'Orden Cancelada por un usuario'
            ]);

            $order->order_status_id = 4;
            $order->is_cancelled = true;
			$order->delete_by = 'postCancelOrder';
            $order->save();

        } 




    }

    public function postCreateOrder(Request $request, $event_id)
    {
        //If there's no session kill the request and redirect back to the event homepage.
        if (!session()->get('ticket_order_' . $event_id)) {
            return response()->json([
                'status'      => 'error',
                'message'     => 'Your session has expired.',
                'redirectUrl' => route('showEventPage', [
                    'event_id' => $event_id,
                ])
            ]);
        }

        $event = Event::findOrFail($event_id);
        $order = new Order();
        $ticket_order = session()->get('ticket_order_' . $event_id);

        $validation_rules = $ticket_order['validation_rules'];
        $validation_messages = $ticket_order['validation_messages'];

        $order->rules = $order->rules + $validation_rules;
        $order->messages = $order->messages + $validation_messages;

        if (env('APP_ENV')=='production') {
            if (!$order->validate($request->all())) {
                return response()->json([
                    'status'   => 'error',
                    'messages' => $order->errors(),
                ]);
            }
        }

        //Add the request data to a session in case payment is required off-site
        session()->push('ticket_order_' . $event_id . '.request_data', $request->except(['card-number', 'card-cvc']));

        $orderRequiresPayment = $ticket_order['order_requires_payment'];

        if (env('APP_ENV')=='local') {
            // return $this->completeOrder($event_id);
        }
         
        if ($orderRequiresPayment && $request->get('pay_offline') && $event->enable_offline_payments) {
            return $this->completeOrder($event_id);
        }

        if (!$orderRequiresPayment) {
            return $this->completeOrder($event_id);
        }

                


        try {
            //more transation data being put in here.
            $transaction_data = [];

            
            $orderService = new OrderService($ticket_order['order_total'], $ticket_order['total_booking_fee'], $event);
            $orderService->calculateFinalCosts();

            $transaction_data += [
                    'amount'      => $orderService->getGrandTotal(),
                    'currency'    => $event->currency->code,
                    'description' => 'Order for customer: ' . $request->get('order_email'),
                    'order_id' => $order->id,
            ];


            if($ticket_order['payment_gateway']->id == config('attendize.payment_gateway_openpay')){

				$ticket_order = session()->get('ticket_order_' . $event_id);
				$request_data = $ticket_order['request_data'][0];

                $payment = $this->ProccessPaymentOpenpay($request, $event, $transaction_data, $request_data);
                
                if($payment['error_code']!='200'){
					session()->flash('message', $payment['description']);
					return response()->redirectToRoute('showEventCheckout', [
						'event_id'          => $event_id,
						'is_payment_failed' => 1,
					]);
                    

                }else{

                    session()->push('ticket_order_' . $event_id . '.transaction_id',
                    $payment['data']);
    
                    $this->completeOrder($event_id, true, true);

                    
                    return response()->json([
                        'status'      => 'success',
                        'message'     => 'Your session has expired.',
                        'redirectUrl' => $payment['url']
                    ]);
 

                }

 
            }else{
                

                if (config('attendize.enable_dummy_payment_gateway') == TRUE) {
                    $formData = config('attendize.fake_card_data');
                    $transaction_data = [
                        'card' => $formData
                    ];

                    $gateway = Omnipay::create('Dummy');
                    $gateway->initialize();

                } else {
                    $gateway = Omnipay::create($ticket_order['payment_gateway']->name);
                    $gateway->initialize($ticket_order['account_payment_gateway']->config + [
                            'testMode' => config('attendize.enable_test_payments'),
                        ]);
                }

                //TODO: class with an interface that builds the transaction data.
                switch ($ticket_order['payment_gateway']->id) {
                    case config('attendize.payment_gateway_dummy'):
                        $token = uniqid();
                        $transaction_data += [
                            'token'         => $token,
                            'receipt_email' => $request->get('order_email'),
                            'card' => $formData
                        ];
                        break;
                    case config('attendize.payment_gateway_paypal'):

                        $transaction_data += [
                            'cancelUrl' => route('showEventCheckoutPaymentReturn', [
                                'event_id'             => $event_id,
                                'is_payment_cancelled' => 1
                            ]),
                            'returnUrl' => route('showEventCheckoutPaymentReturn', [
                                'event_id'              => $event_id,
                                'is_payment_successful' => 1
                            ]),
                            'brandName' => isset($ticket_order['account_payment_gateway']->config['brandingName'])
                                ? $ticket_order['account_payment_gateway']->config['brandingName']
                                : $event->organiser->name
                        ];
                        break;
                    case config('attendize.payment_gateway_stripe'):
                        $token = $request->get('stripeToken');
                        $transaction_data += [
                            'token'         => $token,
                            'receipt_email' => $request->get('order_email'),
                        ];
                        break;
                    default:
                        Log::error('No payment gateway configured.');
                        return response()->json([
                            'status'  => 'error',
                            'message' => 'No payment gateway configured.'
                        ]);
                        break;
                }

                $transaction = $gateway->purchase($transaction_data);

                $response = $transaction->send();

                if ($response->isSuccessful()) {

                    session()->push('ticket_order_' . $event_id . '.transaction_id',
                        $response->getTransactionReference());

                    return $this->completeOrder($event_id);

                } elseif ($response->isRedirect()) {

                    /*
                    * As we're going off-site for payment we need to store some data in a session so it's available
                    * when we return
                    */
                    session()->push('ticket_order_' . $event_id . '.transaction_data', $transaction_data);
                    Log::info("Redirect url: " . $response->getRedirectUrl());

                    $return = [
                        'status'       => 'success',
                        'redirectUrl'  => $response->getRedirectUrl(),
                        'message'      => 'Redirecting to ' . $ticket_order['payment_gateway']->provider_name
                    ];

                    // GET method requests should not have redirectData on the JSON return string
                    if($response->getRedirectMethod() == 'POST') {
                        $return['redirectData'] = $response->getRedirectData();
                    }

                    return response()->json($return);

                } else {
                    // display error to customer
                    return response()->json([
                        'status'  => 'error',
                        'message' => $response->getMessage(),
                    ]);
                }
            }

        } catch (\Exeption $e) {
            Log::error($e);
            $error = 'Sorry, there was an error processing your payment. Please try again.';
        }

        if ($error) {
            return response()->json([
                'status'  => 'error',
                'message' => $error,
            ]);
        }

    }


    /**
     * Attempt to complete a user's payment when they return from
     * an off-site gateway
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function showEventCheckoutPaymentReturn(Request $request, $event_id)
    {

        if ($request->get('is_payment_cancelled') == '1') {
            session()->flash('message', trans('Event.payment_cancelled'));
            return response()->redirectToRoute('showEventCheckout', [
                'event_id'             => $event_id,
                'is_payment_cancelled' => 1,
            ]);
        }

        $ticket_order = session()->get('ticket_order_' . $event_id);
        $gateway = Omnipay::create($ticket_order['payment_gateway']->name??'PayPal_Express');

        $gateway->initialize($ticket_order['account_payment_gateway']->config + [
                'testMode' => config('attendize.enable_test_payments'),
            ]);

        $transaction = $gateway->completePurchase($ticket_order['transaction_data'][0]);

        $response = $transaction->send();

        if ($response->isSuccessful()) {
            session()->push('ticket_order_' . $event_id . '.transaction_id', $response->getTransactionReference());
            return $this->completeOrder($event_id, false);
        } else {
            session()->flash('message', $response->getMessage());
            return response()->redirectToRoute('showEventCheckout', [
                'event_id'          => $event_id,
                'is_payment_failed' => 1,
            ]);
        }

    }

    /**
     * Complete an order
     *
     * @param $event_id
     * @param bool|true $return_json
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function completeOrder($event_id, $return_json = true, $openpay = false)
    {

        DB::beginTransaction();

        try {

            $seat_reserved = ReservedTickets::where('session_id', '=', session()->getId())->where('event_id', $event_id)->first()->seat_reserved;
            $order = new Order();
            $order->seating = $seat_reserved;
            $seatings = explode(",",$seat_reserved);
            $ticket_order = session()->get('ticket_order_' . $event_id);
            $request_data = $ticket_order['request_data'][0];
            $event = Event::findOrFail($ticket_order['event_id']);
            $attendee_increment = 1;
            $ticket_questions = isset($request_data['ticket_holder_questions']) ? $request_data['ticket_holder_questions'] : [];
            
            // dd($seat_reserved,$seatings);

            /*
             * Create the order
             */
            if (isset($ticket_order['transaction_id'])) {
                $order->transaction_id = $ticket_order['transaction_id'][0];
            }
            if ($ticket_order['order_requires_payment'] && !isset($request_data['pay_offline'])) {
                $order->payment_gateway_id = $ticket_order['payment_gateway']->id;
            }
            $order->first_name = strip_tags($request_data['order_first_name']).' '.strip_tags($request_data['order_last_name']);
            $order->last_name = null;
            $order->email = $request_data['order_email'];
            $order->order_status_id = isset($request_data['pay_offline']) ? config('attendize.order_awaiting_payment') : config('attendize.order_complete');
            $order->amount = $ticket_order['order_neto_price_without_paypal'];
            $order->order_date = DB::raw('CURRENT_DATE');
            $order->amount_payment = $ticket_order['order_neto_price_online'];
            $order->services_fee = $ticket_order['order_total_price_service'];
            $order->booking_fee = $ticket_order['booking_fee'];
            $order->organiser_booking_fee = $ticket_order['organiser_booking_fee'];
            $order->discount = 0.00;
            $order->account_id = $event->account->id;
            $order->event_id = $ticket_order['event_id'];
            $order->is_payment_received = isset($request_data['pay_offline']) ? 0 : 1;
            $order->is_completed_payment = ($openpay) ? 0 : 1;


            // Calculating grand total including tax
            $orderService = new OrderService($ticket_order['order_total'], $ticket_order['total_booking_fee'], $event);
            $orderService->calculateFinalCosts();

            $order->taxamt = $orderService->getTaxAmount();
            $order->save();

            /*
             * Update the event sales volume
             */
            // $event->increment('sales_volume', $orderService->getGrandTotal());
            $event->increment('sales_volume', $order->amount);
            $event->increment('organiser_fees_volume', ($order->organiser_booking_fee+$order->services_fee));

            /*
             * Update affiliates stats stats
             */
            if ($ticket_order['affiliate_referral']) {
                $affiliate = Affiliate::where('name', '=', $ticket_order['affiliate_referral'])
                    ->where('event_id', '=', $event_id)->first();
                $affiliate->increment('sales_volume', $order->amount + $order->services_fee);
                $affiliate->increment('tickets_sold', $ticket_order['total_ticket_quantity']);
            }

            /*
             * Update the event stats
             */
            $event_stats = EventStats::updateOrCreate([
                'event_id' => $event_id,
                'date'     => DB::raw('CURRENT_DATE'),
            ]);
            $event_stats->increment('tickets_sold', $ticket_order['total_ticket_quantity']);

            if ($ticket_order['order_requires_payment']) {
                $event_stats->increment('sales_volume', $order->amount);
                $event_stats->increment('organiser_fees_volume', $order->services_fee);
            }

            /*
             * Add the attendees
             */
            $posicion_seat = 0;
            foreach ($ticket_order['tickets'] as $attendee_details) {

                /*
                 * Update ticket's quantity sold
                 */
                $ticket = Ticket::findOrFail($attendee_details['ticket']['id']);

                /*
                 * Update some ticket info
                 */
                // $neto = $attendee_details['ticket']['price_neto'] + $attendee_details['ticket']['price_paypal'];
                $neto = $attendee_details['ticket']['price_neto'];
                $ticket->increment('quantity_sold', $attendee_details['qty']);
                $ticket->increment('sales_volume', ($neto * $attendee_details['qty']));
                $ticket->increment('organiser_fees_volume',($attendee_details['order_total_price_service']));

                /*
                 * Insert order items (for use in generating invoices)
                 */
                $orderItem = new OrderItem();
                $orderItem->title = $attendee_details['ticket']['title'];
                $orderItem->quantity = $attendee_details['qty'];
                $orderItem->order_id = $order->id;
                $orderItem->unit_price = $neto;
                $orderItem->unit_booking_fee = $attendee_details['ticket']['booking_fee'] + $attendee_details['ticket']['organiser_booking_fee'] + $attendee_details['ticket']['price_service'];
                $orderItem->save();

                /*
                 * Create the attendees
                 */
                for ($i = 0; $i < $attendee_details['qty']; $i++) {

                    $seat = null;
                    if($attendee_details['ticket']['select_seat']==1 && isset($seatings[$posicion_seat])){
                        $seat = $seatings[$posicion_seat];
                        $posicion_seat++;
                    }

                    $attendee = new Attendee();
                    $attendee->first_name = strip_tags($request_data["ticket_holder_first_name"][$i][$attendee_details['ticket']['id']]);
                    $attendee->last_name = strip_tags($request_data["ticket_holder_last_name"][$i][$attendee_details['ticket']['id']]);
                    $attendee->email = $request_data["ticket_holder_email"][$i][$attendee_details['ticket']['id']];
                    $attendee->event_id = $event_id;
                    $attendee->order_id = $order->id;
                    $attendee->ticket_id = $attendee_details['ticket']['id'];
                    $attendee->account_id = $event->account->id;
                    $attendee->reference_index = $attendee_increment;
                    $attendee->seat = $seat;
                    $attendee->save();
                    /*
                     * Save the attendee's questions
                     */
                    foreach ($attendee_details['ticket']->questions as $question) {


                        $ticket_answer = isset($ticket_questions[$attendee_details['ticket']->id][$i][$question->id]) ? $ticket_questions[$attendee_details['ticket']->id][$i][$question->id] : null;

                        if (is_null($ticket_answer)) {
                            continue;
                        }

                        /*
                         * If there are multiple answers to a question then join them with a comma
                         * and treat them as a single answer.
                         */
                        $ticket_answer = is_array($ticket_answer) ? implode(', ', $ticket_answer) : $ticket_answer;

                        if (!empty($ticket_answer)) {
                            QuestionAnswer::create([
                                'answer_text' => $ticket_answer,
                                'attendee_id' => $attendee->id,
                                'event_id'    => $event->id,
                                'account_id'  => $event->account->id,
                                'question_id' => $question->id
                            ]);

                        }
                    }


                    /* Keep track of total number of attendees */
                    $attendee_increment++;
                }

                foreach ($seatings as $key => $value) {
                    # code...
                    SeatTicket::whereId($value)->update(['is_available'=>3]);
                }

            }

        } catch (Exception $e) {

            Log::error($e);
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Whoops! There was a problem processing your order. Please try again.'
            ]);

        }
        //save the order to the database
        DB::commit();
        //forget the order in the session
        session()->forget('ticket_order_' . $event->id);

        /*
         * Remove any tickets the user has reserved after they have been ordered for the user
         */
        ReservedTickets::where('session_id', '=', session()->getId())->delete();

        // Queue up some tasks - Emails to be sent, PDFs etc.
        Log::info('Firing the event');
        event(new OrderCompletedEvent($order));

        if ($return_json) {
            return response()->json([
                'status'      => 'success',
                'redirectUrl' => route('showOrderDetails', [
                    'is_embedded'     => $this->is_embedded,
                    'order_reference' => $order->order_reference,
                ]),
            ]);
        }

        return response()->redirectToRoute('showOrderDetails', [
            'is_embedded'     => $this->is_embedded,
            'order_reference' => $order->order_reference,
        ]);


    }

    /**
     * Show the order details page
     *
     * @param Request $request
     * @param $order_reference
     * @return \Illuminate\View\View
     */
    public function showOrderDetails(Request $request, $order_reference)
    {
        $order = Order::where('order_reference', '=', $order_reference)->first();

        if (!$order) {
            abort(404);
        }

        $orderService = new OrderService($order->amount, $order->organiser_booking_fee, $order->event);
        $orderService->calculateFinalCosts();

        $data = [
            'order'        => $order,
            'orderService' => $orderService,
            'event'        => $order->event,
            'tickets'      => $order->event->tickets,
            'is_embedded'  => $this->is_embedded,
        ];

        if ($this->is_embedded) {
            return view('Public.ViewEvent.Embedded.EventPageViewOrder', $data);
        }

        return view('Public.ViewEvent.EventPageViewOrder', $data);
    }

    /**
     * Shows the tickets for an order - either HTML or PDF
     *
     * @param Request $request
     * @param $order_reference
     * @return \Illuminate\View\View
     */
    public function showOrderTickets(Request $request, $order_reference)
    {
        try {
            //code...
            $order = Order::where('order_reference', '=', $order_reference)->first();
            $order->is_print = true;
            $order->save();

            if (!$order) {
                abort(404);
            }
            
            $images = [];
            $imgs = $order->event->images;
            $image_path = public_path('user_content/event_images/sin_image_path.jpg');
            $organiser_full_logo_path = public_path('user_content/organiser_images/sin_logo.png');
            foreach ($imgs as $img) {
                if (file_exists(public_path($img->image_path))) {
                    $image_path = public_path($img->image_path);
                }
                $images[] = base64_encode(file_get_contents($image_path));
            }

            if (file_exists(public_path($order->event->organiser->full_logo_path))) {
                $organiser_full_logo_path = public_path($order->event->organiser->full_logo_path);
            }
            
            
            $bg = null;
            if(isset($images) && count($images) > 0){
                foreach($images as $img){
                    $bg = "data:image/png;base64,".$img;
                }  
            }

            $data = [ 
                'order'     => $order,
                'event'     => $order->event,
                'tickets'   => $order->event->tickets,
                'attendees' => $order->attendees,
                'css'       => file_get_contents(public_path('assets/stylesheet/ticket.css')),
                'image'     => base64_encode(file_get_contents($organiser_full_logo_path)),
                'images'    => $images,
                'bg'    => $bg,
            ];

            // dd($images,$organiser_full_logo_path, $data);
            // dd($data);
            if ($request->get('download') == '1') {
                if ($order->event->version_ticket == '1') {
                    if ($order->payment_gateway_id != null) {
                        return PDF::html('Public.ViewEvent.Partials.PDFTicketV3', $data, 'Tickets');
                    }else{
                        return PDF::html('Public.ViewEvent.Partials.PDFTicketV2', $data, 'Tickets'); 
                    } 
                }else{
                    return PDF::html('Public.ViewEvent.Partials.PDFTicket', $data, 'Tickets');
                }
            }
            return view('Public.ViewEvent.Partials.PDFTicket', $data);
            
        } catch (\Throwable $th) {
            //throw $th;
            dd($th->getMessage());
        }

    }

    public function ProccessPaymentOpenpay($request, $event, $transaction_data, $request_data)
    {
  
        $COUNTRY_CODE = 'MX';
        $activeAccountPaymentGateway = $event->account->getGateway($event->account->payment_gateway_id);
        
        if($activeAccountPaymentGateway->config['production_mode']=='0'){
            $OPENPAY_ID = $activeAccountPaymentGateway->config['apiId0'];
            $OPENPAY_SK = $activeAccountPaymentGateway->config['apiKeyPivate0'];            
            $OPENPAY_PRODUCTION_MODE = false;            
        }else{
            $OPENPAY_ID = $activeAccountPaymentGateway->config['apiId1'];
            $OPENPAY_SK = $activeAccountPaymentGateway->config['apiKeyPivate1'];            
            $OPENPAY_PRODUCTION_MODE = true;            
        }

        
        // dd($activeAccountPaymentGateway, $OPENPAY_ID, $COUNTRY_CODE);

        try {
            // create instance OpenPay
            
			$openpay = Openpay::getInstance('mgzijadvcenoigcrf4bo','sk_6e2174165e644e42b17d32409e44069c'); 

            $customer = array(
                'name' => strip_tags($request_data['order_first_name']),
                'last_name' => strip_tags($request_data['order_last_name']),
                'phone_number' => null,
                'email' => $request_data['order_email']
			);

            $chargeRequest = array(
                'method' => 'card',
                //'source_id' => $request->token_id,
                'amount' => $transaction_data['amount'],
                'currency' => 'MXN',
                'description' => $transaction_data['description'],
                'order_id' =>  $transaction_data['order_id'],
                //'device_session_id' => $request->deviceIdHiddenFieldName,
                'confirm' => false,
                "use_3d_secure"=>"true",
                'redirect_url' => url('/completed_order'),
                'customer' => $customer);
 
            $charge = $openpay->charges->create($chargeRequest);

            return [
                'data' => $charge->id,
                'error_code' => '200',
                'url' => $charge->payment_method->url,
                'description' => 'success'
            ];
			

        } catch (OpenpayApiTransactionError $e) {
            return [ 
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId(),
                    'id' => '1'
            ];
        } catch (OpenpayApiRequestError $e) {
            return [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId(),
                    'id' => '2'
                   ];
        } catch (OpenpayApiConnectionError $e) {
            return [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId(),
                    'id' => '3'
                   ];
        } catch (OpenpayApiAuthError $e) {
            return [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId(),
                    'id' => '4'
                   ];
        } catch (OpenpayApiError $e) {
            return [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId(),
                    'id' => '5'
                   ];
        } catch (Exception $e) {
            return [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId(),
                    'id' => '6'
                   ];
        }
    }
 
}

