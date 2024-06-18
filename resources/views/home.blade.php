@extends('layouts.app')

@section('content')

<h2>Buscar Películas</h2>
<div class="input-group mb-3">
    <input id="search-input" type="text" class="form-control" placeholder="Buscar películas" aria-label="Buscar películas" aria-describedby="button-search">
    <div class="input-group-append">
        <button class="btn" type="button" id="button-search">Buscar</button>
    </div>
</div>

<hr>

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

<h2 class="latest-reviews-heading">Últimas Reseñas de Usuarios Seguidos</h2>
<div class="top-reviews">
    @foreach($latestReviewsFromFollowing as $review)
    <div class="review">
        @php
        $apiKey = '6e77f3008b5489918d40768636265cbd';
        $response = Http::get("https://api.themoviedb.org/3/movie/{$review->movie_id}?api_key={$apiKey}&language=es");
        $movieData = $response->json();
        if ($response->successful() && isset($movieData['title'])) {
        $movieTitle = $movieData['title'];
        }
        else {
        $movieTitle = 'Película Desconocida';
        }
        if ($response->successful() && isset($movieData['poster_path'])) {
        $poster = 'https://image.tmdb.org/t/p/w300' . $movieData['poster_path'];
        }
        else {
        $poster = 'https://via.placeholder.com/300x450';
        }
        @endphp
        <div class="review-content">
            <img src="{{ $poster }}" alt="Movie Poster" class="poster">
            <div class="text-content">
                <p><strong>{{ $movieTitle }}</strong></p>
                <p class="comment-preview break-word">{{ \Illuminate\Support\Str::limit($review->comment, 100) }}</p>
                @if (strlen($review->comment) > 100)
                <a href="#" class="read-more" data-full-comment="{{ $review->comment }}">Leer más</a>
                <a href="#" class="read-less" style="display: none;" data-short-comment="{{ \Illuminate\Support\Str::limit($review->comment, 100) }}">Leer menos</a>
                @endif
                <div class="rating">
                    @for ($i = 1; $i <= 5; $i++) <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                        @endfor
                </div>
                <p>{{ $review->user->username }}</p>
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
        </div>
    </div>
    @endforeach
</div>

<h2>Reseñas Populares</h2>
<div class="top-reviews">
    @foreach($topReviews as $review)
    <div class="review">
        @php
        $apiKey = '6e77f3008b5489918d40768636265cbd';
        $response = Http::get("https://api.themoviedb.org/3/movie/{$review->movie_id}?api_key={$apiKey}&language=es");
        $movieData = $response->json();
        if ($response->successful() && isset($movieData['title'])) {
        $movieTitle = $movieData['title'];
        } else {
        $movieTitle = 'Película Desconocida';
        }
        if ($response->successful() && isset($movieData['poster_path'])) {
        $poster = 'https://image.tmdb.org/t/p/w300' . $movieData['poster_path'];
        } else {
        $poster = 'https://via.placeholder.com/300x450';
        }
        @endphp
        <div class="review-content">
            <img src="{{ $poster }}" alt="Movie Poster" class="poster">
            <div class="text-content">
                <p ><strong>{{ $movieTitle }}</strong></p>
                <p class="comment-preview break-word">{{ \Illuminate\Support\Str::limit($review->comment, 100) }}</p>
                @if (strlen($review->comment) > 100)
                <a href="#" class="read-more" data-full-comment="{{ $review->comment }}">Leer más</a>
                <a href="#" class="read-less" style="display: none;" data-short-comment="{{ \Illuminate\Support\Str::limit($review->comment, 100) }}">Leer menos</a>
                @endif
                <div class="rating">
                    @for ($i = 1; $i <= 5; $i++) <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                        @endfor
                </div>
                <p>{{ $review->user->username }}</p>
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

<style>
    .break-word {
        word-break: break-word;
    }

    @media (max-width: 450px) {
        .review-content .poster {
            display: none;
        }

        .comment-preview {
            white-space: pre-wrap;
        }
    }
</style>

<script>
    function insertLineBreaks(text, maxLength) {
        let result = '';
        let currentLength = 0;

        while (text.length > 0) {
            if (currentLength + text.length <= maxLength) {
                result += text;
                break;
            }

            let spaceIndex = text.lastIndexOf(' ', maxLength - currentLength);

            if (spaceIndex === -1) {
                spaceIndex = maxLength - currentLength;
            }

            result += text.substring(0, spaceIndex) + '\n';
            text = text.substring(spaceIndex + 1);
            currentLength = 0;
        }

        return result;
    }

    function formatComment(text) {
        const maxLength = window.innerWidth <= 425 ? 25 : 100;
        return insertLineBreaks(text, maxLength);
    }

    document.getElementById('search-input').addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.getElementById('button-search').click();
        }
    });

    document.querySelectorAll('.read-more').forEach(function(element) {
        element.addEventListener('click', function(event) {
            event.preventDefault();
            const fullComment = this.getAttribute('data-full-comment');
            const formattedComment = formatComment(fullComment);
            const commentPreview = this.previousElementSibling;
            const readLessButton = this.nextElementSibling;
            commentPreview.textContent = formattedComment;
            this.style.display = 'none';
            readLessButton.style.display = 'inline';
        });
    });

    document.querySelectorAll('.read-less').forEach(function(element) {
        element.addEventListener('click', function(event) {
            event.preventDefault();
            const shortComment = this.getAttribute('data-short-comment');
            const commentPreview = this.previousElementSibling.previousElementSibling;
            const readMoreButton = this.previousElementSibling;
            commentPreview.textContent = formatComment(shortComment);
            this.style.display = 'none';
            readMoreButton.style.display = 'inline';
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.comment-preview').forEach(function(element) {
            const shortComment = element.textContent;
            const formattedComment = formatComment(shortComment);
            element.textContent = formattedComment;
        });
    });
</script>

@endsection
