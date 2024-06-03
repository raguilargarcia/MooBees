@extends('layouts.app')

@section('content')
<h1>Iniciar sesión</h1>

@if ($errors->any())
<div>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('iniciar-sesion') }}">
    @csrf
    <div>
        <label for="username">Nombre de usuario</label>
        <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
    </div>

    <div>
        <label for="password">Contraseña</label>
        <input id="password" type="password" name="password" required>
    </div>

    <div>
        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        <label for="remember">Recordar sesión</label>
    </div>

    <div>
        <button type="submit">Iniciar sesión</button>
    </div>
</form>

<!--Si el usuario no está registrado, aparecera en el header la opción de registrasre-->
@guest
    <a href="{{ route('registro') }}">Registrarse</a>
@endguest

Volver al <a href="/">inicio</a>
@endsection

