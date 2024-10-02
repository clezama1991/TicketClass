<?php

use App\Models\Ticket;

if (!function_exists('money')) {
    /**
     * Format a given amount to the given currency
     *
     * @param $amount
     * @param \App\Models\Currency $currency
     * @return string
     */
    function money($amount, \App\Models\Currency $currency)
    {
        return $currency->symbol_left . number_format($amount, $currency->decimal_place, $currency->decimal_point,
            $currency->thousand_point) . $currency->symbol_right;
    }
}
if (!function_exists('format_money')) {
    /**
     * Format a given amount to the given currency
     *
     * @param $amount
     * @param \App\Models\Currency $currency
     * @return string
     */
    function format_money($amount,  $currency)
    {
        $currency = \App\Models\Currency::where('id',$currency)->first();
        return $currency->symbol_left . number_format($amount, $currency->decimal_place, $currency->decimal_point,
            $currency->thousand_point) . $currency->symbol_right;
    }
}

if (!function_exists('payment_methods')) {

    function payment_methods()
    {
        $methods = [
            [
                'id' => 'cash',
                'name' => 'Efectivo',
            ],
            [
                'id' => 'card',
                'name' => 'Tarjeta',
            ],
            [
                'id' => 'free',
                'name' => 'Cortesia',
            ]
        ];
        return $methods;
    }
}


if (!function_exists('seatsio')) {

    function seatsio($action, $event_id = null, $map_id = null)
    {

        $seatsioClient = new \Seatsio\SeatsioClient(env('WORKSPACE_SECRET_KEY')); 

        if($action=='maps'){
            $charts = $seatsioClient->charts->listAll();
            $charts_a  = [];
            foreach($charts as $chart) { 
                $charts_a[$chart->key] = $chart->name;
            } 
            return $charts_a;
        }

        if($action=='create_event'){ 

            $seatsioClient->events->create($map_id, 'event'.$event_id); 


            $tickets_charts = $seatsioClient->charts->retrievePublishedVersion($map_id);

            // $tickets_categories = $tickets_charts->categories->list;


            $seats = $tickets_charts->subChart->tables;
            $rows = $tickets_charts->subChart->rows;
            $generalAdmissionAreas = $tickets_charts->subChart->generalAdmissionAreas;

            // dd($tickets_charts->subChart);
            $seats_all = [];

 
            $seats_list = [];
 
            foreach ($seats as $key_seats => $value) {
                $rows_sectionLabel =  $value->sectionLabel;
                $rows_label =  $value->label;
                foreach ($value->seats as $key_seat => $seat) {
                    $seat_label = $seat->label;   
                    $seats_list[$seat->categoryLabel]['seat'][] = $seat;
                    $seats_list[$seat->categoryLabel]['seats'][] = $rows_sectionLabel.$rows_label.'-'.$seat_label;
                    $seats_list[$seat->categoryLabel]['select_seat'] = true; 
                    $seats_list[$seat->categoryLabel]['capacity'] = 0; 
                } 
            } 

            foreach ($rows as $key_rows => $value) { 
                $rows_sectionLabel =  $value->sectionLabel;
                $rows_label =  $value->label;
                foreach ($value->seats as $key_seat => $seat) {  
                    $seat_label = $seat->label;   
                    $seats_list[$seat->categoryLabel]['seat'][] = $seat;
                    $seats_list[$seat->categoryLabel]['seats'][] = $rows_sectionLabel.$rows_label.'-'.$seat_label;
                    $seats_list[$seat->categoryLabel]['select_seat'] = true; 
                    $seats_list[$seat->categoryLabel]['capacity'] = 0; 
                } 
            } 

            // foreach ($generalAdmissionAreas as $key_generalAdmissionAreas => $seat) {  
            //     $rows_label =  $value->label;
            //     $seats_list[$seat->categoryLabel]['seat'][] = $seat;
            //     $seats_list[$seat->categoryLabel]['seats'][] = $seat->uuid;
            //     $seats_list[$seat->categoryLabel]['select_seat'] = false; 
            //     if(isset($seats_list[$seat->categoryLabel]['capacity'])){
            //         $seats_list[$seat->categoryLabel]['capacity'] = $seats_list[$seat->categoryLabel]['capacity'] + $seat->capacity;
            //     }else{ 
            //         $seats_list[$seat->categoryLabel]['capacity'] = $seat->capacity; 
            //     }
            // }
   
            foreach ($seats_list as $key => $value) { 
                if($value['select_seat']){ 
                    $seats_list[$key]['capacity'] = count($value['seats']); 
                }else{  
                    $seats_list[$key]['capacity'] = $value['capacity']; 
                }
            }  

            // dd($seats_list);
             
            $event = \App\Models\Event::findOrFail($event_id);

            foreach ($seats_list as $key => $value) {    
                $ticket = \App\Models\Ticket::createNew();
                $ticket->event_id = $event_id;
                $ticket->title = $key;
                $ticket->quantity_available = $value['capacity'];
                // $ticket->start_sale_date = $event->start_date;
                // $ticket->end_sale_date = $event->end_date;
                $ticket->seat_zone = 1;
                $ticket->price_neto = 0;
                $ticket->price_service = 0;
                $ticket->price_paypal = 0;
                $ticket->price = 0;
                $ticket->min_per_person = 1;
                $ticket->max_per_person = $value['capacity'];
                $ticket->description = $key;
                $ticket->is_hidden = 0;
                $ticket->group_zone = 'General';

                $f = $value['capacity'];
                $filas = explode("-", $f); 
                $ticket->quantity_row =  $value['select_seat'] ? json_encode($filas) : null; 
                
                $f =1;
                $filas = explode("-", $f); 
                $ticket->seat_white =  $value['select_seat'] ? json_encode($filas) : null; 
 
                $ticket->select_seat = $value['select_seat'];
                $ticket->save();

                if ($value['select_seat']) {
                    
                    $asiento = 1; 
                    $filas = 1;

                    $LastSeatsRow = $ticket->event->seat_row(); 

                    for ($i = 1; $i <= $filas; $i++) { 
                        foreach ($value['seats'] as $key => $seats) {    
                            $seat = new \App\Models\SeatTicket();
                            $seat->row = ($i+$LastSeatsRow);
                            $seat->column = $asiento;
                            $seat->ticket_id = $ticket->id;
                            $seat->is_available = 1;
                            $seat->uuii = $seats;
                            $seat->save();
                            $asiento += 1;
                        }
                    }
                    
                }

            }
  
        }
 
    }
}


