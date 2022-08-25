Hello <i>{{ $demo->receiver }}</i>,
<p>Este correo fue enviado para reestablecer la contraseña del sistema Adidot.</p>

<p>Si usted no realizó dicha petición, puede ignorar este correo en su totalidad</p>

<div>
    <p><b>Haga click en el siguiente link para reestablecer su contraseña</p>
</div>
<div>
    <a><b>{{$demo->link}}</a>
</div>
<br />
Muchas gracias y feliz día,
<br />
<i>{{ $demo->sender }}</i>