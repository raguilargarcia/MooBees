@extends('layouts.app')

@section('content')
<div class="container movie-detail-container">
    <img src="{{ $poster }}" alt="Movie Poster">
    <h1>{{ $title }}</h1>
    <p>{{ $overview }}</p>
    <p>Fecha de lanzamiento: {{ $release_date }}</p>
    <p>Calificación: {{ $vote_average }}</p>
    <p>Duración: {{ $runtime }} min</p>
    <p>{{ $tagline }}</p>
    <p>Director: {{ $director }}</p>

    <div>
    <a href="javascript:history.back()" class="btn btn-secondary mb-3">Volver atrás</a>
    </div>

    @if (Auth::check())
    <!-- Enlace para escribir una reseña si el usuario está autenticado -->
    <a href="{{ route('reviews.create', ['movie' => $movie_id]) }}" class="btn btn-primary mt-3">Escribir una reseña</a>

    <!-- Formulario para agregar la película a una watchlist -->
    <form action="{{ route('watchlists.add_movie', ['watchlist' => '0']) }}" method="POST" class="mt-3" id="add-movie-form">
        @csrf
        <div class="form-group">
            <label for="watchlist_id">Añadir a Watchlist</label>
            <select name="watchlist_id" id="watchlist_id" class="form-control" required>
                <option value="">Seleccionar Watchlist</option>
                @foreach($watchlists as $watchlist)
                <option value="{{ $watchlist->id }}" data-url="{{ route('watchlists.add_movie', ['watchlist' => $watchlist->id]) }}">{{ $watchlist->name }}</option>
                @endforeach
            </select>
            <input type="hidden" name="movie_id" value="{{ $movie_id }}">
            <input type="hidden" name="movie_title" value="{{ $title }}">
            <input type="hidden" name="movie_poster_path" value="{{ $poster }}">
        </div>
        <button type="submit" class="btn btn-primary">Añadir</button>
    </form>

    <script>
        document.getElementById('watchlist_id').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var form = document.getElementById('add-movie-form');
            form.action = selectedOption.getAttribute('data-url');
        });
    </script>

    @else
    <!-- Mensaje y enlace a la página de inicio de sesión si el usuario no está autenticado -->
    <p>Debes <a href="{{ route('login') }}">iniciar sesión</a> para escribir una reseña o añadir a la Watchlist.</p>
    @endif

    <!-- Mostrar reseñas existentes -->
    @if(count($reviews) > 0)
        <h2>Reseñas</h2>
        <div class="reviews-container">
    @endif
    
        @foreach($reviews as $review)
        <div class="review">
            <p>{{ $review->comment }}</p>
            <div class="rating">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                @endfor
            </div>
            <p>Titulo: {{ $title }}</p>
            <p>Escrito por: {{ $review->user->name }}</p>
            <p>Fecha: {{ $review->created_at->format('d/m/Y') }}</p>
            <div class="icon-container">
                <form action="{{ route('reviews.toggle-like', $review->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn">
                        <i class="fas fa-thumbs-up"></i> <span class="like-count">{{ $review->likes->count() }}</span>
                    </button>
                </form>
                <form action="{{ route('reviews.toggle-dislike', $review->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn">
                        <i class="fas fa-thumbs-down"></i> <span class="dislike-count">{{ $review->dislikes->count() }}</span>
                    </button>
                </form>
                <button type="button" class="btn" data-toggle="modal" data-target="#reportModal{{ $review->id }}">
                    <i class="fas fa-flag"></i>
                </button>
            </div>
        </div>

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
        @endforeach
    </div>
</div>
@endsection
