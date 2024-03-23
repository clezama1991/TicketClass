@extends('Emails.Layouts.Master2')

@section('message_content')

<table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
    <tbody>
        <tr>
            <td bgcolor="#FFFFFF">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <th colspan="5" style="font-size:20px;color:#fcfcfc;background:#a89dff;padding:5px 0">
                                Evento</th>
                        </tr>
                        <tr>
                            <td colspan="5" style="font-size:0;line-height:0" height="5">
                                &nbsp;</td>
                        </tr>
                        <tr>
                            <th colspan="1" align="right"
                                style="text-align:center;width:25%;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                                Nombre:</th>
                            <td colspan="4"
                                style="background:#f3f3f3;padding-top:5px;padding-left:15px;padding-bottom:5px;border-right:1px solid #f3f3f3">
                                <span id="m_7393488360502340397fullname">{{$event->title}}</span>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="1" align="right"
                                style="text-align:center;width:25%;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                                Fecha:</th>
                            <td colspan="4"
                                style="background:#f3f3f3;padding-top:5px;padding-left:15px;padding-bottom:5px;border-right:1px solid #f3f3f3">
                                <span id="m_7393488360502340397fullname">{{$event->start_date}}</span>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="1" align="right"
                                style="text-align:center;width:25%;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                                Lugar:</th>
                            <td colspan="4"
                                style="background:#f3f3f3;padding-top:5px;padding-left:15px;padding-bottom:5px;border-right:1px solid #f3f3f3">
                                <span id="m_7393488360502340397fullname">{!! nl2br($event->getFullAddressAttribute())
                                    !!}</span>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="height: 20px"></td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <th colspan="5" style="font-size:20px;color:#fcfcfc;background:#a89dff;padding:5px 0">
                                Boletos</th>
                        </tr>
                        <tr>
                            <td colspan="5" style="font-size:0;line-height:0" height="5">
                                &nbsp;</td>
                        </tr>
                        <tr>
                            <th colspan="1" align="right"
                                style="text-align:center;width:25%;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                                Nº de Reservación:</th>
                            <td colspan="4"
                                style="background:#f3f3f3;padding-top:5px;padding-left:15px;padding-bottom:5px;border-right:1px solid #f3f3f3;border-bottom:1px solid #f3f3f3">
                                <span id="m_7393488360502340397billing_cycles">{{$order->order_reference}}</span>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="1" align="right"
                                style="text-align:center;width:25%;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                                Compra:</th>
                            <td colspan="4"
                                style="background:#f3f3f3;padding-top:5px;padding-left:15px;padding-bottom:5px;border-right:1px solid #f3f3f3;border-bottom:1px solid #f3f3f3">
                                <span
                                    id="m_7393488360502340397billing_cycles">{{$order->created_at->format(config('attendize.default_datetime_format'))}}</span>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="1" align="right"
                                style="text-align:center;width:25%;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                                Detalles:</th>
                            <td colspan="4"
                                style="background:#f3f3f3;padding-top:5px;padding-left:15px;padding-bottom:5px;border-right:1px solid #f3f3f3">

                                <table class="table table-hover" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th
                                                style="text-align:center;text-align: center;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                                                Zona</th>
                                            <th
                                                style="text-align:center;text-align: center;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                                                Referencia</th>
                                            <th
                                                style="text-align:center;text-align: center;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                                                Asiento</th>
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

                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="height: 20px"></td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <th colspan="5" style="font-size:20px;color:#fcfcfc;background:#a89dff;padding:5px 0">
                                Datos del cliente</th>
                        </tr>
                        <tr>
                            <td colspan="5" style="font-size:0;line-height:0" height="5">
                                &nbsp;</td>
                        </tr>
                        <tr>
                            <th colspan="1" align="right"
                                style="text-align:center;width:25%;font-weight:normal;color:#fcfcfc;background:#a89dff;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #9085e5">
                                Nombre:</th>
                            <td colspan="4"
                                style="background:#f3f3f3;padding-top:5px;padding-left:15px;padding-bottom:5px;border-right:1px solid #f3f3f3">
                                <span id="m_7393488360502340397fullname">{{ $attendee->first_name }} {{
                                    $attendee->last_name }} </span>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table align="right" border="0" cellpadding="0" cellspacing="0" width="60%" bgcolor="#FFFFFF"
                    style="padding:10px 30px 5px 30px">
                    <tbody>
                        <tr>
                            <td align="right"
                                style="font-size:25px;color:#1a1a1a;padding-bottom:5px;border-bottom:1px solid #a89dff">
                                <b>Total Pagado</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="font-size:0;line-height:0" height="20">
                                &nbsp;</td>
                        </tr>
                        <tr>
                            <td align="right" style="color:#a89dff;font-size:25px">
                                <b>${{ $order->amount }}</b>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="display:block;border-top:1px solid #7799ea;margin:20px 30px 0px 30px">
            </td>
        </tr>

        <tr>
            <td>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff"
                    style="padding:20px 30px 20px 30px">
                    <tbody>
                        <tr>
                            <td style="font-weight:400;text-align: center;">
                             
                            ESTE CORREO CONTIENE TUS BOLETOS DIGITALES, DESCÁRGALOS PARA PODER ACCEDER AL EVENTO.
                        
                        </td>

                        </tr>
                        <tr>
                            <td style="height: 20px"></td>
                        </tr>
                        <tr>
                            <td style="font-weight:400;text-align: center;"> 

                            </td>

                        </tr>

                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff"
                    style="padding:20px 30px 20px 30px">
                    <tbody>
                        <tr>
                            <td style="font-weight:400;text-align: center;">
                               Si no se adjunto el boleto digital puedes descargarlo en el siguiente enlace
                            </td>

                        </tr>
                        <tr>
                            <td style="height: 20px"></td>
                        </tr>
                        <tr>
                            <td style="font-weight:400;text-align: center;"> 

                                <a class="ticket_download_link"
                                href="{{ route('showOrderTickets', ['order_reference' => $order->order_reference] ).'?download=1' }}">
                                    {{ @trans("Public_ViewEvent.download_tickets") }}</a>  
                                    
                            </td>

                        </tr>

                    </tbody>
                </table>
            </td>
        </tr>

    </tbody>
</table>

@stop

@section('footer')

@stop