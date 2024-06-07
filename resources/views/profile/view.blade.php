@extends('layouts.app')

@section('content')
<div class="container profile-page">
    <h1>Perfil de {{ $user->username }}</h1>

    <!-- Información principal del perfil -->
    <div class="profile-header text-center">
        <img src="{{ $user->profile_photo_path ? asset('images/profile/' . $user->profile_photo_path) : 'https://via.placeholder.com/150?text=Usuario' }}" alt="Foto de perfil" width="150" class="rounded-circle profile-photo">
        <h2>{{ $user->name }}</h2>
        <p>{{ '@' . $user->username }}</p>
        <div class="followers-following">
            <div class="follower-count">
                <strong>Seguidores</strong>
                <p>{{ $user->followers->count() }}</p>
            </div>
            <div class="following-count">
                <strong>Seguidos</strong>
                <p>{{ $user->followings->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Botones de seguir/dejar de seguir -->
    @if (Auth::id() !== $user->id)
        @if (Auth::user()->isFollowing($user))
            <form action="{{ route('profile.unfollow', $user) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Dejar de seguir</button>
            </form>
        @else
            <form action="{{ route('profile.follow', $user) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">Seguir</button>
            </form>
        @endif
    @endif

    <!-- Reseñas del usuario -->
    <div class="reviews-container mt-4">
        <h2>Reseñas Escritas</h2>
        <ul class="list-group">
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
                <li class="list-group-item">
                    <strong>{{ $movieTitle }}</strong>: {{ $review->comment }}
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
