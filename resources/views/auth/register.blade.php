@extends('layouts.app')

@section('content')
<div class="container register-page">
    <h1>Registro</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('registro') }}">
        @csrf
        <div class="form-group">
            <label for="name">Nombre</label>
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="username">Nombre de usuario</label>
            <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </div>
    </form>

    <p class="mt-3">Volver al <a href="/">inicio</a></p>
</div>
@endsection
