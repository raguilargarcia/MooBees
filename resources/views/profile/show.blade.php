@extends('layouts.app')

@section('content')
<div class="container profile-page">
    <h1>Perfil de {{ $user->username }}</h1>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Información principal del perfil -->
    <div class="profile-header text-center">
        <img src="{{ $user->profile_photo_path ? asset('images/profile/' . $user->profile_photo_path) : 'https://via.placeholder.com/150?text=Usuario' }}" alt="Foto de perfil" width="150" class="rounded-circle profile-photo">
        <h2>{{ $user->name }}</h2>
        <p>{{ '@' . $user->username }}</p>
        <div class="followers-following">
            <div class="follower-count">
                <strong>Seguidores</strong>
                <p><a href="{{ route('profile.followers', $user->id) }}">{{ $user->followers->count() }}</a></p>
            </div>
            <div class="following-count">
                <strong>Seguidos</strong>
                <p><a href="{{ route('profile.followings', $user->id) }}">{{ $user->followings->count() }}</a></p>
            </div>
        </div>
    </div>

    <form action="{{ route('profile.search') }}" method="POST" enctype="multipart/form-data" class="search-form">
        @csrf
        <div class="form-group">
            <label for="search">Buscar usuarios</label>
            <input type="text" class="form-control" id="search" name="search">
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <!-- Barra de navegación -->
    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="my-reviews-tab" data-toggle="tab" href="#my-reviews" role="tab" aria-controls="my-reviews" aria-selected="true">Mis Reseñas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="edit-profile-tab" data-toggle="tab" href="#edit-profile" role="tab" aria-controls="edit-profile" aria-selected="false">Editar Perfil</a>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="profileTabsContent">
        <!-- Mis Reseñas -->
        <div class="tab-pane fade show active" id="my-reviews" role="tabpanel" aria-labelledby="my-reviews-tab">
            <h2>Mis Reseñas</h2>
            <div class="reviews-container">
                <ul class="list-group">
                    @foreach ($user->reviews as $review)
                    @php
                    $apiKey = '6e77f3008b5489918d40768636265cbd';
                    $response = Http::get("https://api.themoviedb.org/3/movie/{$review->movie_id}?api_key={$apiKey}&language=es");
                    $movieData = $response->json();
                    if ($response->successful() && isset($movieData['title'])) {
                        $movieTitle = $movieData['title'];
                    } else {
                        $movieTitle = 'Película Desconocida';
                    }
                    @endphp
                    <li class="list-group-item d-flex flex-column">
                        <div class="review-content">
                            <strong>{{ $movieTitle }}</strong>: {{ $review->comment }}
                        </div>
                        <div class="btn-group mt-2" role="group" aria-label="Basic example">
                            <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-warning btn-sm edit-btn"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="delete-review-form" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm delete-btn" onclick="return confirmDelete(event);"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Editar Perfil -->
        <div class="tab-pane fade" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
            <h2>Editar Perfil</h2>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="profile_photo_path">Foto de perfil</label>
                    <input type="file" class="form-control-file" id="profile_photo_path" name="profile_photo_path">
                </div>

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                </div>

                <div class="form-group">
                    <label for="username">Nombre de usuario</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña (opcional)</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar perfil</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    document.getElementById('togglePasswordConfirmation').addEventListener('click', function() {
        const passwordConfirmation = document.getElementById('password_confirmation');
        const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmation.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    function confirmDelete(event) {
        event.preventDefault();
        const form = event.target.closest('form');
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }
</script>

<style>
    .review-content {
        max-height: 150px; /* Puedes ajustar esta altura según tus necesidades */
        overflow-y: auto;
    }
</style>
@endsection
