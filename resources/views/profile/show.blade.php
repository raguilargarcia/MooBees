<!-- resources/views/profile.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
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
        @if ($user->profile_picture)
        <img src="{{ asset('images/profile/' . $user->profile_picture) }}" alt="Foto de perfil" width="150">
        @endif
        <h2>{{ $user->name }}</h2>
        <p>{{ '@' . $user->username }}</p>
    </div>

    <form action="{{ route('profile.search') }}" method="POST" enctype="multipart/form-data">
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
        <li class="nav-item">
            <a class="nav-link" id="followings-tab" data-toggle="tab" href="#followings" role="tab" aria-controls="followings" aria-selected="false">Usuarios Seguidos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="followers-tab" data-toggle="tab" href="#followers" role="tab" aria-controls="followers" aria-selected="false">Seguidores</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="following-reviews-tab" data-toggle="tab" href="#following-reviews" role="tab" aria-controls="following-reviews" aria-selected="false">Reseñas de Seguidos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="watchlists-tab" data-toggle="tab" href="#watchlists" role="tab" aria-controls="watchlists" aria-selected="false">Mis Watchlists</a>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="profileTabsContent">
        <!-- Mis Reseñas -->
        <div class="tab-pane fade show active" id="my-reviews" role="tabpanel" aria-labelledby="my-reviews-tab">
            <h2>Mis Reseñas</h2>
            <ul>
                @foreach ($user->reviews as $review)
                @php
                // Obtener el título de la película utilizando el ID de la película
                $apiKey = '6e77f3008b5489918d40768636265cbd';
                $response = Http::get("https://api.themoviedb.org/3/movie/{$review->movie_id}?api_key={$apiKey}&language=es");
                $movieData = $response->json();

                // Verificar si la llamada a la API fue exitosa y si se obtuvieron los datos de la película
                if ($response->successful() && isset($movieData['title'])) {
                $movieTitle = $movieData['title'];
                } else {
                $movieTitle = 'Película Desconocida';
                }
                @endphp
                <li>{{ $movieTitle }}: {{ $review->comment }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Editar Perfil -->
        <div class="tab-pane fade" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
            <h2>Editar Perfil</h2>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="profile_picture">Foto de perfil</label>
                    <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                </div>

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña (opcional)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>

                <button type="submit" class="btn btn-primary">Actualizar perfil</button>
            </form>
        </div>

        <!-- Usuarios Seguidos -->
        <div class="tab-pane fade" id="followings" role="tabpanel" aria-labelledby="followings-tab">
            <h2>Usuarios Seguidos</h2>
            <ul>
                @foreach ($user->followings as $following)
                <li>{{ $following->username }}
                    <form action="{{ route('profile.unfollow', $following) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Dejar de seguir</button>
                    </form>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Seguidores -->
        <div class="tab-pane fade" id="followers" role="tabpanel" aria-labelledby="followers-tab">
            <h2>Seguidores</h2>
            <ul>
                @foreach ($user->followers as $follower)
                <li>{{ $follower->name }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Reseñas de Usuarios Seguidos -->
        <div class="tab-pane fade" id="following-reviews" role="tabpanel" aria-labelledby="following-reviews-tab">
            <h2>Reseñas de Usuarios Seguidos</h2>
            <ul>
                @foreach ($user->followings as $following)
                @foreach ($following->reviews as $review)
                @php
                // Obtener el título de la película utilizando el ID de la película
                $apiKey = '6e77f3008b5489918d40768636265cbd';
                $response = Http::get("https://api.themoviedb.org/3/movie/{$review->movie_id}?api_key={$apiKey}&language=es");
                $movieData = $response->json();

                // Verificar si la llamada a la API fue exitosa y si se obtuvieron los datos de la película
                if ($response->successful() && isset($movieData['title'])) {
                $movieTitle = $movieData['title'];
                } else {
                $movieTitle = 'Película Desconocida';
                }
                @endphp
                <li>Reseña de {{$following->username}} para "{{ $movieTitle }}": {{ $review->comment }}</li>
                @endforeach
                @endforeach
            </ul>
        </div>

        <!-- Mis Watchlists -->
        <div class="tab-pane fade" id="watchlists" role="tabpanel" aria-labelledby="watchlists-tab">
            <h2>Mis Watchlists</h2>

            <form action="{{ route('watchlists.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="watchlist_name">Nombre de la Watchlist</label>
                    <input type="text" class="form-control" id="watchlist_name" name="name">
                </div>
                <button type="submit" class="btn btn-primary">Crear Watchlist</button>
            </form>

            @foreach ($user->watchlists as $watchlist)
            <div class="watchlist">
                <h3>{{ $watchlist->name }}</h3>
                <ul>
                    @foreach ($watchlist->items as $item)
                    <li>{{ $item->movie_id }}
                        <form action="{{ route('watchlists.remove_movie', [$watchlist->id, $item->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
