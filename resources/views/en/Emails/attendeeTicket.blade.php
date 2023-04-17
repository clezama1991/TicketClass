Hola, {{{$attendee->first_name}}},<br><br>

Hemos adjuntado sus entradas a este correo electrónico.<br><br>

Puede ver la información de su pedido y descargar sus boletos en {{route('showOrderDetails', ['order_reference' => $attendee->order->order_reference])}} en cualquier momento.<br><br>

La referencia de su pedido es <b>{{$attendee->order->order_reference}}</b>.<br>

Gracias<br>

