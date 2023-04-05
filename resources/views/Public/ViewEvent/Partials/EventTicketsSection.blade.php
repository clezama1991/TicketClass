<section id="tickets" class="container" style="width: 80% !important;">
    <div class="row">
        <h1 class='section_head'>
            @lang("Public_ViewEvent.tickets")
        </h1>
    </div>

    @if($event->end_date->isPast())
    <div class="alert alert-boring">
        @lang("Public_ViewEvent.event_already", ['started' => trans('Public_ViewEvent.event_already_ended')])
    </div>
    @else

    @if($tickets->count() > 0)

    {!! Form::open(['url' => route('postValidateTickets', ['event_id' => $event->id]), 'class' => 'ajax']) !!}
    <input type="hidden" name="asientos_marcados" id="asientos_marcados">
    <input type="hidden" name="asientos" id="asientos_id">
    <section class="section-page-content" id="map-event">
        <div class=" ">
            <div class="row">
                <div id="" class="col-md-7">
                    <img id="hall-seat-plan" src="{{ asset($event->image_map()) }}" alt="stage" usemap="#map" />
                    <map name="map" class="seatmap">
                        <!-- SEAT A --> 
                        <?php                            
                            $zonas_disponibles = $tickets->where('is_hidden', false)->pluck('seat_zone')->toArray();                                
                        ?>                      
                        @foreach($event->sections_map() as $key => $map_section)
                        <area data-seat="{{ in_array($map_section->combine,$zonas_disponibles) ? 'sold' : "
                            x".$map_section->combine }}"
                            data-seatzone="{{ $map_section->combine}}" class="selected_zone" alt="{{ $map_section->combine }}"
                            title="{{ $map_section->combine }}" href="#" shape="{{$map_section->shape}}" coords="{{$map_section->coords}}"
                            data-toggle="{{ in_array($map_section->combine,$zonas_disponibles) ? 'modal' : 'sold' }}" data-target="#myModal"
                            >
                        @endforeach
                    </map>

                    <div class="seat-label">
                        <ul>
                            <li><img src="{{ asset('assets/images/select_seat/available.png') }}" alt="available">
                                Available</li>
                            <li><img src="{{ asset('assets/images/select_seat/sold.png') }}" alt="sold"> Sold Out</li>
                            <li><img src="{{ asset('assets/images/select_seat/selected.png') }}" alt="selected">
                                Selected</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-4">

                    <table class="table table-hover seat-row">
                        <thead>
                            <tr>
                                <th class="">Sección</th>
                                <th class="">Asientos</th>
                                <th class="text-right">Precio</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($tickets->where('is_hidden', false) as $ticket)
                            <tr style="cursor: pointer" data-seat="{{$ticket->seat_zone}}" class="selected_zone"
                                data-toggle="modal" data-target="#myModal">
                                <td class="">
                                    <span style="text-transform: uppercase;">{{$ticket->seat_zone}}</span>
                                    <br>
                                    <span class="tax-amount text-muted text-">
                                        {{$ticket->title}}</span>
                                </td>
                                <td class=""><span
                                        class="ver_asientos_marcados_seccion_comprar_zona_{{ $ticket->seat_zone }}">0</span>
                                </td>
                                <td class="text-right">
                                    <span 
                                        title='{{money(($ticket->price+$ticket->price_paypal), $event->currency)}} 
                                        @lang("Public_ViewEvent.ticket_price") + {{money($ticket->total_booking_fee_online, $event->currency)}} 
                                        @lang("Public_ViewEvent.booking_fees")'>
                                        {{money($ticket->total_price_online, $event->currency)}} 
                                    </span><br>
                                    <span class="tax-amount text-muted text-smaller">
                                        100{{ ($event->organiser->tax_name &&
                                        $event->organiser->tax_value) ?
                                        '(+'.money(($ticket->total_price_online*($event->organiser->tax_value)/100),
                                        $event->currency).' '.$event->organiser->tax_name.')' : '' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </section>
    <div class="row">

        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Comprar Asientos</h4>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-12">

                                @foreach($tickets->where('is_hidden', false) as $ticket)
                                {{-- {{$ticket}} --}}

                                {!! Form::hidden('tickets[]', $ticket->id) !!}
                                <meta property="availability" content="http://schema.org/InStock">
                                @if($ticket->select_seat==1)

                                <input type="hidden" name="ticket_{{$ticket->id}}" value="0">
                                <input type="hidden" name="asientos_{{$ticket->id}}">
                                @endif
                                <div class="zone-events" id="{{'zone-event-'.$ticket->seat_zone}}">
                                    <div class="row" style="padding-left: 2rem;
                                    padding-right: 2rem;">
                                        <div class="col-md-12">
                                            <table class="table seat-row">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Sección</th>
                                                        <th class="text-center">Precio</th>
                                                        {{-- <th class="text-center">Asientos Disp / Ocup</th> --}}
                                                        <th class="text-center">Asientos Marcados</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">{{$ticket->title}}</td>
                                                        <td class="text-center">{{$ticket->price}}</td>
                                                        {{-- <td class="text-center">{{$ticket->max_per_person}} /
                                                            {{$ticket->quantity_available}}</td> --}}
                                                        <td class="text-center"><span
                                                                class="ver_asientos_marcados_seccion_comprar_zona_{{ $ticket->seat_zone }}">0</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    @php
                                    $termina = 1;
                                    $ticket_seats = $ticket->seats->groupBy('row');

                                    @endphp
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="row m-5">
                                                @if($ticket->select_seat==1)
                                                <div class="col-md-12"
                                                    style="display: flex;justify-content: center; margin-bottom: 15px; ">
                                                    <img src="{{ asset('assets/images/select_seat/stage.jpg') }}"
                                                        alt="selected" alt="">

                                                    <br>
                                                </div>
                                                @else
                                                <div class="col-md-12" style="padding: 50px">
                                                    @if($ticket->is_paused)

                                                    <span class="text-danger">
                                                        @lang("Public_ViewEvent.currently_not_on_sale")
                                                    </span>

                                                    @else

                                                    @if($ticket->sale_status ===
                                                    config('attendize.ticket_status_sold_out'))
                                                    <span class="text-danger" property="availability"
                                                        content="http://schema.org/SoldOut">
                                                        @lang("Public_ViewEvent.sold_out")
                                                    </span>
                                                    @elseif($ticket->sale_status ===
                                                    config('attendize.ticket_status_before_sale_date'))
                                                    <span class="text-danger">
                                                        @lang("Public_ViewEvent.sales_have_not_started")
                                                    </span>
                                                    @elseif($ticket->sale_status ===
                                                    config('attendize.ticket_status_after_sale_date'))
                                                    <span class="text-danger">
                                                        @lang("Public_ViewEvent.sales_have_ended")
                                                    </span>
                                                    @else
                                                    <label for="">Seleccione cantidad de asientos</label>
                                                    <select name="ticket_{{$ticket->id}}" data-id="{{$ticket->id}}"
                                                        data-seatzone="{{ $ticket->seat_zone }}"
                                                        class="form-control select-nro-seat" style="text-align: center">
                                                        @if ($tickets->count() > 1)
                                                        <option value="0">0</option>
                                                        @endif
                                                        @for($i=$ticket->min_per_person; $i<=$ticket->max_per_person;
                                                            $i++)
                                                            <option value="{{$i}}">{{$i}}</option>
                                                            @endfor
                                                    </select>
                                                    @endif

                                                    @endif

                                                </div>
                                                @endif

                                                @foreach ($ticket_seats as $key_file => $seats)

                                                <div class="col-md-12">

                                                    <div class="seats__grid">
                                                        <ul class="nostyle seats__row "
                                                            style="list-style:none;padding:0;margin:0;display: flex;justify-content: center;">
                                                            <li class="seat"
                                                                style="font-weight: bolder;width: 30px;position: absolute;left: 20px;">
                                                                F{{$key_file}}</li>

                                                            @foreach ($seats as $t => $seat)
                                                            @if ($key_file == $seat->row)

                                                            @if ($seat->is_available != 2)


                                                            <li class="seat seatSelect {{ $seat->is_available == 3 ? "
                                                                btns-danger": "btns-info" }}"
                                                                style="width: 25px; padding-left: 1px;"
                                                                id="{{ $seat->id }}" data-asiento="{{ $seat->seat() }}"
                                                                data-ticket="{{ $ticket->id }}"
                                                                data-seatzone="{{ $ticket->seat_zone }}" title="{{ "
                                                                Fila: ".$seat->row." - Asiento: ".$seat->column }}" {{
                                                                $seat->is_available == 3 ? "disabled": "" }}
                                                                >
                                                                <svg class="icon-seat" viewBox="0 0 24 20">
                                                                    <path fill="{{ $seat->is_available == 3 ? "
                                                                        #eb2727": "#5495D2" }}"
                                                                        class="st0 asiento_{{$seat->id}}"
                                                                        d="M22.7,0H21c-0.7,0-1.3,0.6-1.3,1.4v0.4v2.6c0-2-1.6-3.7-3.6-3.7H8c-2,0-3.6,1.7-3.6,3.7V1.8V1.4 C4.4,0.6,3.8,0,3,0H1.3C0.6,0,0,0.6,0,1.4v0.4v11.3v0.5c0,1,0.8,1.8,1.8,1.8h0.1c0.1,0,0.2,0,0.3,0h0.2l0,0H3v2.7 C3,19.1,3.8,20,4.9,20h14.3c1.1,0,1.9-0.9,1.9-1.9v-2.7h0.6l0,0h0.8c0.9-0.1,1.5-0.9,1.5-1.8v-0.5V1.8V1.4C24,0.6,23.4,0,22.7,0z">
                                                                    </path>
                                                                </svg>
                                                                <span class="seat__num"
                                                                    aria-label="C10">{{$seat->column}}</span>
                                                            </li>



                                                            {{-- <a id="{{ $seat->id }}"
                                                                data-asiento="{{ $seat->seat() }}"
                                                                data-ticket="{{ $ticket->id }}"
                                                                data-seatzone="{{ $ticket->seat_zone }}" {{
                                                                $seat->is_available == 3 ? "disabled"
                                                                : "" }} class="seat" style="line-height: 0 !important;
                                                                margin: 0px 1px 0px 1px;padding:5px;"
                                                                href="javascript:void(0)"
                                                                title="{{ "Fila: ".$seat->row." - Asiento:
                                                                ".$seat->column }}">

                                                                <svg style="width: 20px" class="icon-seat"
                                                                    viewBox="0 0 24 20">
                                                                    <path fill="#ffc526" class="st0"
                                                                        d="M22.7,0H21c-0.7,0-1.3,0.6-1.3,1.4v0.4v2.6c0-2-1.6-3.7-3.6-3.7H8c-2,0-3.6,1.7-3.6,3.7V1.8V1.4 C4.4,0.6,3.8,0,3,0H1.3C0.6,0,0,0.6,0,1.4v0.4v11.3v0.5c0,1,0.8,1.8,1.8,1.8h0.1c0.1,0,0.2,0,0.3,0h0.2l0,0H3v2.7 C3,19.1,3.8,20,4.9,20h14.3c1.1,0,1.9-0.9,1.9-1.9v-2.7h0.6l0,0h0.8c0.9-0.1,1.5-0.9,1.5-1.8v-0.5V1.8V1.4C24,0.6,23.4,0,22.7,0z">
                                                                    </path>
                                                                </svg>

                                                            </a> --}}
                                                            <input type="hidden" name="sillas[]"
                                                                id="silla{{ $seat->id }}">
                                                            @else

                                                            <li class="seat seatSelect {{ $seat->is_available == 3 ? "
                                                                btns-danger": "btns-info" }}"
                                                                style="width: 25px; padding-left: 1px;"
                                                                id="{{ $seat->id }}" data-asiento="{{ $seat->seat() }}"
                                                                data-ticket="{{ $ticket->id }}"
                                                                data-seatzone="{{ $ticket->seat_zone }}" title="{{ "
                                                                Fila: ".$seat->row." - Asiento: ".$seat->column }}" {{
                                                                $seat->is_available == 3 ? "disabled": "" }}
                                                                >
                                                                <svg class="icon-seat" viewBox="0 0 24 20">
                                                                    <path fill="{{ $seat->is_available == 3 ? "
                                                                        #eb2727": "#5495D2" }}"
                                                                        class="st0 asiento_{{$seat->id}}"
                                                                        d="M22.7,0H21c-0.7,0-1.3,0.6-1.3,1.4v0.4v2.6c0-2-1.6-3.7-3.6-3.7H8c-2,0-3.6,1.7-3.6,3.7V1.8V1.4 C4.4,0.6,3.8,0,3,0H1.3C0.6,0,0,0.6,0,1.4v0.4v11.3v0.5c0,1,0.8,1.8,1.8,1.8h0.1c0.1,0,0.2,0,0.3,0h0.2l0,0H3v2.7 C3,19.1,3.8,20,4.9,20h14.3c1.1,0,1.9-0.9,1.9-1.9v-2.7h0.6l0,0h0.8c0.9-0.1,1.5-0.9,1.5-1.8v-0.5V1.8V1.4C24,0.6,23.4,0,22.7,0z">
                                                                    </path>
                                                                </svg>
                                                                <span class="seat__num"
                                                                    aria-label="C10">{{$seat->column}}</span>
                                                            </li>
                                                            @endif
                                                            @php
                                                            $termina = $seat->row;
                                                            @endphp

                                                            @endif
                                                            @endforeach

                                                        </ul>
                                                    </div>

                                                </div>
                                                <br><br>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default back" data-dismiss="modal">Continuar</button>
                    </div>
                </div>

            </div>
        </div>

        {!!Form::submit(trans("Public_ViewEvent.register"), ['class' => 'btn btn-lg btn-primary
        pull-right'])!!}


        {!! Form::hidden('is_embedded', $is_embedded) !!}
        {!! Form::close() !!}

        @else

        <div class="alert alert-boring">
            @lang("Public_ViewEvent.tickets_are_currently_unavailable")
        </div>

        @endif

        @endif

</section>

<style>

</style>