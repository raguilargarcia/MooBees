@extends('layouts.app')

@section('content')
<div class="container login-page">
    <h1>Iniciar sesión</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('iniciar-sesion') }}">
        @csrf
        <div class="form-group">
            <label for="username">Nombre de usuario</label>
            <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="input-group">
                <input id="password" type="password" class="form-control" name="password" required>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Recordar sesión</label>
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </div>
    </form>

    <p class="mt-3">¿No tienes una cuenta? <a href="{{ route('registro') }}">Registrarse</a></p>
    <p>Volver al <a href="/">inicio</a></p>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const password = document.getElementById('password');
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
</script>
@endsection
