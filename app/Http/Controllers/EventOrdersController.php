<?php

namespace App\Http\Controllers;


use App\Jobs\SendOrderTickets;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\EventStats;
use App\Models\SeatTicket;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Services\Order as OrderService;

use App\Models\User;
use DB;
use Excel;
use Illuminate\Http\Request;
use Log;
use Mail;
use Omnipay;
use Validator;

class EventOrdersController extends MyBaseController
{

    /**
     * Show event orders page
     *
     * @param Request $request
     * @param string $event_id
     * @return mixed
     */
    public function showOrders(Request $request, $event_id = '')
    {
        $allowed_sorts = ['first_name', 'email', 'order_reference', 'order_status_id', 'created_at'];

        $importes = $pedidos = $entradas = $completados = $otros = 0;

        $searchQuery = $request->get('q');
        $dateStartQuery = $request->get('date_start');
        $dateEndQuery = $request->get('date_end');
        $statusQuery = $request->get('status');
        $user_id = $request->get('user_id');
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'created_at');
        $sort_order = $request->get('sort_order') == 'asc' ? 'asc' : 'desc';
        $event = Event::scope()->find($event_id);

        
        /*
        $orders_all = Order::
            where('event_id',$event_id)
            ->where('is_active',true)
            ->where('payment_gateway_id','!=',null)
            ->get();
        
        foreach ($orders_all as $key => $orders) { 

            $quitar_paypal = 0;
        
            $amount = $orders->amount;

            $orders->amount_payment = $amount;

            foreach ($orders->attendees as $key => $attendees) { 
                $quitar_paypal += $attendees->ticket->price_paypal;
                $attendees->ticket->decrement('sales_volume', $attendees->ticket->price_paypal);
                $attendees->ticket->save();
            }
            
            $orders->amount = $amount - $quitar_paypal;

            $orders->event->decrement('sales_volume', $quitar_paypal);
            $orders->save();
        }


        dd($orders_all, $orders->attendees,  $orders->event);
*/




        $orders_all = Order::where('event_id',$event_id)->where('is_active',true);


        if ($searchQuery) {
            /*
            * Strip the hash from the start of the search term in case people search for
            * order references like '#EDGC67'
            */
            if ($searchQuery[0] === '#') {
                $searchQuery = str_replace('#', '', $searchQuery);
            }
            
            $orders_all->where(function ($query) use ($searchQuery) {
                $query->where('order_reference', 'like', $searchQuery . '%')
                        ->orWhere('first_name', 'like', $searchQuery . '%')
                        ->orWhere('email', 'like', $searchQuery . '%')
                        ->orWhere('last_name', 'like', $searchQuery . '%');
                });

            }

            if($dateStartQuery){
                $orders_all->whereDate('created_at', '>=', $dateStartQuery);
            }
            
            if($dateEndQuery){
                $orders_all->whereDate('created_at', '<=', $dateEndQuery);
            }
            
            if($statusQuery){
                $orders_all->where('order_status_id', $statusQuery);
            }
            

            $user_ids = $orders_all->pluck('user_id')->toArray();
            $users = User::whereIn('id',$user_ids)->get();

            if($user_id){
                if($user_id!='linea'){ 
                    $orders_all->where('user_id', $user_id);
                }else{ 
                    $orders_all->where('payment_gateway_id','!=',null);
                }
            }
            
            $orders = $orders_all->get();
            
            $importes = $orders->sum('amount') + $orders->sum('services_fee'); 
            
            // if($user_id=='linea'){ 
            //     $importess =  $importes * 1.06  - $importes;
            //     $importes = round($importes - $importess, 0);
            // }
            

            $importes_cash = $orders->where('payment_method','cash')->sum('amount') + $orders->where('payment_method','cash')->sum('services_fee');
            $importes_card = $orders->where('payment_method','card')->sum('amount') + $orders->where('payment_method','card')->sum('services_fee');
            
