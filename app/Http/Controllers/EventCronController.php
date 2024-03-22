<?php
namespace App\Http\Controllers;


use App\Models\Attendee;
use App\Models\EventStats;
use App\Models\Order;
use Carbon\Carbon;
use Cookie;
use DB;
use Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


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
		
        $orders = Order::where('is_completed_payment',false)->whereDate('created_at', Carbon::today())->where('created_at', '<=', Carbon::now()->subMinutes(env('MINUTES_MIN_VALIDATE_ORDER')))->get();
  
         
            DB::beginTransaction();

            try {
		
                foreach ($orders as $order) {
                    

                        $attendees = Attendee::where('order_id','=',$order->id)->get();

                        foreach ($attendees ?? [] as $value) {
                            
                            $attendee_id = $value->id;
                            $attendee = Attendee::findOrFail($attendee_id);
                            $error_message = false;
                

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
                
                            $order->order_status_id = 4;
                            $order->is_cancelled = true;
                            $order->save();
                            $order->delete();
        
                        }
                        

                }
                DB::commit();
 
            } catch (Exception $e) {

                DB::rollBack();
				
		        return response()->json(['success' => 'error'], 400);
             }  

		return response()->json(['success' => 'success'], 200);

    }
}
