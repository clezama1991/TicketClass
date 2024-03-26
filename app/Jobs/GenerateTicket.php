<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use PDF;

class GenerateTicket extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $reference;
    protected $order_reference;
    protected $attendee_reference_index;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reference)
    {
        Log::info("Generating ticket: #" . $reference);
        $this->reference = $reference;
        $this->order_reference = explode("-", $reference)[0];
        if (strpos($reference, "-")) {
            $this->attendee_reference_index = explode("-", $reference)[1];
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $file_name = $this->reference;
        $file_path = public_path(config('attendize.event_pdf_tickets_path')) . '/' . $file_name;
        $file_with_ext = $file_path . ".pdf";

        if (file_exists($file_with_ext)) {
            Log::info("Use ticket from cache: " . $file_with_ext);
            return;
        }

        $order = Order::where('order_reference', $this->order_reference)->first();
        Log::info($order);
        $event = $order->event;
        $seat = $order->seat;

        $query = $order->attendees();
        if ($this->isAttendeeTicket()) {
            $query = $query->where('reference_index', '=', $this->attendee_reference_index);
        }
        $attendees = $query->get();

        $image_path = $event->organiser->full_logo_path;
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
            'event'     => $event,
            'seat'     => $seat,
            'attendees' => $attendees,
            'css'       => file_get_contents(public_path('assets/stylesheet/ticket.css')),
            'image'     => base64_encode(file_get_contents(public_path($image_path))),
            'images'    => $images,
            'bg'    => $bg,
        ];
        try {
            PDF::setOutputMode('F'); // force to file
            
            if ($order->payment_gateway_id != null) {
                PDF::html('Public.ViewEvent.Partials.PDFTicketV3', $data, $file_path);
            }else{
                PDF::html('Public.ViewEvent.Partials.PDFTicketV2', $data, $file_path); 
            } 

            Log::info("Ticket generated!");
        } catch(\Exception $e) {
            Log::error("Error generating ticket. This can be due to permissions on vendor/nitmedia/wkhtml2pdf/src/Nitmedia/Wkhtml2pdf/lib. This folder requires write and execute permissions for the web user");
            Log::error("Error message. " . $e->getMessage());
            Log::error("Error stack trace" . $e->getTraceAsString());
            $this->fail($e);
        }

    }

    private function isAttendeeTicket()
    {
        return ($this->attendee_reference_index != null);
    }
}
