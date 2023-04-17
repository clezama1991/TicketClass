@extends('en.Emails.Layouts.Master')

@section('message_content')

<p>Hola</p>
<p>
    Usted ha sido agregado a un {{ config('attendize.app_name') }} cuenta por {{$inviter->first_name.' '.$inviter->last_name}}.
</p>

<p>
    Puede iniciar sesión con los siguientes datos.<br><br>
    
    Usuario: <b>{{$user->email}}</b> <br>
    Contraseña: <b>{{$temp_password}}</b>
</p>

<p>
    Puede cambiar su contraseña temporal una vez que haya iniciado sesión.
</p>

<div style="padding: 5px; border: 1px solid #ccc;" >
   {{route('login')}}
</div>
<br><br>
<p>
    Si tiene alguna pregunta, responda a este correo electrónico.
</p>
<p>
    Gracias
</p>

@stop

@section('footer')


@stop
