<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MooBees</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Monoton&family=Tilt+Neon&display=swap" rel="stylesheet">

    <!-- CSS de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>


<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand text-warning" href="/">MooBees</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar Navegación">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">

                    <!--enlace para ir a la pagina la-colmena-moobees-->
                    
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="{{ route('showGame') }}">La Colmena</a>
                    </li>

                    <!--Si el usuario no está registrado, aparecera en el header la opción de registrarse-->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="{{ route('registro') }}">Registro</a>
                        </li>
                    @endguest

                    <!--Si el usuario no está registrado, aparecera en el header la opción de iniciar sesión-->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="{{ route('login') }}">Iniciar sesión</a>
                        </li>
                    @endguest

                    <!--Si el usuario está con una sesion iniciada, aparecera en el header la opción de perfil-->
                    @auth
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="{{ route('profile.show') }}">Perfil</a>
                        </li>
                    @endauth

                    @auth
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="{{ route('watchlists.index') }}">Watchlists</a>
                        </li>
                    @endauth

                    <!--Si el usuario está con una sesion iniciada, aparecera en el header la opción de cerrar sesión-->
                    @auth
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="{{ route('cerrar-sesion') }}">Cerrar sesión</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </nav>
    </header>

    <main class="d-flex justify-content-center align-items-center">
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