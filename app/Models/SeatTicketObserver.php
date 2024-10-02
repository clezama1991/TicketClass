<?php

namespace App\Models;

use App\Models\SeatTicket;

class SeatTicketObserver {
     
     public function updated(SeatTicket $seat_ticket)
     {
          
          // $status_set = ['1'=>'free', '0'=>'booked', '3'=>'sold'];
          
          // $seatsioClient = new \Seatsio\SeatsioClient(env('WORKSPACE_SECRET_KEY')); 

          // $event = 'event'.$seat_ticket->getEventId();

          // $uuii = [$seat_ticket->uuii];
 
          // $seatsioClient->events->changeObjectStatus($event, $uuii, $status_set[$seat_ticket->is_available]);

 
     }
 

}