            $pedidos = $orders->count();

            foreach ($orders as $key => $value) {
                $entradas += $value->SumQuantyorderItems();
            }
            
            $completados = $orders->where('order_status_id',1)->count();
            
            $otros = $orders->where('order_status_id','!=',1)->count();
              
            
            $status = OrderStatus::get();

            $data = [
                'type_status'     => $status,
                'orders'     => $orders,
                'event'      => $event,
                'sort_by'    => $sort_by,
                'sort_order' => $sort_order,
                'q'          => $searchQuery,
                'date_start'          => $dateStartQuery,
                'date_end'          => $dateEndQuery,
                'status'          => $statusQuery,
                'user_id'          => $user_id,
                'users'          => $users,
                'importes'          => $importes,
                'importes_cash'          => $importes_cash,
                'importes_card'          => $importes_card,
                'pedidos'          => $pedidos,
                'entradas'          => $entradas,
                'completados'          => $completados,
                'otros'          => $otros,
            ];

        return view('ManageEvent.Orders', $data);
    }

    /**
     * Shows  'Manage Order' modal
     *
     * @param Request $request
     * @param $order_id
     * @return mixed
     */
    public function manageOrder(Request $request, $order_id)
    {
        $order = Order::scope()->find($order_id);

        $orderService = new OrderService($order->amount, $order->booking_fee+$order->services_fee, $order->event);
        $orderService->calculateFinalCosts();

        $data = [
            'order' => $order,
            'orderService' => $orderService
        ];

        return view('ManageEvent.Modals.ManageOrder', $data);
    }

    /**
     * Shows 'Edit Order' modal
     *
     * @param Request $request
     * @param $order_id
     * @return mixed
     */
    public function showEditOrder(Request $request, $order_id)
    {
        $order = Order::scope()->find($order_id);

        $data = [
            'order'     => $order,
            'event'     => $order->event(),
            'attendees' => $order->attendees()->withoutCancelled()->get(),
            'modal_id'  => $request->get('modal_id'),
        ];

        return view('ManageEvent.Modals.EditOrder', $data);
    }

    /**
     * Shows 'Cancel Order' modal
     *
     * @param Request $request
     * @param $order_id
     * @return mixed
     */
    public function showCancelOrder(Request $request, $order_id)
    {
        $order = Order::scope()->find($order_id);

        $data = [
            'order'     => $order,
            'event'     => $order->event(),
            'attendees' => $order->attendees()->withoutCancelled()->get(),
            'modal_id'  => $request->get('modal_id'),
        ];

        return view('ManageEvent.Modals.CancelOrder', $data);
    }

    /**
     * Resend an entire order
     *
     * @param $order_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendOrder($order_id)
    {
        $order = Order::scope()->find($order_id);

        $this->dispatch(new SendOrderTickets($order));

        return response()->json([
            'status'      => 'success',
            'redirectUrl' => '',
        ]);
    }

    /**
     * Cancels an order
     *
     * @param Request $request
     * @param $order_id
     * @return mixed
     */
    public function postEditOrder(Request $request, $order_id)
    {
        $rules = [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email'],
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

        $order = Order::scope()->findOrFail($order_id);

        $order->first_name = $request->get('first_name');
        $order->last_name = $request->get('last_name');
        $order->email = $request->get('email');

        $order->update();


        \Session::flash('message', trans("Controllers.the_order_has_been_updated"));

        return response()->json([
            'status'      => 'success',
            'redirectUrl' => '',
        ]);
    }


    /**
     * Cancels an order
     *
     * @param Request $request
     * @param $order_id
     * @return mixed
     */
    public function postCancelOrder(Request $request, $order_id)
    {
        try {

            DB::beginTransaction();
 
            $rules = [
                'refund_amount' => ['numeric'],
            ];
            $messages = [
                'refund_amount.integer' => trans("Controllers.refund_only_numbers_error"),
            ];

            if (env('APP_ENV')=='production') {
                $validator = Validator::make($request->all(), $rules, $messages);

                if ($validator->fails()) {
                    return response()->json([
                        'status'   => 'error',
                        'messages' => $validator->messages()->toArray(),
                    ]);
                }
            }

            $order = Order::scope()->findOrFail($order_id);

            $refund_order = ($request->get('refund_order') === 'on') ? true : false;
            $refund_type = $request->get('refund_type');
            $refund_amount = round(floatval($request->get('refund_amount')), 2);
            $attendees = $request->get('attendees');
            $error_message = false;

            if ($refund_order && $order->payment_gateway->can_refund) {
                if (!$order->transaction_id) {
                    $error_message = trans("Controllers.order_cant_be_refunded");
                }

                if ($order->is_refunded) {
                    $error_message = trans("Controllers.order_already_refunded");
                } elseif ($order->organiser_amount == 0) {
                    $error_message = trans("Controllers.nothing_to_refund");
                } elseif ($refund_type !== 'full' && $refund_amount > round(($order->organiser_amount - $order->amount_refunded),
                        2)
                ) {
                    $error_message = trans("Controllers.maximum_refund_amount", ["money"=>(money($order->organiser_amount - $order->amount_refunded,
                            $order->event->currency))]);
                }
                if (!$error_message) {
                    try {
                        $gateway = Omnipay::create($order->payment_gateway->name);

                        $gateway->initialize($order->account->getGateway($order->payment_gateway->id)->config);

                        if ($refund_type === 'full') { /* Full refund */
                            $refund_amount = $order->organiser_amount - $order->amount_refunded;
                        }

                        $request = $gateway->refund([
                            'transactionReference' => $order->transaction_id,
                            'amount'               => $refund_amount,
                            'refundApplicationFee' => floatval($order->booking_fee) > 0 ? true : false,
                        ]);

                        $response = $request->send();

                        if ($response->isSuccessful()) {
                            /* Update the event sales volume*/
                            $order->event->decrement('sales_volume', $refund_amount);
                            $order->amount_refunded = round(($order->amount_refunded + $refund_amount), 2);

                            if (($order->organiser_amount - $order->amount_refunded) == 0) {
                                $order->is_refunded = 1;
                                $order->order_status_id = config('attendize.order_refunded');
                            } else {
                                $order->is_partially_refunded = 1;
                                $order->order_status_id = config('attendize.order_partially_refunded');
                            }
                        } else {
                            $error_message = $response->getMessage();
                        }

                        $order->save();
                    } catch (\Exeption $e) {
                        Log::error($e);
                        $error_message = trans("Controllers.refund_exception");
                    }
                }

                if ($error_message) {
                    return response()->json([
                        'status'  => 'success',
                        'message' => $error_message,
                    ]);
                }
            }

            /*
            * Cancel the attendees
            */
            if ($attendees) {
                foreach ($attendees as $attendee_id) {
                    $attendee = Attendee::scope()->where('id', '=', $attendee_id)->first();
                    $attendee->ticket->decrement('quantity_sold');
                    $attendee->ticket->decrement('sales_volume', $attendee->ticket->price);
                    $order->event->decrement('sales_volume', $attendee->ticket->price);
                    $order->decrement('amount', $attendee->ticket->price);
                    $attendee->is_cancelled = 1;
                    $attendee->save();

                    $eventStats = EventStats::where('event_id', $attendee->event_id)->where('date', $attendee->created_at->format('Y-m-d'))->first();
                    if($eventStats){
                        $eventStats->decrement('tickets_sold',  1);
                        $eventStats->decrement('sales_volume',  $attendee->ticket->price);
                    }

                    $seating = explode(",",$order->seating);
                    $new = \array_diff($seating, [$attendee->seat]);
                    $new_arr = implode(",",$new);

                    $order->seating = $new_arr;
                    $order->save();

                    SeatTicket::whereId($attendee->seat)->update(['is_available'=>1]);
            
                }
            }
            if(!$refund_amount && !$attendees)
                $msg = trans("Controllers.nothing_to_do");
            else {
                if($attendees && $refund_order)
                    $msg = trans("Controllers.successfully_refunded_and_cancelled");
                else if($refund_order)
                    $msg = trans("Controllers.successfully_refunded_order");
                else if($attendees)
                    $msg = trans("Controllers.successfully_cancelled_attendees");
            }
 
            if(count($order->attendees()->withoutCancelled()->get())==0){
                $order->order_status_id = 4;
                $order->is_cancelled = true; 
                $order->save();
            }  
 
            \Session::flash('message', $msg);

            DB::commit();

        } catch (\Throwable $th) {
            
            DB::rollback();

            return response()->json([
                'status'      => 'error',
                'redirectUrl' => '',
            ]);
        }

        return response()->json([
            'status'      => 'success',
            'redirectUrl' => '',
        ]);
    }

    /**
     * Exports order to popular file types
     *
     * @param $event_id
     * @param string $export_as Accepted: xls, xlsx, csv, pdf, html
     */
    public function showExportOrders($event_id, $export_as = 'xls')
    {
        $event = Event::scope()->findOrFail($event_id);

        Excel::create('orders-as-of-' . date('d-m-Y-g.i.a'), function ($excel) use ($event) {

            $excel->setTitle('Orders For Event: ' . $event->title);

            // Chain the setters
            $excel->setCreator(config('attendize.app_name'))
                ->setCompany(config('attendize.app_name'));

            $excel->sheet('orders_sheet_1', function ($sheet) use ($event) {

                $yes = strtoupper(trans("basic.yes"));
                $no = strtoupper(trans("basic.no"));
                $orderRows = DB::table('orders')
                    ->where('orders.event_id', '=', $event->id)
                    ->where('orders.event_id', '=', $event->id)
                    ->select([
                        'orders.first_name',
                        'orders.last_name',
                        'orders.email',
                        'orders.order_reference',
                        'orders.amount',
                        \DB::raw("(CASE WHEN orders.is_refunded = 1 THEN '$yes' ELSE '$no' END) AS `orders.is_refunded`"),
                        \DB::raw("(CASE WHEN orders.is_partially_refunded = 1 THEN '$yes' ELSE '$no' END) AS `orders.is_partially_refunded`"),
                        'orders.amount_refunded',
                        'orders.created_at',
                    ])->get();

                $exportedOrders = $orderRows->toArray();

                array_walk($exportedOrders, function(&$value) {
                    $value = (array)$value;
                });

                $sheet->fromArray($exportedOrders);

                // Add headings to first row
                $sheet->row(1, [
                    trans("Attendee.first_name"),
                    trans("Attendee.last_name"),
                    trans("Attendee.email"),
                    trans("Order.order_ref"),
                    trans("Order.amount"),
                    trans("Order.fully_refunded"),
                    trans("Order.partially_refunded"),
                    trans("Order.amount_refunded"),
                    trans("Order.order_date"),
                ]);

                // Set gray background on first row
                $sheet->row(1, function ($row) {
                    $row->setBackground('#f5f5f5');
                });
            });
        })->export($export_as);
    }

    /**
     * shows 'Message Order Creator' modal
     *
     * @param Request $request
     * @param $order_id
     * @return mixed
     */
    public function showMessageOrder(Request $request, $order_id)
    {
        $order = Order::scope()->findOrFail($order_id);

        $data = [
            'order' => $order,
            'event' => $order->event,
        ];

        return view('ManageEvent.Modals.MessageOrder', $data);
    }

    /**
     * Sends message to order creator
     *
     * @param Request $request
     * @param $order_id
     * @return mixed
     */
    public function postMessageOrder(Request $request, $order_id)
    {
        $rules = [
            'subject' => 'required|max:250',
            'message' => 'required|max:5000',
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

        $order = Order::scope()->findOrFail($order_id);

        $data = [
            'order'           => $order,
            'message_content' => $request->get('message'),
            'subject'         => $request->get('subject'),
            'event'           => $order->event,
            'email_logo'      => $order->event->organiser->full_logo_path,
        ];

        Mail::send('Emails.messageReceived', $data, function ($message) use ($order, $data) {
            $message->to($order->email, $order->full_name)
                ->from(config('attendize.outgoing_email_noreply'), $order->event->organiser->name)
                ->replyTo($order->event->organiser->email, $order->event->organiser->name)
                ->subject($data['subject']);
        });

        /* Send a copy to the Organiser with a different subject */
        if ($request->get('send_copy') == '1') {
            Mail::send('Emails.messageReceived', $data, function ($message) use ($order, $data) {
                $message->to($order->event->organiser->email)
                    ->from(config('attendize.outgoing_email_noreply'), $order->event->organiser->name)
                    ->replyTo($order->event->organiser->email, $order->event->organiser->name)
                    ->subject($data['subject'] . trans("Email.organiser_copy"));
            });
        }

        return response()->json([
            'status'  => 'success',
            'message' => trans("Controllers.message_successfully_sent"),
        ]);
    }

    /**
     * Mark an order as payment received
     *
     * @param Request $request
     * @param $order_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postMarkPaymentReceived(Request $request, $order_id)
    {
        $order = Order::scope()->findOrFail($order_id);

        $order->is_payment_received = 1;
        $order->order_status_id = 1;

        $order->save();

        session()->flash('message', trans("Controllers.order_payment_status_successfully_updated"));

        return response()->json([
            'status' => 'success',
        ]);
    }

    /**
     * Shows 'Reactivate Order' modal
     *
     * @param Request $request
     * @param $order_id
     * @return mixed
     */
    public function showReactivateOrder(Request $request, $order_id)
    {
        $order = Order::scope()->find($order_id);

        $data = [
            'order'     => $order,
            'event'     => $order->event(),
            'attendees' => $order->attendees()->withoutCancelled()->get(),
            'modal_id'  => $request->get('modal_id'),
        ];

        return view('ManageEvent.Modals.ReactivateOrder', $data);
    }

    
    /**
     * Reactivate an order
     *
     * @param Request $request
     * @param $order_id
     * @return mixed
     */
    public function postReactivateOrder(Request $request, $order_id)
    {
        try {

            DB::beginTransaction();
 
                $order = Order::scope()->findOrFail($order_id);
 
                foreach ($order->attendees as $attendee) {
                    //$attendee->ticket->increment('quantity_sold');
                    //$attendee->ticket->increment('sales_volume', $attendee->ticket->price);
                    //$order->event->increment('sales_volume', $attendee->ticket->price);
                    //$order->increment('amount', $attendee->ticket->price);
                    $attendee->is_cancelled = 0;
                    $attendee->save();

                    //$eventStats = EventStats::where('event_id', $attendee->event_id)->where('date', $attendee->created_at->format('Y-m-d'))->first();
                    //if($eventStats){
                    //    $eventStats->increment('tickets_sold',  1);
                    //    $eventStats->increment('sales_volume',  $attendee->ticket->price);
                    //}

                    //$order->save();

                }
            
 
                $msg = trans("Controllers.successfully_reactivate_order");
             
                //$order->order_status_id = 1;
                //$order->is_cancelled = false; 
                //$order->save(); 
 
            \Session::flash('message', $msg);

            DB::commit();

        } catch (\Throwable $th) {
            
            DB::rollback();

            return response()->json([
                'status'      => 'error',
                'redirectUrl' => '',
            ]);
        }

        return response()->json([
            'status'      => 'success',
            'redirectUrl' => '',
        ]);
    }
}
