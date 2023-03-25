@extends('Emails.Layouts.Master2')

@section('message_content')

<table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
    <tbody>       
    <tr>
        <td bgcolor="#FFFFFF">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody><tr>
                    <th colspan="5" style="font-size:20px;color:#fcfcfc;background:#a89dff;padding:5px 0">
                        Evento</th>
                </tr>
                <tr>
                    <td colspan="5" style="font-size:0;line-height:0" height="5">
                        &nbsp;</td>
                </tr>
                <tr>
                    <th colspan="1" align="right" style="text-align:center;width:25%;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                        Nombre:</th>
                    <td colspan="4" style="background:#f3f3f3;padding-top:5px;padding-left:15px;padding-bottom:5px;border-right:1px solid #f3f3f3">
                        <span id="m_7393488360502340397fullname">{{$event->title}}</span></td>
                </tr>
                <tr>
                    <th colspan="1" align="right" style="text-align:center;width:25%;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                        Fecha:</th>
                    <td colspan="4" style="background:#f3f3f3;padding-top:5px;padding-left:15px;padding-bottom:5px;border-right:1px solid #f3f3f3">
                        <span id="m_7393488360502340397fullname">{{$event->start_date}}</span></td>
                </tr>
                <tr>
                    <th colspan="1" align="right" style="text-align:center;width:25%;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                        Lugar:</th>
                    <td colspan="4" style="background:#f3f3f3;padding-top:5px;padding-left:15px;padding-bottom:5px;border-right:1px solid #f3f3f3">
                        <span id="m_7393488360502340397fullname">{!! nl2br($event->getFullAddressAttribute()) !!}</span></td>
                </tr>
               
            </tbody></table>
        </td>
    </tr>
    <tr>
        <td style="height: 20px"></td>
    </tr>
    <tr>
        <td bgcolor="#FFFFFF">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody><tr>
                    <th colspan="5" style="font-size:20px;color:#fcfcfc;background:#a89dff;padding:5px 0">
                        Boletos</th>
                </tr>
                <tr>
                    <td colspan="5" style="font-size:0;line-height:0" height="5">
                        &nbsp;</td>
                </tr>
                <tr>
                    <th colspan="1" align="right" style="text-align:center;width:25%;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                        Nº de Reservación:</th>
                    <td colspan="4" style="background:#f3f3f3;padding-top:5px;padding-left:15px;padding-bottom:5px;border-right:1px solid #f3f3f3;border-bottom:1px solid #f3f3f3">
                        <span id="m_7393488360502340397billing_cycles">{{$order->order_reference}}</span></td>
                </tr>
                <tr>
                    <th colspan="1" align="right" style="text-align:center;width:25%;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                        Compra:</th>
                    <td colspan="4" style="background:#f3f3f3;padding-top:5px;padding-left:15px;padding-bottom:5px;border-right:1px solid #f3f3f3;border-bottom:1px solid #f3f3f3">
                        <span id="m_7393488360502340397billing_cycles">{{$order->created_at->format(config('attendize.default_datetime_format'))}}</span></td>
                </tr>
                <tr>
                    <th colspan="1" align="right" style="text-align:center;width:25%;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                        Detalles:</th>
                    <td colspan="4" style="background:#f3f3f3;padding-top:5px;padding-left:15px;padding-bottom:5px;border-right:1px solid #f3f3f3">
                       
                        <table class="table table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th style="text-align:center;text-align: center;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">Zona</th>
                                    <th style="text-align:center;text-align: center;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">Referencia</th>
                                    <th style="text-align:center;text-align: center;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">Asiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->attendees as $key_order_item => $attendee)
                                <tr>
                                    <td style="text-align: center">
                                        {{{$attendee->ticket->title}}}
                                    </td>
                                    <td style="text-align: center">
                                        {{{$order->order_reference}}}-{{{$attendee->reference_index}}}
                                    </td>                                    
                                    @if($attendee->seats)
                                        <td style="text-align: center" nowrap>
                                            {{{$attendee->seats->seat()}}}
                                        </td>
                                    @endif  
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr> 
               
            </tbody></table>
        </td>
    </tr>
    <tr>
        <td style="height: 20px"></td>
    </tr>
    <tr>
        <td bgcolor="#FFFFFF">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody><tr>
                    <th colspan="5" style="font-size:20px;color:#fcfcfc;background:#a89dff;padding:5px 0">
                        Datos del cliente</th>
                </tr>
                <tr>
                    <td colspan="5" style="font-size:0;line-height:0" height="5">
                        &nbsp;</td>
                </tr>
                <tr>
                    <th colspan="1" align="right" style="text-align:center;width:25%;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                        Nombre:</th>
                    <td colspan="4" style="background:#f3f3f3;padding-top:5px;padding-left:15px;padding-bottom:5px;border-right:1px solid #f3f3f3">
                        <span id="m_7393488360502340397fullname">{{ $attendee->first_name }}  {{ $attendee->last_name }} </span></td>
                </tr>
               
            </tbody></table>
        </td>
    </tr>
    <tr>
        <td>
            <table align="right" border="0" cellpadding="0" cellspacing="0" width="60%" bgcolor="#FFFFFF" style="padding:10px 30px 5px 30px">
                <tbody><tr>
                    <td align="right" style="font-size:25px;color:#1a1a1a;padding-bottom:5px;border-bottom:1px solid #a89dff">
                        <b>TOTAL A PAGAR</b></td>
                </tr>
                <tr>
                    <td colspan="5" style="font-size:0;line-height:0" height="20">
                        &nbsp;</td>
                </tr>
                <tr>
                    <td align="right" style="color:#a89dff;font-size:25px">
                        <b>${{ $order->amount }}</b></td>
                </tr>
            </tbody></table>
        </td>
    </tr> 
</tbody></table>

<div style="display:none">

Estimado: {{ $attendee->first_name }}  {{ $attendee->last_name }} 

¡Tu pago referenciado ha sido recibido!


Número de Reservación:
{{$order->order_reference}}


ESTE CORREO CONTIENE LA INFORMACIÓN NECESARIA PARA QUE PUEDAS ACUDIR A CUALQUIER SUCURSAL TICKETMATICO PARA IMPRIMIR TUS BOLETOS



Evento: {{$event->title}}
Fecha: {{$event->start_date}}
Lugar: {{$event->location_address}}
Código Postal: {{$event->location_post_code}}
Ciudad: {{$event->location_state}}, {{$event->location_country}}
 
Zona: 
{{-- {{$event->sections_map()}} --}}

Lugares asignados: 
{{-- {{$event->sections_map()}} --}}

Cliente: {{ $attendee->first_name }}  {{ $attendee->last_name }} 
Total Pagado: ${{$order->amount}}

</div>
@stop

@section('footer')

@stop