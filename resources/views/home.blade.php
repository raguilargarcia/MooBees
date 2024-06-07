@extends('layouts.app')

@section('content')

<h2>Buscar Películas</h2>
<div class="input-group mb-3">
    <input id="search-input" type="text" class="form-control" placeholder="Buscar películas" aria-label="Buscar películas" aria-describedby="button-search">
    <div class="input-group-append">
        <button class="btn" type="button" id="button-search">Buscar</button>
    </div>
</div>

<h2 class="popular-heading">Películas Populares</h2>
<div id="popular-carousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <!-- Las diapositivas de películas populares se agregan dinámicamente con JavaScript -->
    </div>
</div>

<hr>

<h2 class="trending-heading">Películas en Tendencia</h2>
<div id="trending-carousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <!-- Las diapositivas de películas en tendencia se agregan dinámicamente con JavaScript -->
    </div>
</div>

<hr>

<h2>Reseñas con Más Likes</h2>
<div class="top-reviews">
    @foreach($topReviews as $review)
        <div class="review">
            <p><strong>{{ $review->movie_title }}</strong></p>
            <p>{{ $review->comment }}</p>
            <div class="rating">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                @endfor
            </div>
            <p>Escrito por: {{ $review->user->name }}</p>
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
    @endforeach
</div>

<!-- Modal para reportar -->
@foreach($topReviews as $review)
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

<script>
    document.getElementById('search-input').addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.getElementById('button-search').click();
        }
    });
</script>

@endsection
