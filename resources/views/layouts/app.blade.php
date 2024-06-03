<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MooBees</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <!-- CSS de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/">MooBees</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar Navegación">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Inicio</a>
                    </li>

                <!--enlace para ir a la pagina la-colmena-moobees-->
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('showGame') }}">La Colmena</a>
                    </li>

            <!--Si el usuario no está registrado, aparecera en el header la opción de registrasre-->
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('registro') }}">Registro</a>
                </li>
            @endguest

            <!--Si el usuario no está registrado, aparecera en el header la opción de iniciar sesión-->
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('iniciar-sesion') }}">Iniciar sesión</a>
                </li>
            @endguest

            <!--Si el usuario está con una sesion iniciada, aparecera en el header la opción de perfil-->
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.show') }}">Perfil</a>
                </li>
            @endauth

            <!--Si el usuario está con una sesion iniciada, aparecera en el header la opción de cerrar sesión-->
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cerrar-sesion') }}">Cerrar sesión</a>
                </li>
            @endauth
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <!-- Puedes agregar elementos del pie de página aquí -->
        <p>&copy; {{ date('Y') }} MooBees</p>
    </footer>

    <!--<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>-->
    <script src="{{ asset('js/api.js') }}"></script>
</body>

</html>