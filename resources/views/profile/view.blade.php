@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Perfil de {{ $user->username }}</h1>

    <img src="{{ asset('images/profile/' . $user->profile_picture) }}" alt="Foto de perfil" width="150">

    <p>Nombre: {{ $user->name }}</p>
    <p>Correo electrónico: {{ $user->email }}</p>

    <h2>Reseñas escritas</h2>
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

    <h2>Seguidores</h2>
    <ul>
        @foreach ($user->followers as $follower)
        <li>{{ $follower->name }}</li>
        @endforeach
    </ul>

    <h2>Seguidos</h2>
    <ul>
        @foreach ($user->followings as $following)
        <li>{{ $following->name }}</li>
        @endforeach
    </ul>

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

    <h2>Reseñas de Usuarios Seguidos</h2>
    <ul>
        @foreach ($user->followings as $following)
            @foreach ($following->reviews as $review)
                <li>
                    {{ $following->name }} sobre {{ $review->movie->title }}: {{ $review->comment }}

                    <!-- Botón de like -->
                    <form action="{{ route('reviews.like', $review->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">
                            Me gusta ({{ $review->likes->count() }})
                        </button>
                    </form>

                    <!-- Botón de desmarcar like -->
                    <form action="{{ route('reviews.unlike', $review->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-secondary">
                            No me gusta
                        </button>
                    </form>

                    <!-- Botón de reportar -->
                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#reportModal{{ $review->id }}">
                        Reportar
                    </button>

                    <!-- Modal para reportar -->
                    <div class="modal fade" id="reportModal{{ $review->id }}" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel{{ $review->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('reviews.report', $review->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="reportModalLabel{{ $review->id }}">Reportar Reseña</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="reason">Razón</label>
                                            <textarea name="reason" id="reason" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-danger">Reportar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        @endforeach
    </ul>
</div>
@endsection
