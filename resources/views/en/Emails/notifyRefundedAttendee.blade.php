@extends('en.Emails.Layouts.Master')

@section('message_content')

    <p>Hola,</p>
    <p>
        Ha recibido un reembolso en nombre de su billete cancelado por <b>{{{$attendee->event->title}}}</b>.
        <b>{{{ $refund_amount }}} ha sido reembolsado al beneficiario original, debería ver el pago en unos días.</b>
    </p>

    <p>
        Puedes contactar <b>{{{ $attendee->event->organiser->name }}}</b> directamente en <a href='mailto:{{{$attendee->event->organiser->email}}}'>{{{$attendee->event->organiser->email}}}</a> o respondiendo a este correo electrónico si necesita más información.
    </p>
@stop

@section('footer')

@stop