<?php

namespace App\Mailers;

use App\Models\Order;
use App\Services\Order as OrderService;
use Log;
use Mail;

class OrderMailer
{
   
    public function sendOrderNotification(Order $order)
    {
        $orderService = new OrderService($order->amount, $order->organiser_booking_fee, $order->event);
        $orderService->calculateFinalCosts();

        $file_name = $order->order_reference;
        $file_path = public_path(config('attendize.event_pdf_tickets_path')) . '/' . $file_name . '.pdf';
        $file_exists = file_exists($file_path);
       
/*
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
 */
        $data = [
            'order' => $order,
            'orderService' => $orderService
        ];

        Mail::send('Emails.OrderNotification', $data, function ($message) use ($order) {
            $message->to($order->account->email);
            $message->subject(trans("Controllers.new_order_received", ["event"=> $order->event->title, "order" => $order->order_reference]));
        });

    }

    public function sendOrderTickets(Order $order)
    {
        $orderService = new OrderService($order->amount, $order->organiser_booking_fee, $order->event);
        $orderService->calculateFinalCosts();

        Log::info("Sending ticket in OrderMailer to : " . $order->email);

        
        try {
                  

            $file_name = $order->order_reference;
            $file_path = public_path(config('attendize.event_pdf_tickets_path')) . '/' . $file_name . '.pdf';
            $file_exists = file_exists($file_path);

            if (!file_exists($file_path)) {
                // Log::error("Cannot send actual ticket to : " . $order->email . " as ticket file does not exist on disk");
                // return;
            } 
            if($order->is_completed_payment){
                
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

                Mail::send('Emails.messageTicketsSalesCompleted', $data, function ($message) use ($order, $file_path,$file_exists) {
                    $message->to($order->email);
                    $message->subject(trans("Controllers.tickets_for_event", ["event" => $order->event->title]));
                    if($file_exists){
                        $message->attach($file_path);
                    }
                });
            
            
                if (count(Mail::failures()) > 0) {
                    
                    Log::info("Error ticket in OrderMailer to: " . $order->email);

                }else{
                    
                    Log::info("Success ticket in OrderMailer to: " . $order->email);

                }
            }
            
        } catch (\Exception $th) { 
            Log::info("Error Message ticket in OrderMailer: " . $th->getMessage()); 
        }




    }

}
