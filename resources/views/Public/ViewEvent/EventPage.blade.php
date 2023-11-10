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



                             <!-- Nav tabs -->
                         
                              <!-- Tab panes -->
                         
 
            <div style="width:100%; text-align:center">
                <div class="flex-container-wrap">
                    <div class="flexizq66a100compra" style="vertical-align:top;">
                        <ul class="nav nav-tabs " id="myTab" role="tablist">
                            <li class="nav-item w-50 show-boleto" role="presentation">
                              <button class="nav-link active w-100" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Busqueda de Boletos</button>
                            </li>
                            <li class="nav-item w-50 show-mapa" role="presentation">
                              <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Selecciona tus Asientos</button>
                            </li> 
                          </ul>
                          
                        <div id="contenedor_principal">
                            <!-- InstanceBeginEditable name="contenido" -->



                            <div class="ancho_100"
                                style="background-color:#ffffff; padding:0px 20px 20px 20px; border:none; margin-bottom:10px"
                                align="center">

                                <div class="tab-content">
                                    <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">  
     
                                        <div style="padding-top:20px">


                                            <div id="divHeaderZona" class="headerpaso" style="">Selecciona tu
                                                Zona&nbsp;<span id="spZonaSeleccionada" class="headerseleccion"></span>
                                            </div>

                                            <div id="divSecciones" style="">
                                                @php
                                                    $grupo_zona = 1;
                                                @endphp
                                                @foreach ($tickets_all as $keygrup => $item)
                                                    @php
                                                        $name_grup = str_replace(' ', '_', $keygrup);
                                                    @endphp

                                                    <div class="seccion contenedor select-zona d-block" id="grupo{{ $grupo_zona }}"
                                                        data-zonaid="{{ $name_grup }}" data-zonaname="{{ $keygrup }}"
                                                        style="display: block;">
                                                        <div class="seccion titulo">{{ $keygrup }}</div>
                                                     </div>
                                                     @php
                                                         $grupo_zona++;
                                                     @endphp
                                                @endforeach
                                            </div>

                                            <div id="divHeaderSeccion" class="headerpaso IdDivZonasT d-none">Selecciona tu
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
                                                                data-seccionname="{{ $sectkey }}" 
                                                                style="display: block;">
                                                                <div class="seccion titulo"> {{ $sectkey }}</div>
                                                                <div class="seccion precio">

                                                                    @foreach ($sect as $ticketskey => $tickets)
                                                                        {{ money($tickets[0]->price ?? 0, $event->currency) }}
                                                                    @endforeach
                                                                </div>
                                                                <div class="seccion agotado" style="display: none;">AGOTADO</div>
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>





                                            <div id="divHeaderBloques" class="headerpaso  divBloquesT d-none">Selecciona tu
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


                                        </div>
                                    </div>
                                    <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab"> 
                                        
                                        @include('Public.ViewEvent.Partials.MapaSalesArea')
                                    
                                    </div> 
                                </div>

                                
                                <div id="divPrecios" class="card mt-5" style="">

                                    {!! Form::open(['url' => route('postValidateTickets', ['event_id' => $event->id]), 'class' => 'ajax']) !!}
                                    <input type="hidden" name="asientos_marcados" id="asientos_marcados">
                                    <input type="hidden" name="asientos" id="asientos_id">
                                                    <meta property="availability" content="http://schema.org/InStock">

                                                @foreach ($event->tickets as $vite)     
           
                                                    <input type="hidden" name="tickets[]" value="{{$vite->id}}">

                                                         <input type="hidden" name="ticket_{{ $vite->id }}"
                                                            value="0" class="ticketx_{{ $vite->id }}">
                                                        <input type="hidden" name="asientos_{{ $vite->id }}">
                                                     
                                                @endforeach
 
                                    @foreach ($tickets_all as $key_tickets_all => $ticket_all)
                                        @php
                                            $name_grup = str_replace(' ', '_', $key_tickets_all);
                                        @endphp
                                        @foreach ($ticket_all as $sectkey => $sect)
                                            @php
                                                $name_section = str_replace(' ', '_', $sectkey);
                                            @endphp
                                            @foreach ($sect as $ticketskey => $tickets_scr)
                                                @foreach ($tickets_scr as $ticketkey => $ticket)                                                      
                                                    
                                                    
                                                    @php
                                                    $termina = 1;
                                                    $ticket_seats = $ticket->seats->groupBy('row');

                                                    $ticket_seat_zone = str_replace(' ', '_', $ticket->seat_zone);
                                                @endphp

                                                    <div class="d-none zone-events zone-event-{{$ticket_seat_zone}}" id="{{ $ticket->id }}" >

                                                        <div class="card-header">
                                                            <h5 class="card-title">
                                                                @if ($ticket->select_seat == 1)
                                                                Selecciona los asientos del bloque:  {{$ticket->title}} 

                                                                @else
                                                                Selecciona la cantidad de boletos del bloque:  {{$ticket->title}} 

                                                                @endif
                                                            </h5>
                                                        </div> 
                                                        <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12">

                                                                <div class="row d-flex justify-content-end mb-5">
                                                                    <div class="col-12 d-flex justify-content-end">
                                                                        <a data-ticketid="{{$ticket->id}}" class="btn btn-primary zoom-out" style="margin-right: 5px;">
                                                                             <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                                                                            <svg fill="#ffffff" width="20px" height="20px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                                                            <title>search-minus</title>
                                                                            <path d="M30.531 29.469l-10.451-10.451c1.645-1.874 2.649-4.346 2.649-7.053 0-5.917-4.797-10.714-10.714-10.714-0.005 0-0.011 0-0.016 0h0.001c-0 0-0.001 0-0.001 0-5.936 0-10.749 4.812-10.749 10.749 0 2.968 1.203 5.656 3.148 7.601v0c1.931 1.946 4.607 3.15 7.564 3.15 2.711 0 5.185-1.012 7.066-2.68l-0.011 0.010 10.451 10.451c0.136 0.136 0.324 0.22 0.531 0.22 0.415 0 0.751-0.336 0.751-0.751 0-0.207-0.084-0.395-0.22-0.531v0zM5.459 18.539c-1.688-1.676-2.733-3.998-2.733-6.564 0-5.108 4.141-9.249 9.249-9.249 2.566 0 4.888 1.045 6.564 2.733l0.001 0.001h0.002c1.674 1.674 2.709 3.986 2.709 6.54s-1.035 4.866-2.708 6.539v0l-0.002 0.001-0.001 0.003c-1.673 1.673-3.985 2.708-6.538 2.708-2.555 0-4.867-1.036-6.541-2.711l-0-0zM16 11.25h-8c-0.414 0-0.75 0.336-0.75 0.75s0.336 0.75 0.75 0.75v0h8c0.414 0 0.75-0.336 0.75-0.75s-0.336-0.75-0.75-0.75v0z"></path>
                                                                            </svg>
                                                                        </a> 
                                                                        <a data-ticketid="{{$ticket->id}}" class="btn btn-primary zoom-in">
                                                                             <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                                                                            <svg fill="#ffffff" width="20px" height="20px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                                                            <title>search-plus</title>
                                                                            <path d="M30.531 29.469l-10.453-10.453c1.604-1.861 2.58-4.301 2.58-6.97 0-5.912-4.793-10.704-10.704-10.704s-10.704 4.793-10.704 10.704c0 5.912 4.793 10.704 10.704 10.704 0.016 0 0.032-0 0.048-0h-0.002c2.697-0.011 5.156-1.022 7.027-2.681l-0.011 0.010 10.453 10.453c0.136 0.136 0.324 0.22 0.531 0.22 0.415 0 0.751-0.336 0.751-0.751 0-0.207-0.084-0.395-0.22-0.531v0zM2.75 12c0-5.109 4.141-9.25 9.25-9.25s9.25 4.141 9.25 9.25c0 5.109-4.141 9.25-9.25 9.25v0c-5.106-0.006-9.244-4.144-9.25-9.249v-0.001zM16 11.25h-3.25v-3.25c0-0.414-0.336-0.75-0.75-0.75s-0.75 0.336-0.75 0.75v0 3.25h-3.25c-0.414 0-0.75 0.336-0.75 0.75s0.336 0.75 0.75 0.75v0h3.25v3.25c0 0.414 0.336 0.75 0.75 0.75s0.75-0.336 0.75-0.75v0-3.25h3.25c0.414 0 0.75-0.336 0.75-0.75s-0.336-0.75-0.75-0.75v0z"></path>
                                                                            </svg>
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                                <div class="row m-5 mapa{{$ticket->id}}">
 
                                                                    @if ($ticket->select_seat == 1)
                                                                        {{-- <div class="col-md-12"
                                                                            style="display: flex;justify-content: center; margin-bottom: 15px; ">
                                                                            <img src="{{ asset('assets/images/select_seat/stage.jpg') }}"
                                                                                alt="selected" alt="">

                                                                            <br>
                                                                        </div> --}}
                                                                    @else
                                                                        <div class="col-md-12">
                                                                            @if ($ticket->is_paused)
                                                                                <span class="text-danger">
                                                                                    @lang('Public_ViewEvent.currently_not_on_sale')
                                                                                </span>
                                                                            @else
                                                                                @if ($ticket->sale_status === config('attendize.ticket_status_sold_out'))
                                                                                    <span class="text-danger"
                                                                                        property="availability"
                                                                                        content="http://schema.org/SoldOut">
                                                                                        @lang('Public_ViewEvent.sold_out')
                                                                                    </span>
                                                                                @elseif($ticket->sale_status === config('attendize.ticket_status_before_sale_date'))
                                                                                    <span class="text-danger">
                                                                                        @lang('Public_ViewEvent.sales_have_not_started')
                                                                                    </span>
                                                                                @elseif($ticket->sale_status === config('attendize.ticket_status_after_sale_date'))
                                                                                    <span class="text-danger">
                                                                                        @lang('Public_ViewEvent.sales_have_ended')
                                                                                    </span>
                                                                                @else
                                                                                    <div
                                                                                        class="precio contenedor d-block">
                                                                                        <div class="tb ancho_100">
                                                                                            <div
                                                                                                class="precio titulo col_i-Desplegada_inline-SmartPhone text_i_izq_cent_SmartPhone col_ancho33 alinearenmedio">
                                                                                                <span
                                                                                                    class="precio etiqueta boldear600 font16">ADULTO</span>&nbsp;
                                                                                            </div>
                                                                                            <div
                                                                                                class="precio codigo col_i-Desplegada_inline-SmartPhone centrarTexto col_ancho33 alinearenmedio">
                                                                                                <div class="precio valor boldear800 azul font16 centrarTexto alinearenmedio"
                                                                                                    data-toggle="tooltip"
                                                                                                    data-price-db="[total]"
                                                                                                    data-original-title="Importe: [price]"
                                                                                                    cargos:'[charge]'=""
                                                                                                    data-priceid="921688"
                                                                                                    data-bs-original-title="Importe: $1,200.00<br> Cargos: $190.00">
                                                                                                    {{ money($ticket->price, $event->currency) }}
                                                                                                </div>
                                                                                                <div
                                                                                                    class="codigopromo_container">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="precio cantidad col_i-Desplegada_block-SmartPhone centrarTexto ">
                                                                                                <div class="input-group input-group-sm bootstrap-touchspin">
                                                                                                    <span class="input-group-btn">
                                                                                                        <button class="btn btn-primary bootstrap-touchspin-down" data-id="{{ $ticket->id }}" type="button">-</button>
                                                                                                    </span>
                                                                                                    <span class="input-group-addon bootstrap-touchspin-prefix" style="display: none;"></span>
                                                                                                    <input 
                                                                                                        id=""
                                                                                                        type="text" 
                                                                                                        value="0" 
                                                                                                        name="ticket_{{ $ticket->id }}"
                                                                                                        data-id="{{ $ticket->id }}"
                                                                                                        data-seatzone="{{ $ticket_seat_zone }}"
                                                                                                        class="form-control select-nro-seat ticketx_{{ $ticket->id }}"
                                                                                                        style="display: block;">
                                                                                                    <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;"></span>
                                                                                                    <span class="input-group-btn">
                                                                                                        <button class="btn btn-primary bootstrap-touchspin-up" data-id="{{ $ticket->id }}" type="button">+</button>
                                                                                                    </span>
                                                                                                </div>
                                                                                            </div>
                                                                                            <!--
                                                                                            <div
                                                                                                class="precio cantidad col_i-Desplegada_block-SmartPhone centrarTexto ">
                                                                                                <div
                                                                                                    class="spinbox_container">
                                                                                                    <input
                                                                                                        id="ticketx_{{ $ticket->id }}"
                                                                                                        type="text"
                                                                                                        value="0"
                                                                                                        name="ticket_{{ $ticket->id }}"
                                                                                                        data-id="{{ $ticket->id }}"
                                                                                                        data-seatzone="{{ $ticket_seat_zone }}"
                                                                                                        class="form-control select-nro-seat">
                                                                                                </div>
                                                                                            </div>
                                                                                        -->
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endif

                                                                        </div>
                                                                    @endif
 
                                                                    @php
                                                                    $key_abecedario = 0;
                                                                @endphp
                                                                
                                                                    @if ($ticket->select_seat == 1)
                                                                        <div class="col-md-12"> 
                                                                            <div class="row" style="text-align: justify;">
                                                                            <div class="col-md-3" >
                                                                                <h5>Filas</h5>
                                                                            </div>
                                                                            <div class="col-md-9">
                                                                                <h5>Asientos</h5>
                                                                            </div></div>
                                                                        </div>
                                                                    @endif
                                                                    @foreach ($ticket_seats as $key_file => $seats)
                                                                    
                                                                        <div class="col-md-12">

                                                                            <div class="seats__grid">
                                                                                <ul class="nostyle seats__row "
                                                                                    style="list-style:none;padding:0;margin:0;display: flex;justify-content: center;">
                                                                                    <li class="seat"
                                                                                        style="font-weight: bolder;width: 30px;position: absolute;left: 20px;">
                                                                                        {{ $abecedario[$key_abecedario]}}</li>

                                                                                    @foreach ($seats as $t => $seat)
                                                                                        @if ($key_file == $seat->row)
                                                                                        @if ($t>0)
                                                                                            @if ($seat->is_available != 2)
                                                                                                <li class="seat seatSelect st0 asiento_{{$seat->id}} 
                                                                                                    {{ $seat->is_available == 3 ? "bg-info" : 'bg-white' }}"
                                                                                                    style="width: 25px; padding-left: 1px;     border: 1px solid;
                                                                                                    border-radius: 5px; margin-right:.6px; margin-left:.6px; font-size:13px"
                                                                                                    id="{{ $seat->id }}"
                                                                                                    data-asiento="{{ $seat->seat() }}"
                                                                                                    data-ticket="{{ $ticket->id }}"
                                                                                                    data-seatzone="{{ $ticket_seat_zone }}"
                                                                                                    title="{{ $seat->is_available == 3 ? "Asiento Ocupado" : " " . $seat->seat() }}"
                                                                                                    {{ $seat->is_available == 3 ? 'disabled' : '' }}>
                                                                                                    {{ $seat->column }}
                                                                                                </li>

                                                                                                <input type="hidden"
                                                                                                    name="sillas[]"
                                                                                                    id="silla{{ $seat->id }}">
                                                                                            @else
                                                                                                <li class="seat seatSelect st0 asiento_{{$seat->id}} 
                                                                                                    {{ $seat->is_available == 3 ? "bg-info" : 'bg-white' }}"
                                                                                                    style="width: 25px; padding-left: 1px;     border: 1px solid;
                                                                                                    border-radius: 5px; margin-right:.6px; margin-left:.6px;; font-size:13px"
                                                                                                    id="{{ $seat->id }}"
                                                                                                    data-asiento="{{ $seat->seat() }}"
                                                                                                    data-ticket="{{ $ticket->id }}"
                                                                                                    data-seatzone="{{ $ticket_seat_zone }}"
                                                                                                    title="{{ $seat->is_available == 3 ? "Asiento Ocupado" : " " . $seat->seat() }}"
                                                                                                    {{ $seat->is_available == 3 ? 'disabled' : '' }}>
                                                                                                   
                                                                                                    <span
                                                                                                        class="seat__num"
                                                                                                        aria-label="C10">{{ $seat->column }}</span>
                                                                                                </li>
                                                                                            @endif
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
                                                                        @php
                                                                            $key_abecedario++;
                                                                        @endphp
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>  



                                                    </div>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @endforeach


                                    <br> 
                                    {!! Form::submit(trans('Public_ViewEvent.register'), [
                                        'class' => 'btn btn-lg btn-primary
                                                                    pull-right',
                                    ]) !!}


                                    {!! Form::hidden('is_embedded', $is_embedded) !!}
                                    {!! Form::close() !!}
                                                <br> 

                                </div>

                            </div>

                            <!-- termina contenido principal-->
                            <!-- InstanceEndEditable -->
                        </div>
                    </div>


                    @include('Public.ViewEvent.Partials.EventInfoSection')
 
                </div>
                

                {{-- @include('Public.ViewEvent.Partials.EventShareSection') --}}
            </div>

        </div>
    </div>

    </div>
    <input type="hidden" id="cantidad_grupos" value="{{count($tickets_all)}}">

    {{-- 
    @include('Public.ViewEvent.Partials.EventTicketsSection')
    @include('Public.ViewEvent.Partials.EventDescriptionSection')
    @include('Public.ViewEvent.Partials.EventShareSection')
    @include('Public.ViewEvent.Partials.EventFooterSection') --}}
