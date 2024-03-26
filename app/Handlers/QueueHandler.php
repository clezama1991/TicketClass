<?php

namespace App\Handlers;

use App\Mailers\OrderMailer;
use Attendee;
use Order;

//use PDF;

class QueueHandler
{
    protected $orderMailer;

    public function __construct(OrderMailer $orderMailer)
    {
        $this->orderMailer = $orderMailer;
    }

    public function handleOrder($job, $data)
    {
        echo "Starting Job {$job->getJobId()}\n";

        $order = Order::findOrfail($data['order_id']);

        /*
         * Steps :
         *   1 Notify event organiser
         *   2 Order Confirmation email to buyer
         *   3 Generate /  Send Tickets
         */
         
        $images = [];
        $imgs = $order->event->images;
        foreach ($imgs as $img) {
            $images[] = base64_encode(file_get_contents(public_path($img->image_path)));
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
            'image'     => base64_encode(file_get_contents(public_path($order->event->organiser->full_logo_path))),
            'bg' => $bg,
        ];

        $pdf_file = storage_path() . '/' . $order->order_reference;
        exit($pdf_file);

        PDF::setOutputMode('F'); // force to file
           
        if ($order->payment_gateway_id != null) {
            PDF::html('Public.ViewEvent.Partials.PDFTicketV3', $data, $pdf_file);
        }else{
            PDF::html('Public.ViewEvent.Partials.PDFTicketV2', $data, $pdf_file); 
        } 

        //1
        $this->orderMailer->sendOrderNotification($order);
        //2
        $this->orderMailer->sendOrderConfirmation($order);
        //3

        $this->orderMailer->sendTickets($order);

        $job->delete();
    }

    public function messageAttendees($job, $data)
    {
        echo "Starting Job {$job->getJobId()}\n";

        $message_object = Message::find($data['message_id']);
        $event = $message_object->event;

        $attendees = ($message_object->recipients == 0) ? $event->attendees : Attendee::where('ticket_id', '=',
            $message_object->recipients)->where('account_id', '=', $message_object->account_id)->get();

        $toFields = [];
        foreach ($attendees as $attendee) {
            $toFields[$attendee->email] = $attendee->full_name;
        }

        $data = [
            'event'           => $event,
            'message_content' => $message_object->message,
            'subject'         => $message_object->subject,
        ];

        Mail::send('Emails.messageReceived', $data, function ($message) use ($toFields, $event, $message_object) {
            $message->to($toFields)
                ->from(config('attendize.outgoing_email_noreply'), $event->organiser->name)
                ->replyTo($event->organiser->email, $event->organiser->name)
                ->subject($message_object->subject);
        });

        $message_object->is_sent = 1;
        $message_object->save();
        //$message->sent

        $job->delete();
    }
}
