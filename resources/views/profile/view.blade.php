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
                <p><a href="{{ route('profile.followers', $user->id) }}">{{ $user->followers->count() }}</a></p>
            </div>
            <div class="following-count">
                <strong>Seguidos</strong>
                <p><a href="{{ route('profile.followings', $user->id) }}">{{ $user->followings->count() }}</a></p>
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
                <p style="color: #FFC30B"><strong>{{ $movieTitle }}</strong></p>
                    <p class="comment-preview break-word">{{ \Illuminate\Support\Str::limit($review->comment, 100) }}</p>
                    @if (strlen($review->comment) > 100)
                    <a href="#" class="read-more" data-full-comment="{{ $review->comment }}">Leer más</a>
                    <a href="#" class="read-less" style="display: none;" data-short-comment="{{ \Illuminate\Support\Str::limit($review->comment, 100) }}">Leer menos</a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Watchlists del usuario -->
    <div class="watchlists-container mt-4">
        <h2>Watchlists de {{ $user->username }}</h2>
        <ul class="list-group">
            @foreach ($user->watchlists as $watchlist)
                <li class="list-group-item">
                    <a href="{{ route('watchlists.show', $watchlist->id) }}">{{ $watchlist->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

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

<style>
    .break-word {
        word-break: break-word;
    }

    @media (max-width: 450px) {
        .comment-preview {
            white-space: pre-wrap;
        }
    }
</style>
@endsection