@stop

@section('scripts')


<script> 
    
    $('.bootstrap-touchspin-down').click(function(event) { 
        var id = $(this).data('id');
        var nro = $('.ticketx_'+id).val(); 
        var nrotickets = parseInt(nro) - 1;
        $('.ticketx_'+id).val( nrotickets<0 ? 0 : nrotickets ); 
    });

    $('.bootstrap-touchspin-up').click(function(event) {
        var id = $(this).data('id');
        var nro = $('.ticketx_'+id).val();
        $('.ticketx_'+id).val( parseInt(nro) +1);
    });
    $('.zoom-in').click(function() { 
        updtZoom(0.1, $(this).data('ticketid'));
    });
    
    $('.zoom-out').click(function() {
        updtZoom(-0.1, $(this).data('ticketid'));
    });
    
    zoomLvl = 1;
    
    var updtZoom = function(zoom, ticketid) {
        zoomLvl += zoom;
        $('.mapa'+ticketid).css({ zoom: zoomLvl, '-moz-transform': 'scale(' + zoomLvl + ')' , transition: 'all 3s ease'});
    }

</script>

    <script>
        var global = '';
        var zona = '';
        var seccion = 'xx';
        var bloque = '';


        $("#divHeaderZona").on('click', function(e) {
            $('#spZonaSeleccionada').html('');
            $('.select-zona').removeClass('d-none');
            $('.select-zona').addClass('d-block');
            $('.select-section').addClass('d-none');
            $('.zone-events').addClass('d-none').removeClass('d-block');
        });



        $(".select-zona").on('click', function(e) {
            var zonaid_display = $(this).data('zonaid');
            var zonaname_display = $(this).data('zonaname');
            zona = zonaname_display;

            $('#spZonaSeleccionada').html(zonaname_display);
            $('.select-zona').addClass('d-none'); 
            $('.' + zonaid_display).removeClass('d-none');
            $('.IdDivZonasT').removeClass('d-none');            
            $('.zone-events').addClass('d-none').removeClass('d-block');
        });



        $("#divHeaderSeccion").on('click', function(e) {
            $('#spSeccionSeleccionado').html('');
            $('.' + zona).removeClass('d-none');
            $('.' + zona).addClass('d-block');
            $('.select-bloque').addClass('d-none');            
            $('#divHeaderBloques').addClass('d-none').removeClass('d-block');
            $('.zone-events').addClass('d-none').removeClass('d-block');
        });


        $(".select-section").on('click', function(e) {
            var seccionname_display = $(this).data('seccionname');

            $('#spSeccionSeleccionado').html(seccionname_display);
            var sectionid_display = $(this).data('sectionid');
            seccion = sectionid_display;

            $('.select-section').addClass('d-none');
            $('.divBloquesT').removeClass('d-none');
            $('.' + sectionid_display).removeClass('d-none');
            $('.' + sectionid_display).addClass('d-block');
            $('.zone-events').addClass('d-none').removeClass('d-block');
        });




        $("#divHeaderBloques").on('click', function(e) {
            $('#spBloqueSeleccionado').html('');
            $('.select-bloque').removeClass('d-none d-block');
            $('.' + seccion).removeClass('d-none');
            $('.' + seccion).addClass('d-block');
        });

        $(".select-bloque").on('click', function(e) {
            var bloquename_display = $(this).data('bloquename');

            $('#spBloqueSeleccionado').html(bloquename_display);

            $('.select-bloque').addClass('d-none');


            var ticketid = $(this).data('ticketid');

            var bloqueid_display = $(this).data('bloqueid');
            $('#' + ticketid).removeClass('d-none');
            $('#' + ticketid).addClass('d-block');
           

        });
    </script>
@stop
