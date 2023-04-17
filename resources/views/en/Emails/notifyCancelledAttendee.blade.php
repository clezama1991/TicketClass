@extends('en.Emails.Layouts.Master')

@section('message_content')

<p>Hola,</p>
<p>
    Tu entrada para el evento <b>{{{$attendee->event->title}}}</b> ha sido cancelado.
</p>

<p>
    Puedes contactar <b>{{{$attendee->event->organiser->name}}}</b> directamente en <a href='mailto:{{{$attendee->event->organiser->email}}}'>{{{$attendee->event->organiser->email}}}</a> o respondiendo a este correo electrónico si necesita más información.
</p>
@stop

@section('footer')

@stop
