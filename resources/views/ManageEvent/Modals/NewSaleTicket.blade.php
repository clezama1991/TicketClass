<div role="dialog"  class="modal fade " style="display: none; ">
   {!! Form::open(array('url' => route('postSalesTickets', array('ticket_id' => $ticket_id)), 'class' => 'ajax', 'id' => 'FormpostSalesTickets')) !!}
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-ticket"></i>
                    Ventas de Tickets: {{ $detalles->title }}
                </h3>
            </div>
            <div class="modal-body" style="overflow: auto !important;" >
                <div class="row">
                    <div class="col-md-12">
                        <h4>
                            Asientos
                            <span class="h6 text-muted"> ({{money($detalles->price, $detalles->event->currency)}})</span>
                        </h4>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="ticket_id" value="{{ $ticket_id }}" id="ticket_id">
                        
                        <div class="row">
                            <div class="col-md-12">

                                <div class="row m-5">
                                    @if($detalles->select_seat==1)                            
                                        <div class="col-md-12" style="display: flex;justify-content: center; margin-bottom: 15px; ">
                                            <img src="{{ asset('assets/images/select_seat/stage.jpg') }}" alt="selected" alt="">
                                           
                                            <br>
                                        </div>
                                        <input type="hidden" id="quantity" name="quantity" value="0">

                                    @else
                                        <div class="col-md-12">
                                            @if($detalles->is_paused)

                                            <span class="text-danger">
                                                @lang("Public_ViewEvent.currently_not_on_sale")
                                            </span>
            
                                            @else
            
                                            @if($detalles->sale_status === config('attendize.ticket_status_sold_out'))
                                            <span class="text-danger" property="availability" content="http://schema.org/SoldOut">
                                                @lang("Public_ViewEvent.sold_out")
                                            </span>
                                            @elseif($detalles->sale_status === config('attendize.ticket_status_before_sale_date'))
                                            <span class="text-danger">
                                                @lang("Public_ViewEvent.sales_have_not_started")
                                            </span>
                                            @elseif($detalles->sale_status === config('attendize.ticket_status_after_sale_date'))
                                            <span class="text-danger">
                                                @lang("Public_ViewEvent.sales_have_ended")
                                            </span>
                                            @else
                                           
                                            {!! Form::label('quantity', "Cantidad de Ticket", array('class'=>'control-label required')) !!}
                                            {!! Form::select('quantity', array(0=>0, 1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,30=>30,50=>50,100=>100), null, ['class' => 'form-control']) !!}
                                            @endif
            
                                            @endif

                                        </div>
                                    @endif

                                    @php
                                    $termina = 1;
                                    $key_abecedario = 0;
                                    $ticket_seats = $detalles->seats->groupBy('row');
                                    
                                    @endphp
                                    
                                    <div class="col-md-12"> 
                                        <div class="row"> 
                                            <div class="col-md-3">
                                                <h5>Filas</h5>
                                            </div>
                                            <div class="col-md-9">
                                                <h5>Asientos</h5>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach ($ticket_seats as $key_file => $seats)
                                        @php
                                            $titulo_fila = $abecedario[$key_abecedario];
                                        @endphp
                                        <div class="col-md-12">
                                            
                                            <div class="seats__grid">
                                                <ul class="nostyle seats__row " style="list-style:none;padding:0;margin:0;display: flex;justify-content: center;">
                                                    <li class="seat" style="font-weight: bolder;width: 30px;position: absolute;left: 20px;">{{ $titulo_fila }}</li>

                                                    @foreach ($seats as $t => $seat)
                                                        @if ($key_file == $seat->row)

                                                            @if ($seat->is_available != 2) 
                                                                <li class="seat seatSelect {{ $seat->is_available == 3 ? "btns-danger": ($seat->is_available == 0 ? "btns-warning" : "btns-info") }}" style="width: 25px; padding-left: 1px;"
                                                                    id="{{ $seat->id }}" 
                                                                            data-asiento="{{ $seat->seat() }}"
                                                                            data-ticket="{{ $detalles->id }}" 
                                                                            data-seatzone="{{ $detalles->seat_zone }}"
                                                                            title="{{ $seat->seat() }}"
                                                                            {{ $seat->is_available == 3 ? "disabled": "" }}
                                                                        >
                                                                            <svg class="icon-seat" viewBox="0 0 24 20">
                                                                                <path fill="{{ $seat->is_available == 3 ? "#eb2727": ($seat->is_available == 0 ? "#FFD66A": "#5495D2")  }}" class="st0 asiento_{{$seat->id}}" d="M22.7,0H21c-0.7,0-1.3,0.6-1.3,1.4v0.4v2.6c0-2-1.6-3.7-3.6-3.7H8c-2,0-3.6,1.7-3.6,3.7V1.8V1.4 C4.4,0.6,3.8,0,3,0H1.3C0.6,0,0,0.6,0,1.4v0.4v11.3v0.5c0,1,0.8,1.8,1.8,1.8h0.1c0.1,0,0.2,0,0.3,0h0.2l0,0H3v2.7 C3,19.1,3.8,20,4.9,20h14.3c1.1,0,1.9-0.9,1.9-1.9v-2.7h0.6l0,0h0.8c0.9-0.1,1.5-0.9,1.5-1.8v-0.5V1.8V1.4C24,0.6,23.4,0,22.7,0z"></path>
                                                                            </svg>
                                                                            <span class="seat__num" aria-label="C10">{{$seat->column}}</span>
                                                                </li>
                                                                <input type="hidden" name="sillas[]" id="silla{{ $seat->id }}">
                                                            @else
                                                            
                                                                <li class="seat seatSelect {{ $seat->is_available == 3 ? "btns-danger": ($seat->is_available == 0 ? "btns-warning" : "btns-info") }}" style="width: 25px; padding-left: 1px;"
                                                                    id="{{ $seat->id }}" 
                                                                    data-asiento="{{ $seat->seat() }}"
                                                                    data-ticket="{{ $detalles->id }}" 
                                                                    data-seatzone="{{ $detalles->seat_zone }}"
                                                                    title="{{ $seat->seat() }}"
                                                                    {{ $seat->is_available == 3 ? "disabled": "" }}
                                                                    >
                                                                    <svg class="icon-seat" viewBox="0 0 24 20">
                                                                        <path fill="{{ $seat->is_available == 3 ? "#eb2727": ($seat->is_available == 0 ? "#FFD66A": "#5495D2")  }}" class="st0 asiento_{{$seat->id}}" d="M22.7,0H21c-0.7,0-1.3,0.6-1.3,1.4v0.4v2.6c0-2-1.6-3.7-3.6-3.7H8c-2,0-3.6,1.7-3.6,3.7V1.8V1.4 C4.4,0.6,3.8,0,3,0H1.3C0.6,0,0,0.6,0,1.4v0.4v11.3v0.5c0,1,0.8,1.8,1.8,1.8h0.1c0.1,0,0.2,0,0.3,0h0.2l0,0H3v2.7 C3,19.1,3.8,20,4.9,20h14.3c1.1,0,1.9-0.9,1.9-1.9v-2.7h0.6l0,0h0.8c0.9-0.1,1.5-0.9,1.5-1.8v-0.5V1.8V1.4C24,0.6,23.4,0,22.7,0z"></path>
                                                                    </svg>
                                                                    <span class="seat__num" aria-label="C10">{{$seat->column}}</span>
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
                                        @php
                                            $key_abecedario++;
                                        @endphp
                                    @endforeach
                                </div>     
                            </div>     
                        </div>   
                        <hr>
                        
                        @if( $detalles->select_seat == 1)
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Compra</h4>
                                </div>
                                <div class="col-md-3">
                                    <h5>Nro Asientos Apartados: </h5>
                                    <div class="h5">&nbsp;<span id="set_quantity">0</span></div>
                                </div>
                                <div class="col-md-3">
                                    <h5>Asientos Apartados: </h5>
                                    <div class="h5">&nbsp;<span id="set_asiento"></span></div>
                                </div>
                                <div class="col-md-3">
                                    <h5>Asientos Apartados: </h5>
                                    <div class="h5">
                                        <div class="form-group">
                                            <select class="form-control payment_method" name="payment_method" id="payment_method"> 
                                                @foreach (payment_methods() as $item)
                                                    <option value="{{$item['id']}}" {{'cash'==$item['id'] ? 'selected' : ''}}> {{$item['name']}}</option>                    
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input type="hidden"  id="total_pagar2" value="{{ $price }}">
                                    <h5>Total a Cobrar: </h5>
                                    <div class="h5">&nbsp;$<span id="total_pagar">0</span></div>
                                </div>
                            </div> 
                        @else
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Compra</h4>
                                </div>
                                <div class="col-md-3">
                                    <h5>Nro Tickets Apartados: </h5>
                                    <div class="h5">&nbsp;<span id="set_quantity_gen">0</span></div>
                                </div>
                                <div class="col-md-3">
                                    <h5>Asientos Apartados: </h5>
                                    <div class="h5">&nbsp;<span id="">N/A</span></div>
                                </div>
                                <div class="col-md-3">
                                    <h5>Metodo de Pago: </h5>
                                    <div class="h5">
                                        <div class="form-group"> 
                                          <select class="form-control payment_method" name="payment_method" id="payment_method">
                                            @foreach (payment_methods() as $item)
                                                <option value="{{$item['id']}}" {{'cash'==$item['id'] ? 'selected' : ''}}> {{$item['name']}}</option>                    
                                            @endforeach  
                                          </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3  {{ $detalles->select_seat == 1 ? "" : "" }}">
                                    <input type="hidden"  id="total_pagar2" value="{{ $price }}">
                                    <h5>Total a Cobrar: </h5>
                                    <div class="h5">&nbsp;$<span id="total_pagar">0</span></div>
                                </div>
                            </div> 
                        @endif
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Asistente</h4>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                {!! Form::label('first_name', trans("Attendee.first_name"), array('class'=>'control-label required')) !!}

                                {!!  Form::text('first_name', Input::old('first_name'),
                                            array(
                                            'class'=>'form-control'
                                            ))  !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                {!! Form::label('last_name', trans("Attendee.last_name"), array('class'=>'control-label')) !!}

                                {!!  Form::text('last_name', Input::old('last_name'),
                                            array(
                                            'class'=>'form-control'
                                            ))  !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                        {!! Form::label('email', trans("Attendee.email_address"), array('class'=>'control-label required')) !!}

                                        {!!  Form::text('email', Input::old('email'),
                                                            array(
                                                            'class'=>'form-control'
                                                            ))  !!}
                                </div>
                                <div class="form-group">
                                    <div class="checkbox custom-checkbox">
                                        <input type="checkbox" name="email_ticket" id="email_ticket" value="1" />
                                        <label for="email_ticket">&nbsp;&nbsp; ¿Enviar la Entrada al correo?</label>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit("Procesar Venta", ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
       <input type="hidden" id="rutaAjax" value="{{ route('postAvertSeat') }}">
    </div>
</div>
{!!HTML::style('assets/stylesheet/select-seat.css')!!}

<script>

    var valor = $("#total_pagar2").val();
    
    var asientos = [];
    var asientos_id = [];

    setTimeout(() => {
        var cant = $(".btns-warning").length;
        $("#quantity").val(cant);
        $("#set_quantity").html(cant);
        $("#total_pagar").html(valor*cant);
        $( ".btns-warning" ).each(function( index ) {         
            var asiento_saved = $(this).data("asiento");
            var id_saved = $(this).attr("id");
            asientos.push(asiento_saved);
            asientos_id.push(id_saved);
             $("#silla"+id_saved).val(id_saved);
        }); 
        $("#set_asiento").html(asientos.toString());
    }, 500);


    $(".seatSelect").on('click', function (e)
    {
        e.preventDefault();
        
        if($(this).hasClass("btns-danger"))
        {
            return false;
        }
        
        var id = $(this).attr("id");
        var asiento = $(this).data("asiento");
        var remove, add, status, set_asientos;

        if($(this).hasClass("btns-info"))
        {
            remove = "btns-info";
            add = "btns-warning";
            status = 0;
            asientos.push(asiento);
            asientos_id.push(id);
                $(".asiento_"+id).attr('fill','#FFD66A');
        }
        else
        {
                $(".asiento_"+id).attr('fill','#5495D2');
            remove = "btns-warning";
            add = "btns-info";
            status = 1;
            asientos = asientos.filter(function(item) {
                return item !== asiento
            })
            asientos_id = asientos_id.filter(function(item) {
                return item !== id
            })
        }

        $.ajax({
            type: "POST",
            url: `${$('#rutaAjax').val()}`,
            data: {"id": id, "status": status},
            success: function(data) {
                console.log(data.status);
                switch (data.status) {
                    case 'success':
                        $("#silla"+id).val(id);
                        $("#"+id).removeClass(remove).addClass(add);
                        break;
                    case 'error1':
                        /* Error */
                        $("#silla"+id).val("");
                        $("#"+id).removeClass(remove).addClass(add);
                        break;
                    case 'error2':
                        /* Error */
                        $("#"+id).removeClass(remove).addClass(add);
                        break;
                    default:
                        break;
                }
                
                $("#set_asiento").html(asientos.toString());
                var cant = $(".btns-warning").length;
                $("#quantity").val(cant);
                $("#set_quantity").html(cant);
                $("#total_pagar").html(valor*cant);
                showMessage(data.message);
            },
            error:function(x,xs,xt){
                //nos dara el error si es que hay alguno
                showMessage(JSON.stringify(x));
                //window.open(JSON.stringify(x));
                //alert('error: ' + JSON.stringify(x) +"\n error string: "+ xs + "\n error throwed: " + xt);
            }
        });
    });

    $('#FormpostSalesTickets').on('submit', function(e){
        console.log(asientos);
        if(asientos.length > 0){
            $(this).append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'asientos_marcados')
                .val(asientos_id)
            );
        }
    });

    $("#choose_seat").on('change', function (e)
    {
        e.preventDefault();
        if($(this).prop('checked'))
        {
            $("#asientos").fadeIn().removeClass("hide");
            $("#cantAsientos").addClass("hide") ;
        }
        else
        {
            $("#asientos").fadeOut().addClass("hide");
            $("#cantAsientos").removeClass("hide") ;
        }
    });

    $("#quantity").on('change', function (e)
    {
        var valor = $("#total_pagar2").val();
        var cant =$(this).val();
        var payment_method = $('.payment_method').val();
        var total_pay = valor*cant;

        if(payment_method=='card'){ 
            total_pay = (total_pay) * 1.045;
        }
        if(payment_method=='free'){ 
            total_pay = (total_pay) * 0;
        }
  
        total_pay = parseFloat(total_pay).toFixed(2);
        $("#total_pagar").html(total_pay);
        $("#set_quantity_gen").html(cant);
  
    });

    $(".payment_method").on('change', function (e)
    {
        var valor = $("#total_pagar2").val();
        var cant = $("#quantity").val();
        var payment_method = $('.payment_method').val();
        var total_pay = valor*cant;

        if(payment_method=='card'){ 
            total_pay = (total_pay) * 1.045;
        }
        if(payment_method=='free'){ 
            total_pay = (total_pay) * 0;
        }
  
  
        total_pay = parseFloat(total_pay).toFixed(2);
        $("#total_pagar").html(total_pay);
   
    });
 


</script>
