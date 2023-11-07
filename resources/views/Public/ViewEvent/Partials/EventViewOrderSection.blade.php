<style>
    /*@todo This is temp - move to styles*/
    h3 {
        border: none !important;
        font-size: 30px;
        text-align: center;
        margin: 0;
        margin-bottom: 30px;
        letter-spacing: .2em;
        font-weight: 200;
    }

    .order_header {
        text-align: center
    }

    .order_header .massive-icon {
        display: block;
        width: 120px;
        height: 120px;
        font-size: 100px;
        margin: 0 auto;
        color: #63C05E;
    }

    .order_header h1 {
        margin-top: 20px;
        text-transform: uppercase;
    }

    .order_header h2 {
        margin-top: 5px;
        font-size: 20px;
    }

    .order_details.well, .offline_payment_instructions {
        margin-top: 25px;
        background-color: #FCFCFC;
        line-height: 30px;
        text-shadow: 0 1px 0 rgba(255,255,255,.9);
        color: #656565;
        overflow: hidden;
    }

    .ticket_download_link {
        border-bottom: 3px solid;
    }
</style>

<section id="order_form" class="container">

    <div class="row">
        <div class="col-md-12" style="text-align: center;     height: 40px;">
           
        </div>
        <div class="col-md-4 col-md-push-8">
             <br>
             <br>
             <br>
             <br> 
            <div class="pb-5" style="width:100%">
                <!-- InstanceBeginEditable name="titulo_copete" -->
        
                <!-- InstanceEndEditable -->
                <div id="contenido_completo" class="ancholimitado"> <!--style="margin-top:10px"-->
        
         
        
        
                                     <!-- Nav tabs -->
                                 
                                      <!-- Tab panes -->
                                 
         
                    <div style="width:100%; text-align:center">
                        <div class="flex-container-wrap">
                            <div id="Evento_COMPRANDO" >

                                <div class="espacio_margen_fondo_blanco_top lateral_Evento_COMPRANDO d-block">
                                    <div>
                                        @if(!is_null($event->imageCover())) 
                                            <img src="{{asset($event->imageCover())}}" class="imageneventocompra">
                                        @endif
                                    </div>
                                    <!-- inicia: Informacion Evento -->
                                    <div align="center" style="padding-top:10px">
                                        <div class="grisfondo" style="margin:5px auto 10px auto">
                                            <hr>
                                            {{-- <img src="{{asset('assets/images/spacer.gif')}}" height="1" width="100%"> --}}
                                        </div>
                                        <div style="margin:10px 0px 0px 0px; padding:0px 0px 10px 0px" class="font14" align="center">
                                            <div class="font18 boldear">{{$event->title}}</div>
                            
                                            <div class="font14">{{$event->venue_name}}</div>
                            
                                            {{-- <div class="font14">MoRIDA</div> --}}
                                            <div class="font12">  
                                                
                                                {{-- {{ $event->start_date->format('l') }},  --}}
                                                {{ $event->startDateFormatted() }}
                                                
                                                <span class="boldear800">@if($event->start_date->diffInDays($event->end_date) == 0)
                                                    {{ $event->end_date->format('H:i') }}
                                                @else
                                                    {{ $event->endDateFormatted() }}
                                                @endif</span>
                            
                                            </div>
                                        </div>
                                    </div>
                                    <!-- termina: Informacion Evento -->
                            
                                </div>
                                <div class="espacio_margen_fondo_blanco lateral_Evento_COMPRANDO">

                                    <div id="MapaTemplateCompra" style="margin:0px 0px 20px 0px;" class="font14"
                                        align="center">
                                        <div style="padding:10px 0px 5px 0px; margin:0px 5px 10px 5px">
                            
                                            <img src="{{asset($event->map->url)}}" >
                            
                                        </div>
                                    </div>
                            
                                    <!-- InstanceBeginEditable name="PreciosTemplateCompra" -->
                                    <!-- InstanceEndEditable -->
                            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-md-8 col-md-pull-4">
             
            <div class="row">
                <div class="col-md-12 order_header">
                    <span class="massive-icon">
                        <i class="ico ico-checkmark-circle"></i>
                    </span>
                    <h1>{{ @trans("Public_ViewEvent.thank_you_for_your_order") }}</h1>
                    <h2>
                        {{ @trans("Public_ViewEvent.your") }}
                        <a class="ticket_download_link"
                        href="{{ route('showOrderTickets', ['order_reference' => $order->order_reference] ).'?download=1' }}">
                            {{ @trans("Public_ViewEvent.tickets") }}</a> {{ @trans("Public_ViewEvent.confirmation_email") }}
                    </h2>
                </div>
            </div>

            <div class="row pt-3">
                
                <div class="col-md-12">
                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ico-cart mr5"></i>
                                @lang("Public_ViewEvent.order_details")
                            </h3>
                        </div>

                        <div class="card-body pt0">

                            <div class="content event_view_order">

                                @if($event->post_order_display_message)
                                <div class="alert alert-dismissable alert-info">
                                    {{ nl2br(e($event->post_order_display_message)) }}
                                </div>
                                @endif

                                <div class="order_details well">
                                    <div class="row">
                                        <div class="col-sm-4 col-xs-6">
                                            <b>@lang("Public_ViewEvent.first_name")</b><br> {{$order->first_name}}
                                        </div>

                                        <div class="col-sm-4 col-xs-6">
                                            <b>@lang("Public_ViewEvent.last_name")</b><br> {{$order->last_name}}
                                        </div>

                                        <div class="col-sm-4 col-xs-6">
                                            <b>@lang("Public_ViewEvent.amount")</b><br> {{$order->event->currency_symbol}}{{number_format($order->total_amount_online, 2)}}
                                            @if($event->organiser->charge_tax)
                                            <small>{{ $orderService->getVatFormattedInBrackets() }}</small>
                                            @endif
                                        </div>

                                        <div class="col-sm-4 col-xs-6">
                                            <b>@lang("Public_ViewEvent.reference")</b><br> {{$order->order_reference}}
                                        </div>

                                        <div class="col-sm-4 col-xs-6">
                                            <b>@lang("Public_ViewEvent.date")</b><br> {{$order->created_at->format(config('attendize.default_datetime_format'))}}
                                        </div>

                                        <div class="col-sm-4 col-xs-6">
                                            <b>@lang("Public_ViewEvent.email")</b><br> {{$order->email}}
                                        </div>
                                    </div>
                                </div>
 
                                    <hr class="my-5">
                                
                                    @if(!$order->is_payment_received)
                                        <h3>
                                            @lang("Public_ViewEvent.payment_instructions")
                                        </h3>
                                    <div class="alert alert-info">
                                        @lang("Public_ViewEvent.order_awaiting_payment")
                                    </div>
                                    <div class="offline_payment_instructions well">
                                        {!! Markdown::parse($event->offline_payment_instructions) !!}
                                    </div>

                                    @endif

                                    
                                    <hr class="my-5">
                                
                                <h3>
                                    @lang("Public_ViewEvent.order_items")
                                </h3>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    @lang("Public_ViewEvent.ticket")
                                                </th>
                                                <th>
                                                    @lang("Public_ViewEvent.quantity_full")
                                                </th>
                                                <th>
                                                    @lang("Public_ViewEvent.price")
                                                </th>
                                                <th>
                                                    @lang("Public_ViewEvent.booking_fee")
                                                </th>
                                                <th>
                                                    @lang("Public_ViewEvent.total")
                                                </th>
                                                <th style="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                
                                                $subtotal = 0;

                                            @endphp
                                            @foreach($order->orderItems as $order_item)
                                                <tr>
                                                    <td>
                                                        {{$order_item->title}}
                                                    </td>
                                                    <td>
                                                        {{$order_item->quantity}}
                                                    </td>
                                                    <td>
                                                        @if((int)ceil($order_item->unit_price) == 0)
                                                            @lang("Public_ViewEvent.free")
                                                        @else
                                                    {{money($order_item->unit_price, $order->event->currency)}}
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if((int)ceil($order_item->unit_price) == 0)
                                                        -
                                                        @else
                                                        {{money($order_item->unit_booking_fee, $order->event->currency)}}
                                                        @endif

                                                    </td>
                                                    <td style="text-align: right;" colspan="2">
                                                        @if((int)ceil($order_item->unit_price) == 0)
                                                            @lang("Public_ViewEvent.free")
                                                        @else
                                                        {{money(($order_item->unit_price + $order_item->unit_booking_fee) * ($order_item->quantity), $order->event->currency)}}
                                                        @endif


                                                        @php
                                                            $subtotal += ($order_item->unit_price + $order_item->unit_booking_fee) * ($order_item->quantity);

                                                        @endphp
                                                    </td> 
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                    <b>@lang("Public_ViewEvent.sub_total")</b>
                                                </td>
                                                <td colspan="2" style="text-align: right;">
                                                    {{money($subtotal, $order->event->currency)}}
                                                        
                                                   
                                                    {{-- {{ $orderService->getOrderTotalWithBookingFee(true) }} --}}
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                    Comision Paypal
                                                </td>
                                                <td colspan="2" style="text-align: right;">
                                                    {{$order->event->currency_symbol}}{{number_format($order->total_amount_online - $subtotal, 2)}}
 
                                                   
                                                    {{-- {{ $orderService->getOrderTotalWithBookingFee(true) }} --}}
                                                </td>
                                                <td></td>
                                            </tr>
                                            @if($event->organiser->charge_tax)
                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                    {{$event->organiser->tax_name}}
                                                </td>
                                                <td colspan="2" style="text-align: right;">
                                                    {{ $orderService->getTaxAmount(true) }}
                                                </td>
                                                <td></td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                    <b>Total</b>
                                                </td>
                                                <td colspan="2" style="text-align: right;">
                                                    {{$order->event->currency_symbol}}{{number_format($order->total_amount_online, 2)}}
                                                {{-- {{ $orderService->getGrandTotal(true) }} --}}
                                                </td>
                                                <td></td>
                                            </tr>
                                            @if($order->is_refunded || $order->is_partially_refunded)
                                                <tr>
                                                    <td>
                                                    </td>
                                                    <td>
                                                    </td>
                                                    <td>
                                                    </td>
                                                    <td>
                                                        <b>@lang("Public_ViewEvent.refunded_amount")</b>
                                                    </td>
                                                    <td colspan="2"  right;">
                                                        {{money($order->amount_refunded, $order->event->currency)}}
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    </td>
                                                    <td>
                                                    </td>
                                                    <td>
                                                    </td>
                                                    <td>
                                                        <b>@lang("Public_ViewEvent.total")</b>
                                                    </td>
                                                    <td colspan="2" style="text-align: right;">
                                                        {{money($order->total_amount - $order->amount_refunded, $order->event->currency)}}
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>

                                </div>

                                <hr class="my-5">
                                
                                <h3>
                                    @lang("Public_ViewEvent.order_attendees")
                                </h3>

                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <tbody>
                                            @foreach($order->attendees as $attendee)
                                            <tr>
                                                <td>
                                                    {{$attendee->first_name}}
                                                    {{$attendee->last_name}}
                                                    (<a href="mailto:{{$attendee->email}}">{{$attendee->email}}</a>)
                                                </td>
                                                <td>
                                                    {{{$attendee->ticket->title}}}
                                                </td>
                                                <td>
                                                    @if($attendee->is_cancelled)
                                                        @lang("Public_ViewEvent.attendee_cancelled")
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

