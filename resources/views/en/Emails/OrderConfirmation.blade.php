@extends('en.Emails.Layouts.Master')

@section('message_content')
Hola,<br><br>

Tu pedido para el evento <b>{{$order->event->title}}</b> Fue exitosa.<br><br>

Sus entradas se adjuntan a este correo electrónico. También puede ver los detalles de su pedido y descargar sus boletos en:{{route('showOrderDetails', ['order_reference' => $order->order_reference])}}


<h3>Detalles del pedido</h3>
Referencia: <b>{{$order->order_reference}}</b><br>
Nombre del pedido: <b>{{$order->full_name}}</b><br>
Fecha del pedido: <b>{{$order->created_at->format(config('attendize.default_datetime_format'))}}</b><br>
Correo electrónico del pedido: <b>{{$order->email}}</b><br>

<h3>Detalles</h3>
<div style="padding:10px; background: #F9F9F9; border: 1px solid #f1f1f1;">
    <table style="width:100%; margin:10px;">
        <tr>
            <td>
                <b>Ticket</b>
            </td>
            <td>
                <b>Cantidad</b>
            </td>
            <td>
                <b>Precio</b>
            </td>
            <td>
                <b>Servicio</b>
            </td>
            <td>
                <b>Total</b>
            </td>
        </tr>
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
                LIBRE
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
            <td>
                @if((int)ceil($order_item->unit_price) == 0)
                LIBRE
                @else
                {{money(($order_item->unit_price + $order_item->unit_booking_fee) * ($order_item->quantity),
                $order->event->currency)}}
                @endif

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
                <b>Sub Total</b>
            </td>
            <td colspan="2">
                {{$orderService->getOrderTotalWithBookingFee(true)}}
            </td>
        </tr>
        @if($order->event->organiser->charge_tax == 1)
        <tr>
            <td>
            </td>
            <td>
            </td>
            <td>
            </td>
            <td>
                <b>{{$order->event->organiser->tax_name}}</b>
            </td>
            <td colspan="2">
                {{$orderService->getTaxAmount(true)}}
            </td>
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
            <td colspan="2">
                {{$orderService->getGrandTotal(true)}}
            </td>
        </tr>
    </table>

    <br><br>
</div>
<br><br>
Gracias
@stop