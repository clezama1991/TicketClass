<?php
namespace App\Http\Controllers;


use App\Models\Attendee;
use App\Models\EventStats;
use App\Models\Order;
use App\Models\SeatTicket;
use Carbon\Carbon;
use Cookie;
use DB;
use Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Openpay;
use OpenpayApiError;
use OpenpayApiAuthError;
use OpenpayApiRequestError;
use OpenpayApiConnectionError;
use OpenpayApiTransactionError;
use Illuminate\Http\JsonResponse;


class EventCronController extends Controller
{
	
    public function __construct()
    {
      
        $this->middleware('guest');
    }
    /**
     * Show the 'Create Event' Modal
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function ValidarOrdenesRechazadas()
    {
		
        $orders = Order::where('is_completed_payment',false)->whereDate('created_at', Carbon::today())->where('payment_gateway_id',3)->where('created_at', '<=', Carbon::now()->subMinutes(env('MINUTES_MIN_VALIDATE_ORDER')))->get();
  
         
        $openpay = Openpay::getInstance('mgzijadvcenoigcrf4bo','sk_6e2174165e644e42b17d32409e44069c'); 

            DB::beginTransaction();

            try {
		
                foreach ($orders as $order) {
                    
                    $transaction_id = $order->transaction_id;
                                
                    $charge = $openpay->charges->get($transaction_id); 
                     
                    if($charge->status!='completed'){

                        $attendees = Attendee::where('order_id','=',$order->id)->get();

                        foreach ($attendees ?? [] as $attendee) {
                             
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
							$order->delete_by = 'cron';
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

                    }

                }
                DB::commit();
 
            } catch (Exception $e) {

                DB::rollBack();
				
		        return response()->json(['success' => 'error'], 400);
             }  

		return response()->json(['success' => 'success','count'=> count($orders)], 200);

    }

    public function DobleVerificacionOrdenesRechazadas()
    {
		
        $orders = Order::where('order_status_id',4)->where('is_cancelled',true)->where('is_cancelled_confirmed',false)->where('payment_gateway_id',3)->whereDate('created_at', Carbon::today())->where('created_at', '<=', Carbon::now()->subMinutes(60))->withTrashed()->get();
            DB::beginTransaction();

            try {
		
                $openpay = Openpay::getInstance('mgzijadvcenoigcrf4bo','sk_6e2174165e644e42b17d32409e44069c'); 

                foreach ($orders as $order) {
                    
                    $transaction_id = $order->transaction_id;
                                
                    $charge = $openpay->charges->get($transaction_id); 
                    
                    if($charge->status=='completed'){

                        $order->order_status_id = 1;
                        $order->is_cancelled = false;
                        $order->is_completed_payment = true;
                        $order->delete_by = null; 

                        
                        $attendees = Attendee::where('order_id','=',$order->id)->get();

                        foreach ($attendees ?? [] as $attendee) {
            

                            $attendee->ticket->increment('quantity_sold');
                            $attendee->ticket->increment('sales_volume', $attendee->ticket->price);
                            $attendee->ticket->increment('organiser_fees_volume', $attendee->ticket->price_service);
                            $attendee->ticket->event->increment('sales_volume', $attendee->ticket->price);
                            $attendee->ticket->event->increment('organiser_fees_volume', $attendee->ticket->price_service);
                            $attendee->is_cancelled = 0;
                            $attendee->save(); 

                            $eventStats = EventStats::where('event_id', $attendee->event_id)->where('date', $attendee->created_at->format('Y-m-d'))->first();
                            if($eventStats){
                                $eventStats->increment('tickets_sold',  3);
                                $eventStats->increment('sales_volume',  $attendee->ticket->price);
                                $eventStats->increment('organiser_fees_volume',  $attendee->ticket->price_service);
                            }

                            if(!is_null($order->seating)){
                                $array=explode(',',$order->seating); 
                                $seatings = SeatTicket::whereIn('id',$array)->get();
                                foreach ($seatings as $key => $seating) {
                                    $seating->is_available = 3;
                                    $seating->save();
                                }
                            } 
                        } 

                        $order->save();
                        $order->restore();

                    }else{
                     
                        if(!is_null($order->seating)){
                            $array=explode(',',$order->seating); 
                            $seatings = SeatTicket::whereIn('id',$array)->get();
                            foreach ($seatings as $key => $seating) {
                                $seating->is_available = 1;
                                $seating->save();
                            }
                        } 
                        
                        $order->is_cancelled_confirmed = true;
                        $order->save();
                    } 

                }

                DB::commit();
 
            } catch (Exception $e) {

                DB::rollBack();
				
		        return response()->json(['success' => 'error'], 400);
             }  

		return response()->json(['success' => 'success','count'=> count($orders)], 200);

    }

    public function DobleVerificacionAsientosBloquedos()
    {
		
            $orders = Order::where('order_status_id',1)->whereDate('created_at', Carbon::today())->where('created_at', '<=', Carbon::now()->subMinutes(60))->get();
   
            DB::beginTransaction();

            try {
		 
                foreach ($orders as $order) {
                     
                    if(!is_null($order->seating)){
                        $array=explode(',',$order->seating); 
                        $seatings = SeatTicket::whereIn('id',$array)->get();
                        foreach ($seatings as $key => $seating) {
                            $seating->is_available = 3;
                            $seating->save();
                        }
                    } 

                }

                DB::commit();
 
            } catch (Exception $e) {

                DB::rollBack();
				
		        return response()->json(['success' => 'error'], 400);
             }  

		return response()->json(['success' => 'success','count'=> count($orders)], 200);

    }
}
