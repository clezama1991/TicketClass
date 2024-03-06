<?php

namespace App\Http\Controllers;

use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\EventStats;
use App\Models\Order;

class EventDashboardController extends MyBaseController
{
    /**
     * Show the event dashboard
     *
     * @param bool|false $event_id
     * @return \Illuminate\View\View
     */
    public function showDashboard($event_id = false)
    {
        $event = Event::scope()->findOrFail($event_id);

        $num_days = 20;

        /*
         * This is a fairly hackish way to get the data for the dashboard charts. I'm sure someone
         * with better SQL skill could do it in one simple query.
         *
         * Filling in the missing days here seems to be fast(ish) (with 20 days history), but the work
         * should be done in the DB
         */
        $chartData = EventStats::where('event_id', '=', $event->id)
            ->where('date', '>', Carbon::now()->subDays($num_days)->format('Y-m-d'))
            ->get()
            ->toArray();

        $startDate = new DateTime("-$num_days days");
        $dateItter = new DatePeriod(
            $startDate, new DateInterval('P1D'), $num_days
        );

        /*
         * Iterate through each possible date, if no stats exist for this date set default values
         * Otherwise, if a date does exist use these values
         */
        $result = [];
        $resultFree = [];
        $tickets_data = [];
        foreach ($dateItter as $date) {
            $views = 0;
            $sales_volume = 0;
            $unique_views = 0;
            $tickets_sold = 0;
            $tickets_sold_free = 0;
            $organiser_fees_volume = 0;

            foreach ($chartData as $item) {
                if ($item['date'] == $date->format('Y-m-d')) {
                    $views = $item['views'];
                    $sales_volume = $item['sales_volume'];
                    $organiser_fees_volume = $item['organiser_fees_volume'];
                    $unique_views = $item['unique_views'];
                    $tickets_sold = $item['tickets_sold'];
                    
                    break;
                }
            }

            
            $Orders = Order::where('event_id', '=', $event->id)
            ->where('order_date', $date->format('Y-m-d'))
            ->where('payment_method','free')
            ->where('is_cancelled',false)
            ->get();

            foreach ($Orders as $key => $value) {
                $tickets_sold_free += $value->SumQuantyorderItems(); 

            }

            $resultFree[] = [
                'date'         => $date->format('Y-m-d'),
                'tickets_sold' => $tickets_sold_free,
            ];

            $result[] = [
                'date'         => $date->format('Y-m-d'),
                'views'        => $views,
                'unique_views' => $unique_views,
                'sales_volume' => $sales_volume + $organiser_fees_volume,
                'tickets_sold' => $tickets_sold - $tickets_sold_free,
            ];


        }

        $tickets_data_id = [];
        $tickets_data_totales = [];
        $tickets_data_agrupadas_id = [];
        $tickets_data_totales_agrupadas = [];
        foreach ($event->orders->where('is_cancelled',false) as $key => $value) {
            $plata = 0;
            $cortesia = 0;
            $online = 0;
            $sincargo = 0;
            $cargo = 0;
            $venta = 0;
            $total = 0;
            if( is_null($value->payment_gateway_id)){
                
                if($value->payment_method != 'free'){
                    $plata += $value->SumQuantyorderItems();
                }else{
                    $cortesia += $value->SumQuantyorderItems();
                }

             }else{
                $online = $value->SumQuantyorderItems();
            }

            foreach ($value->orderItems as $key => $orderItems) {
                $sincargo = $orderItems->quantity * $orderItems->unit_price;
                $cargo = $orderItems->quantity * $orderItems->unit_booking_fee;
                $venta = $sincargo + $cargo;
                $total = $online + $plata;
                if(!in_array($orderItems->title, $tickets_data_id)){
                    $tickets_data_id[] = $orderItems->title;                    
                    $tickets_data_totales[$orderItems->title] = [
                        'label' => $orderItems->title,
                        'group' => ($orderItems->ticket) ? $orderItems->ticket->group_zone : null,
                        'value' => [
                            'cortesia' => $cortesia,
                            'plata' => $plata,
                            'online' => $online,
                            'total' => $total,
                            'sincargo' => $sincargo,
                            'cargo' => $cargo,
                            'venta' => $venta
                        ]
                    ];                    
                }else{
                    $tickets_data_totales[$orderItems->title] = [
                        'label' => $orderItems->title,
                        'group' => ($orderItems->ticket) ? $orderItems->ticket->group_zone : null,
                        'value' => [
                            'cortesia' => $tickets_data_totales[$orderItems->title]['value']['cortesia'] + $cortesia,
                            'plata' => $tickets_data_totales[$orderItems->title]['value']['plata'] + $plata,
                            'online' => $tickets_data_totales[$orderItems->title]['value']['online'] + $online,
                            'total' => $tickets_data_totales[$orderItems->title]['value']['total'] + $total,
                            'sincargo' => $tickets_data_totales[$orderItems->title]['value']['sincargo'] + $sincargo,
                            'cargo' => $tickets_data_totales[$orderItems->title]['value']['cargo'] + $cargo,
                            'venta' => $tickets_data_totales[$orderItems->title]['value']['venta'] + $venta
                        ]
                    ];
                }    
            }
        }


        foreach ($tickets_data_totales as $key => $value) {

            $plata = $value['value']['plata'] ?? 0;
            $cortesia = $value['value']['cortesia'] ?? 0;
            $online = $value['value']['online'] ?? 0; 
            $sincargo = $value['value']['sincargo'] ?? 0; 
            $cargo = $value['value']['cargo'] ?? 0; 
            $venta = $value['value']['venta'] ?? 0; 

            if(!in_array($value['group'], $tickets_data_agrupadas_id)){
                $tickets_data_agrupadas_id[] = $value['group'];                    
                $tickets_data_totales_agrupadas[$value['group']] = [
                    'label' => $value['group'],
                    'value' => [
                        'plata' => $plata,
                        'cortesia' => $cortesia,
                        'online' => $online,
                        'sincargo' => $sincargo,
                        'cargo' => $cargo,
                        'venta' => $venta,
                        'total' => $online + $plata
                    ]
                ];                    
            }else{
                $plata = $tickets_data_totales_agrupadas[$value['group']]['value']['plata'] + $plata;
                $cortesia = $tickets_data_totales_agrupadas[$value['group']]['value']['cortesia'] + $cortesia;
                $online = $tickets_data_totales_agrupadas[$value['group']]['value']['online'] + $online;
                $sincargo = $tickets_data_totales_agrupadas[$value['group']]['value']['sincargo'] + $sincargo;
                $cargo = $tickets_data_totales_agrupadas[$value['group']]['value']['cargo'] + $cargo;
                $venta = $tickets_data_totales_agrupadas[$value['group']]['value']['venta'] + $venta;

                $tickets_data_totales_agrupadas[$value['group']] = [
                    'label' => $value['group'],
                    'value' => [
                        'plata' => $plata,
                        'cortesia' => $cortesia,
                        'online' => $online,
                        'sincargo' => $sincargo,
                        'cargo' => $cargo,
                        'venta' => $venta,
                        'total' => $online + $plata
                    ]
                ];
            }   
        }

         
 
        foreach ($event->tickets as $ticket) {
            $tickets_data[] = [
                'value' => $ticket->quantity_sold,
                'label' => $ticket->title,
            ];
        }

        $data = [
            'event'      => $event,
            'tickets_data_totales'      => $tickets_data_totales,
            'tickets_data_totales_agrupadas'      => $tickets_data_totales_agrupadas,
            'chartData'  => json_encode($result),
            'chartDataFree'  => json_encode($resultFree),
            'ticketData' => json_encode($tickets_data),
        ];

        return view('ManageEvent.Dashboard', $data);
    }

    /**
     * Redirect to event dashboard
     * @param  Integer|false $event_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToDashboard($event_id = false) {
        return redirect()->action(
            'EventDashboardController@showDashboard', ['event_id' => $event_id]
        );
    }
}
