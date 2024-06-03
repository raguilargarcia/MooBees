<!-- resources/views/movies/show.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <img src="{{ $poster }}" alt="Movie Poster">
    <h1>{{ $title }}</h1>
    <p>{{ $overview }}</p>
    <p>Fecha de lanzamiento: {{ $release_date }}</p>
    <p>Calificación: {{ $vote_average }}</p>
    <p>Duración: {{ $runtime }} min</p>
    <p>{{ $tagline }}</p>
    <p>Director: {{ $director }}</p>

    <a href="/">Volver al listado de películas</a>

    @if (Auth::check())
    <!-- Enlace para escribir una reseña si el usuario está autenticado -->
    <a href="{{ route('reviews.create', ['movie' => $movie_id]) }}">Escribir una reseña</a>

    <!-- Formulario para agregar la película a una watchlist -->
    <form action="{{ route('watchlists.add_movie', ['watchlist' => 0]) }}" method="POST" class="mt-3">
        @csrf
        <div class="form-group">
            <label for="watchlist_id">Añadir a Watchlist</label>
            <select name="watchlist_id" id="watchlist_id" class="form-control" required>
                <option value="">Seleccionar Watchlist</option>
                @foreach($watchlists as $watchlist)
                <option value="{{ $watchlist->id }}">{{ $watchlist->name }}</option>
                @endforeach
            </select>
            <input type="hidden" name="movie_id" value="{{ $movie_id }}">
            <input type="hidden" name="movie_title" value="{{ $title }}">
        </div>
        <button type="submit" class="btn btn-primary">Añadir</button>
    </form>

    @else
    <!-- Mensaje y enlace a la página de inicio de sesión si el usuario no está autenticado -->
    <p>Debes <a href="{{ route('iniciar-sesion') }}">iniciar sesión</a> para escribir una reseña o añadir a la Watchlist.</p>
    @endif

    <!-- Mostrar reseñas existentes -->
    <h2>Reseñas</h2>
    @foreach($reviews as $review)
    <div class="review">
        <p>{{ $review->comment }}</p>
        <p>Calificación: {{ $review->rating }} estrellas</p>
        <p>Escrito por: {{ $review->user->name }}</p>
        <p>Fecha: {{ $review->created_at->format('d/m/Y') }}</p>

        <!-- Botón de like -->
        <form action="{{ route('reviews.toggle-like', $review->id) }}" method="POST" style="display:inline;">
            @csrf
            @if ($review->likes->contains('user_id', Auth::id()))
            <button type="submit" class="btn btn-sm btn-primary">
                Quitar Me gusta ({{ $review->likes->count() }})
            </button>
            @else
            <button type="submit" class="btn btn-sm btn-primary">
                Me gusta ({{ $review->likes->count() }})
            </button>
            @endif
        </form>

        <!-- Botón de dislike -->
        <form action="{{ route('reviews.toggle-dislike', $review->id) }}" method="POST" style="display:inline;">
            @csrf
            @if ($review->dislikes->contains('user_id', Auth::id()))
            <button type="submit" class="btn btn-sm btn-danger">
                Quitar No me gusta ({{ $review->dislikes->count() }})
            </button>
            @else
            <button type="submit" class="btn btn-sm btn-danger">
                No me gusta ({{ $review->dislikes->count() }})
            </button>
            @endif
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
    </div>
    @endforeach
</div>
@endsection
