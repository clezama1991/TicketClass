<section id='order_form' class="container"> 
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
                                        <img src="{{asset($event->imageCover())}}"
                                            class="imageneventocompra">
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
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ico-cart mr5"></i>
                        @lang("Public_ViewEvent.order_details")
                    </h3>
                </div>

                <div class="card-body pt0">
                     
                    <div class="row d-flex justify-content-end">
                        <div class="col-md-6 d-flex justify-content-center align-items-center">
                            
                        <div class="help-block">
                            {!! @trans("Public_ViewEvent.time", ["time"=>"<span id='countdown'></span>"]) !!}
                        </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="card"> 
                                <div class="card-body">
                                    <div class="" style="font-size:14px; text-align:left; margin:10px 0px">
                                        Evento sujeto a cargo x servicio, los cuales están incluidos en el precio listado.
                                    </div>    
                                    <table class="table mb0 table-condensed">
                                        @foreach($tickets as $ticket)
                                        <tr>
                                            <td style="text-align: left;" class="pl0">{{{$ticket['ticket']['title']}}} X <b>{{$ticket['qty']}}</b></td>
                                            <td style="text-align: right;">
                                                @if((int)ceil($ticket['full_price']) === 0)
                                                    @lang("Public_ViewEvent.free")
                                                @else
                                                {{ money($ticket['full_price'], $event->currency) }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                                @if($order_total > 0)
                                <div class="card-footer">
                                    <h5>
                                        @lang("Public_ViewEvent.total"): <span style="float: right;"><b>{{ $orderService->getOrderTotalWithBookingFee(true) }}</b></span>
                                    </h5>
                                    @if($event->organiser->charge_tax)
                                    <h5>
                                        {{ $event->organiser->tax_name }} ({{ $event->organiser->tax_value }}%):
                                        <span style="float: right;"><b>{{ $orderService->getTaxAmount(true) }}</b></span>
                                    </h5>
                                    <h5>
                                        <strong>@lang("Public_ViewEvent.grand_total")</strong>
                                        <span style="float: right;"><b>{{  $orderService->getGrandTotal(true) }}</b></span>
                                    </h5>
                                    @endif
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <hr class="my-5">
                    </div>

                    <div style="text-align:left; ">

                        <h4> @lang("Public_ViewEvent.your_information")</h4>

                    </div>
                   
                    <div class="event_order_form">
                        {!! Form::open(['url' => route('postCreateOrder', ['event_id' => $event->id]), 'id' => 'payment-form',  'class' => ($order_requires_payment && @$payment_gateway->is_on_site) ? 'ajax' : 'ajax', 'data-stripe-pub-key' => isset($account_payment_gateway->config['publishableKey']) ? $account_payment_gateway->config['publishableKey'] : '']) !!}
                        {{-- {!! Form::hidden("asientos_ids", $ticket['asientos_ids']) !!}  --}}
                        {!! Form::hidden('event_id', $event->id) !!}
        
                            <div class="row mb-3"  style="text-align:left; ">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        {!! Form::label("order_first_name", trans("Public_ViewEvent.first_name")) !!}
                                        {!! Form::text("order_first_name", null, ['required' => 'required', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        {!! Form::label("order_last_name", trans("Public_ViewEvent.last_name")) !!}
                                        {!! Form::text("order_last_name", null, ['required' => 'required', 'class' => 'form-control']) !!}
                                    </div>
                                </div> 
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        {!! Form::label("order_email", trans("Public_ViewEvent.email_address")) !!}
                                        {!! Form::text("order_email", null, ['required' => 'required', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="p20 pl0">
                                        <a href="javascript:void(0);" class="btn btn-primary btn-xs" id="mirror_buyer_info">
                                            <svg width="25px" height="25px" viewBox="0 0 24 24" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M21 8C21 6.34315 19.6569 5 18 5H10C8.34315 5 7 6.34315 7 8V20C7 21.6569 8.34315 23 10 23H18C19.6569 23 21 21.6569 21 20V8ZM19 8C19 7.44772 18.5523 7 18 7H10C9.44772 7 9 7.44772 9 8V20C9 20.5523 9.44772 21 10 21H18C18.5523 21 19 20.5523 19 20V8Z" fill="#ffffff"/>
                                            <path d="M6 3H16C16.5523 3 17 2.55228 17 2C17 1.44772 16.5523 1 16 1H6C4.34315 1 3 2.34315 3 4V18C3 18.5523 3.44772 19 4 19C4.55228 19 5 18.5523 5 18V4C5 3.44772 5.44772 3 6 3Z" fill="#ffffff"/>
                                            </svg>
                                            @lang("Public_ViewEvent.copy_buyer")
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="row pt-5">
                                <div class="col-md-12">
                                    <div class="ticket_holders_details text-right" style="text-align:left; "  >
                                        <h4>@lang("Public_ViewEvent.ticket_holder_information")</h4>
                                        <?php
                                            $total_attendee_increment = 0;
                                        ?>
                                        @foreach($tickets as $ticket)
                                            @for($i=0; $i<=$ticket['qty']-1; $i++)
                                                <div class="card mb-3">

                                                    <div class="card-header">
                                                        <h5 class="card-title" style="text-align: start;">
                                                            <b>{{$ticket['ticket']['title']}}</b>: @lang("Public_ViewEvent.ticket_holder_n", ["n"=>$i+1])
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4 col-12">
                                                                <div class="form-group">
                                                                    {!! Form::label("ticket_holder_first_name[{$i}][{$ticket['ticket']['id']}]", trans("Public_ViewEvent.first_name")) !!}
                                                                    {!! Form::text("ticket_holder_first_name[{$i}][{$ticket['ticket']['id']}]", null, ['required' => 'required', 'class' => "ticket_holder_first_name.$i.{$ticket['ticket']['id']} ticket_holder_first_name form-control"]) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-12">
                                                                <div class="form-group">
                                                                    {!! Form::label("ticket_holder_last_name[{$i}][{$ticket['ticket']['id']}]", trans("Public_ViewEvent.last_name")) !!}
                                                                    {!! Form::text("ticket_holder_last_name[{$i}][{$ticket['ticket']['id']}]", null, ['required' => 'required', 'class' => "ticket_holder_last_name.$i.{$ticket['ticket']['id']} ticket_holder_last_name form-control"]) !!}
                                                                </div>
                                                            </div> 
                                                            <div class="col-md-4 col-12">
                                                                <div class="form-group">
                                                                    {!! Form::label("ticket_holder_email[{$i}][{$ticket['ticket']['id']}]", trans("Public_ViewEvent.email_address")) !!}
                                                                    {!! Form::text("ticket_holder_email[{$i}][{$ticket['ticket']['id']}]", null, ['required' => 'required', 'class' => "ticket_holder_email.$i.{$ticket['ticket']['id']} ticket_holder_email form-control"]) !!}
                                                                </div>
                                                            </div>
                                                            @include('Public.ViewEvent.Partials.AttendeeQuestions', ['ticket' => $ticket['ticket'],'attendee_number' => $total_attendee_increment++])

                                                        </div>

                                                    </div>
            
                                                </div>
                                            @endfor
                                        @endforeach
                                    </div>
                                </div>
                            </div>
        
                            <div>
                                <hr class="my-5">
                            </div>


                            <style>
                                .offline_payment_toggle {
                                    padding: 20px 0;
                                }
                            </style>

                            @if($order_requires_payment)

                                <div class="row">
                                    <div class="col-12">
                                        <div>

                                            <h4>@lang("Public_ViewEvent.payment_information")</h4>
                                                @lang("Public_ViewEvent.below_payment_information_header")

                                        </div>
                                        @if($event->enable_offline_payments)
                                            <div class="offline_payment_toggle">
                                                <div class="custom-checkbox" style="display: inline-flex;">
                                                    @if($payment_gateway === false)
                                                        {{--  Force offline payment if no gateway  --}}
                                                        <input type="hidden" name="pay_offline" value="1">
                                                        <input id="pay_offline" type="checkbox" value="1" checked disabled>
                                                    @else
                                                        <input data-toggle="toggle" id="pay_offline" name="pay_offline" type="checkbox" value="1">
                                                    @endif
                                                    <label class="ml-2" for="pay_offline">@lang("Public_ViewEvent.pay_using_offline_methods")</label>
                                                </div>
                                            </div>
                                            <div class="offline_payment" style="display: none;">
                                                <h5>@lang("Public_ViewEvent.offline_payment_instructions")</h5>
                                                <div class="well">
                                                    {!! Markdown::parse($event->offline_payment_instructions) !!}
                                                </div>
                                            </div>

                                        @endif
            
                                        @if(@$payment_gateway->is_on_site)

                                            <div class="online_payment p-4">

                                                @includeIf('Public.Openpay.card', ['some' => 'data'])
                        
                                            </div>

                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($event->pre_order_display_message)
                                <div class="well well-small">
                                    {!! nl2br(e($event->pre_order_display_message)) !!}
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-12">
                                    <div class="card border-0">
                                        <div class="card-body">
                                            {!! Form::hidden('is_embedded', $is_embedded) !!}
                                            {!! Form::submit(trans("Public_ViewEvent.checkout_submit"), ['id' => 'pay-button', 'class' => 'btn btn-lg btn-success card-submit', 'style' => 'width:100%;']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <img src="https://cdn.attendize.com/lg.png" />
</section>
@if(session()->get('message'))
    <script>showMessage('{{session()->get('message')}}');</script>
@endif

