@if ($detalles->select_seat == 1)
    <style>
        .modal-dialog {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        }

        .modal-content {
        height: auto;
        min-height: 100%;
        border-radius: 0;
        }
    </style>
@endif


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
                        <input type="hidden" name="ticket_id" value="{{ $ticket_id }}" id="ticket_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                {!! Form::label('first_name', trans("Attendee.first_name"), array('class'=>'control-label required')) !!}

                                {!!  Form::text('first_name', Input::old('first_name'),
                                            array(
                                            'class'=>'form-control'
                                            ))  !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                {!! Form::label('last_name', trans("Attendee.last_name"), array('class'=>'control-label')) !!}

                                {!!  Form::text('last_name', Input::old('last_name'),
                                            array(
                                            'class'=>'form-control'
                                            ))  !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="checkbox custom-checkbox">
                                            <input type="checkbox" name="email_ticket" id="email_ticket" value="1" />
                                            <label for="email_ticket">&nbsp;&nbsp; ¿Enviar la Entrada al correo?</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                        <div class="form-group">
                                                {!! Form::label('email', trans("Attendee.email_address"), array('class'=>'control-label required')) !!}

                                                {!!  Form::text('email', Input::old('email'),
                                                                    array(
                                                                    'class'=>'form-control'
                                                                    ))  !!}
                                        </div>
                                </div>
                        </div>

                        <div class="row {{ $detalles->select_seat == 1 ? "hide" : "" }} ">

                            <div class="col-md-6">
                                    <div id="cantAsientos" class="form-group">
                                        <div class="form-group">
                                            {!! Form::label('quantity', "Cantidad de Ticket", array('class'=>'control-label required')) !!}
                                            {!! Form::select('quantity', array(0=>0, 1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,30=>30,50=>50,100=>100), null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="" style="  " class="{{ $detalles->select_seat == 0 ? "hide" : "" }} ">
                                <input type="hidden" name="tipoVenta" value="{{ $detalles->select_seat }}">
                                @php
                                    $termina = 1;
                                @endphp
                                @foreach ($seats as $t => $seat)

                                    @if ($seat->is_available != 2)
                                            <a id="{{ $seat->id }}" data-asiento="{{ $seat->seat() }}" {{ $seat->is_available == 3 ? "disabled" : "" }}  class="btn btn-sm {{ $seat->is_available == 3 ? "btn-danger" :  ($seat->is_available == 0 ? "btn-warning" : "btn-info")   }}  seatSelect" style="margin-bottom: 2px"  href="javascript:void(0)" title="{{ "Fila: ".$seat->row." - Asiento: ".$seat->column }}">
                                                <i class="ico-ticket"></i>
                                            </a>
                                            <input type="hidden" name="sillas[]" id="silla{{ $seat->id }}">
                                    @else
                                            <a   class="btn btn-sm  btn-light" disabled style="margin-bottom: 2px"  href="javascript:void(0)" title="{{ "Fila: ".$seat->row." - Asiento: ".$seat->column }}">
                                                 <i class="ico-ticket"></i>
                                            </a>
                                    @endif
                                    @if ($seat->row != $termina )
                                            <br>
                                    @endif
                                    @php
                                       $termina = $seat->row;
                                    @endphp
                                @endforeach

                        </div>

                        <div class="row">
                            <div class="col-md-4 {{ $detalles->select_seat == 1 ? "" : "hide" }}">
                                 <h3>Nro Asientos Apartados: &nbsp;<span id="set_quantity">0</span></h3>
                            </div>
                            <div class="col-md-4 {{ $detalles->select_seat == 1 ? "" : "hide" }}">
                                 <h3>Asientos Apartados: </h3>
                                 <div class="h4">&nbsp;<span id="set_asiento"></span></div>
                            </div>
                            <div class="col-md-4  {{ $detalles->select_seat == 1 ? "" : " col-md-offset-4" }}">
                                <input type="hidden"  id="total_pagar2" value="{{ $price }}">
                                <h3>Total a Cobrar: &nbsp;$<span id="total_pagar">0</span></h3>
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

<script>

    var valor = $("#total_pagar2").val();
    setTimeout(() => {
        var cant = $(".btn-warning").length;
        $("#quantity").val(cant);
        $("#set_quantity").html(cant);
        $("#total_pagar").html(valor*cant);
        $( ".btn-warning" ).each(function( index ) {         
            var asiento_saved = $(this).data("asiento");
            var id_saved = $(this).attr("id");
            asientos.push(asiento_saved);
            asientos_id.push(id_saved);
        }); 
        $("#set_asiento").html(asientos.toString());
    }, 500);

    var asientos = [];
    var asientos_id = [];

    $(".seatSelect").on('click', function (e)
    {
        e.preventDefault();
        var id = $(this).attr("id");
        var asiento = $(this).data("asiento");
        var remove, add, status, set_asientos;

        if($(this).hasClass("btn-info"))
        {
            remove = "btn-info";
            add = "btn-warning";
            status = 0;
            asientos.push(asiento);
            asientos_id.push(id);
        }
        else
        {
            remove = "btn-warning";
            add = "btn-info";
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
                var cant = $(".btn-warning").length;
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
        $("#total_pagar").html(valor*cant);
    });
</script>
