@extends('en.Emails.Layouts.Master')

@section('message_content')

<p>Hola, {{$first_name}}</p>
<p>
    Gracias por registrarse para {{ config('attendize.app_name') }}. Estamos encantados de tenerte a bordo.
</p>

<p>
    Puede crear su primer evento y confirmar su correo electrónico utilizando el enlace a continuación.
</p>

<div style="padding: 5px; border: 1px solid #ccc;">
   {{route('confirmEmail', ['confirmation_code' => $confirmation_code])}}
</div>
<br><br>
<p>
    Si tiene alguna pregunta, comentario o sugerencia, no dude en responder a este correo electrónico.
</p>
<p>
    Gracias
</p>

@stop

@section('footer')


@stop
