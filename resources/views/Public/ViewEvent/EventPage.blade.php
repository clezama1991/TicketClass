@extends('Public.ViewEvent.Layouts.EventPage')

@section('head')
    @include('Public.ViewEvent.Partials.GoogleTagManager')
@endsection

@section('content')
    @include('Public.ViewEvent.Partials.EventHeaderSection')
    <div class="grismuyclarofondo" style="width:100%">
        <!-- InstanceBeginEditable name="titulo_copete" -->

        <!-- InstanceEndEditable -->
        <div id="contenido_completo" class="ancholimitado"> <!--style="margin-top:10px"-->

            @include('Public.ViewEvent.Partials.EventTitleSection')



            <div style="width:100%; text-align:center">
                <div class="flex-container-wrap">
                    <div class="flexizq66a100compra" style="vertical-align:top;">
                        <div id="contenedor_principal">
                            <!-- InstanceBeginEditable name="contenido" -->



                            <div class="flex-container-nowrap boldear">
                                <div class="ancho_50 blancofondo font13 alinearenmedio flex-container-nowrap"
                                    style="padding:5px; justify-content:center; align-items:center">
                                    Busqueda de Boletos
                                </div>

                                <div class="ancho_50 grisclarofondo font13 alinearenmedio flex-container-nowrap"
                                    style="padding:5px; cursor:pointer;  justify-content:center; align-items:center"
                                    onclick="document.location.href=&#39;paso1c.aspx?idevento=31281&#39;; gtag( &#39;event&#39;, &#39;Selecciona Tus Asientos&#39;, {&#39;event_category&#39;: &#39;Busqueda de Lugares&#39;, &#39;event_label&#39;: &#39;GLORIA TREVI&#39;});return false;">
                                    Selecciona tus Asientos
                                </div>

                            </div>

                            <div class="ancho_100"
                                style="background-color:#ffffff; padding:0px 20px 20px 20px; border:none; margin-bottom:10px"
                                align="center">

                                <div style="padding-top:20px">
                                    <div id="divHeaderZona" class="headerpaso" style="">Selecciona tu
                                        Zona&nbsp;<span id="spZonaSeleccionada" class="headerseleccion"></span>
                                    </div>

                                    <div id="divSecciones" style="">
                                        @foreach ($tickets_all as $keygrup => $item)
                                            @php
                                                $name_grup = str_replace(' ', '_', $keygrup);
                                            @endphp

                                            <div class="seccion contenedor select-zona d-block"
                                                data-zonaid="{{ $name_grup }}" data-zonaname="{{ $keygrup }}"
                                                style="display: block;">
                                                <div class="seccion titulo">{{ $keygrup }}</div>
                                                <div class="seccion agotado" style="display: none;">AGOTADO</div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div id="divHeaderSeccion" class="headerpaso">Selecciona tu
                                        SECCION&nbsp;<span id="spSeccionSeleccionado" class="headerseleccion"></span>
                                    </div>
                                    <div class="IdDivZonas">
                                        <div id="divSecciones" style="">
                                            @foreach ($tickets_all as $key_tickets_all => $ticket_all)
                                                @php
                                                    $name_grup = str_replace(' ', '_', $key_tickets_all);
                                                @endphp

                                                @foreach ($ticket_all as $sectkey => $sect)
                                                    @php
                                                        $name_section = str_replace(' ', '_', $sectkey);
                                                    @endphp
                                                    <div class="seccion contenedor select-section d-none {{ $name_grup }}"
                                                        data-sectionid="{{ $name_section }}"
                                                        data-seccionname="{{ $sectkey }}" style="display: block;">
                                                        <div class="seccion titulo"> {{ $sectkey }}</div>
                                                        <div class="seccion precio">

                                                            @foreach ($sect as $ticketskey => $tickets)
                                                                {{ money($tickets[0]->price, $event->currency) }}
                                                            @endforeach
                                                        </div>
                                                        <div class="seccion agotado" style="display: none;">AGOTADO</div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>





                                    <div id="divHeaderBloques" class="headerpaso">Selecciona tu
                                        BLOQUE&nbsp;<span id="spBloqueSeleccionado" class="headerseleccion"></span>
                                    </div>
                                    <div id="divBloques" style="">
                                        @foreach ($tickets_all as $key_tickets_all => $ticket_all)
                                            @php
                                                $name_grup = str_replace(' ', '_', $key_tickets_all);
                                            @endphp
                                            @foreach ($ticket_all as $sectkey => $sect)
                                                @php
                                                    $name_section = str_replace(' ', '_', $sectkey);
                                                @endphp
                                                @foreach ($sect as $ticketskey => $tickets)
                                                    @foreach ($tickets as $ticketkey => $ticket)
                                                        <div class="bloque contenedor d-none select-bloque {{ $name_section }}"
                                                            data-bloquename="{{ $ticket->title }}"
                                                            data-ticketid="{{ $ticket->id }}"
                                                            data-bloqueid="{{ $name_section }}">
                                                            <div class="bloque titulo"> {{ $ticket->title }}</div>
                                                            <div class="bloque mensaje"> </div>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        @endforeach

                                    </div>


                                    <div id="divPrecios" style="">
                                        
    {!! Form::open(['url' => route('postValidateTickets', ['event_id' => $event->id]), 'class' => 'ajax']) !!}
    <input type="hidden" name="asientos_marcados" id="asientos_marcados">
    <input type="hidden" name="asientos" id="asientos_id">


    
                                        @foreach ($tickets_all as $key_tickets_all => $ticket_all)
                                            @php
                                                $name_grup = str_replace(' ', '_', $key_tickets_all);
                                            @endphp
                                            @foreach ($ticket_all as $sectkey => $sect)
                                                @php
                                                    $name_section = str_replace(' ', '_', $sectkey);
                                                @endphp
                                                @foreach ($sect as $ticketskey => $tickets)
                                                    @foreach ($tickets as $ticketkey => $ticket) 


                                                    {!! Form::hidden('tickets[]', $ticket->id) !!}
                                                    <meta property="availability" content="http://schema.org/InStock">
                                                    @if($ticket->select_seat==1)

                                                    <input type="hidden" name="ticket_{{$ticket->id}}" value="0">
                                                    <input type="hidden" name="asientos_{{$ticket->id}}">
                                                    @endif
                                                    <div class="d-none " id="{{ $ticket->id }}">



                                                    @php
                                                    $termina = 1;
                                                    $ticket_seats = $ticket->seats->groupBy('row');
                 
                                    $ticket_seat_zone = str_replace(' ', '_', $ticket->seat_zone );
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


                                                                    
                                                        <div class="precio contenedor d-block">
                                                            <div class="tb ancho_100">
                                                                <div
                                                                    class="precio titulo col_i-Desplegada_inline-SmartPhone text_i_izq_cent_SmartPhone col_ancho33 alinearenmedio">
                                                                    <span class="precio etiqueta boldear600 font16">ADULTO</span>&nbsp;
                                                                </div>
                                                                <div
                                                                    class="precio codigo col_i-Desplegada_inline-SmartPhone centrarTexto col_ancho33 alinearenmedio">
                                                                    <div class="precio valor boldear800 azul font16 centrarTexto alinearenmedio"
                                                                        data-toggle="tooltip" data-price-db="[total]"
                                                                        data-original-title="Importe: [price]" cargos:'[charge]'=""
                                                                        data-priceid="921688"
                                                                        data-bs-original-title="Importe: $1,200.00<br> Cargos: $190.00">
                                                                        {{money($ticket->price, $event->currency)}} 
                                                                    </div>
                                                                    <div class="codigopromo_container"></div>
                                                                </div>
                                                                <div
                                                                    class="precio cantidad col_i-Desplegada_block-SmartPhone centrarTexto ">
                                                                    <div class="spinbox_container">
                                                                        <input id="ticket_{{ $ticket->id }}" type="text" value="1"
                                                                             name="ticket_{{$ticket->id}}" data-id="{{$ticket->id}}"
                                                                            data-seatzone="{{ $ticket_seat_zone }}"
                                                                            class="form-control select-nro-seat"
                                                                            >  
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> 
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
                                                                                data-seatzone="{{ $ticket_seat_zone }}" title="{{ "
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
                                                                                data-seatzone="{{ $ticket_seat_zone }}" title="{{ "
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
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                                                    
                                        <input
                                id="btnContinuar921688" type="button" value="Continuar"
                                class="goButton" style="display: none;">


                                
        {!!Form::submit(trans("Public_ViewEvent.register"), ['class' => 'btn btn-lg btn-primary
        pull-right'])!!}


        {!! Form::hidden('is_embedded', $is_embedded) !!}
        {!! Form::close() !!}


                                    </div>
 
                                </div>
                            </div>


                            @include('Public.ViewEvent.Partials.EventShareSection')
                            <!-- termina contenido principal-->
                            <!-- InstanceEndEditable -->
                        </div>
                    </div>


                    @include('Public.ViewEvent.Partials.EventInfoSection')

                </div>
            </div>

        </div>
    </div>

    </div>


    {{-- 
    @include('Public.ViewEvent.Partials.EventTicketsSection')
    @include('Public.ViewEvent.Partials.EventDescriptionSection')
    @include('Public.ViewEvent.Partials.EventShareSection')
    @include('Public.ViewEvent.Partials.EventFooterSection') --}}
@stop

@section('scripts')
    <script>
        var global = '';
        var zona = '';
        var seccion = 'xx';
        var bloque = '';


        $("#divHeaderZona").on('click', function(e) {
            alert(zona);
            $('#spZonaSeleccionada').html('');
            $('.select-zona').removeClass('d-none');
            $('.select-zona').addClass('d-block');
            $('.select-section').addClass('d-none');
        });



        $(".select-zona").on('click', function(e) {
            // alert('zona'); 
            var zonaid_display = $(this).data('zonaid');
            var zonaname_display = $(this).data('zonaname');
            zona = zonaname_display;

            $('#spZonaSeleccionada').html(zonaname_display);
            $('.select-zona').addClass('d-none');
            $('.' + zonaid_display).removeClass('d-none');
        });



        $("#divHeaderSeccion").on('click', function(e) {
            alert(zona);
            $('#spSeccionSeleccionado').html('');
            $('.' + zona).removeClass('d-none');
            $('.' + zona).addClass('d-block');
            $('.select-bloque').addClass('d-none');
        });


        $(".select-section").on('click', function(e) {
            // alert('section'); 
            var seccionname_display = $(this).data('seccionname');

            $('#spSeccionSeleccionado').html(seccionname_display);
            var sectionid_display = $(this).data('sectionid');
            seccion = sectionid_display;

            $('.select-section').addClass('d-none');
            $('.' + sectionid_display).removeClass('d-none');
            $('.' + sectionid_display).addClass('d-block');
        });




        $("#divHeaderBloques").on('click', function(e) {
            alert(seccion);
            $('#spBloqueSeleccionado').html('');
            $('.select-bloque').removeClass('d-none d-block');
            $('.' + seccion).removeClass('d-none');
            $('.' + seccion).addClass('d-block');
        });

        $(".select-bloque").on('click', function(e) {
            // alert('section'); 
            var bloquename_display = $(this).data('bloquename');

            $('#spBloqueSeleccionado').html(bloquename_display);

            $('.select-bloque').addClass('d-none');

            
            var ticketid = $(this).data('ticketid');

            
            var bloqueid_display = $(this).data('bloqueid');
            $('#' + ticketid).removeClass('d-none');
            $('#' + ticketid).addClass('d-block');
            $("input[name='demo3']").TouchSpin();
                $('#ticket_' +ticketid).TouchSpin();
        
        });
    </script>
@stop
