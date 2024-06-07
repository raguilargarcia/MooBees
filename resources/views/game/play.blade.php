@extends('layouts.app')
@section('content')

<div class="guess-game-page">
    <h1>Juego de Adivinación de Películas</h1>

    @if(session('error'))
    <p style="color: red;">{{ session('error') }}</p>
    @endif

    @if(isset($success) && $success)
    <p style="color: green;">¡Correcto! Has adivinado la película.</p>
    @else
    <div>
        <p><span class="title-representation">{{ $titleRepresentation }}</span></p>

        @if($step >= 1)
        <p><strong>Géneros:</strong> {{ implode(', ', array_map(fn($genre) => $genre['name'], $movie['genres'])) }}</p>
        @endif
        @if($step >= 2)
        <p><strong>Fecha de Estreno:</strong> {{ $movie['release_date'] }}</p>
        @endif
        @if($step >= 3)
        <p><strong>Director:</strong> {{ $movie['director'] }}</p>
        @endif
        @if($step >= 4)
        <p><strong>Actores Principales:</strong>
            @foreach($movie['actors'] as $actor)
            {{ $actor['name'] }}{{ !$loop->last ? ',' : '' }}
            @endforeach
        </p>
        @endif
        @if($step >= 5)
        <p><strong>Sinopsis:</strong> {{ $movie['overview'] }}</p>
        @endif
        @if($step == 6)
        <p><strong>Título:</strong> {{ $movie['title'] }}</p>
        @endif
    </div>

    @if(isset($incorrectGuess) && $incorrectGuess)
    <p style="color: red;">La adivinanza es incorrecta. ¡Inténtalo de nuevo!</p>
    @endif

    <div class="form-container">
        @if($step < 6)
        <form id="guessForm" action="{{ route('guessMovie') }}" method="POST">
            @csrf
            <input type="hidden" name="step" value="{{ $step }}">
            <div class="input-group">
                <input type="text" id="movieInput" name="movie" placeholder="Escribe tu adivinanza" autocomplete="off" class="form-control">
                <button type="submit" class="btn-submit" {{ $step == 7 ? 'disabled' : '' }}>Adivinar</button>
                <ul id="suggestions" class="suggestions-list"></ul>
            </div>
        </form>
        @endif
    </div>

    @if($step < 6)
    <form action="{{ route('nextClue') }}" method="POST">
        @csrf
        <input type="hidden" name="step" value="{{ $step }}">
        <button type="submit" class="btn-next-clue">Siguiente Pista</button>
    </form>
    @else
    <p><strong>Volver al <a href="/">inicio</a></strong></p>
    @endif
    @endif
</div>

<script>
    document.getElementById('movieInput').addEventListener('input', function() {
        const query = this.value;

        if (query.length > 2) {
            axios.get("{{ route('searchMovies') }}", {
                params: {
                    query: query
                }
            }).then(response => {
                const suggestions = response.data;
                const suggestionsList = document.getElementById('suggestions');
                suggestionsList.innerHTML = '';

                suggestions.forEach(movie => {
                    const listItem = document.createElement('li');
                    listItem.textContent = movie.title;
                    listItem.addEventListener('click', function() {
                        document.getElementById('movieInput').value = movie.title;
                        suggestionsList.innerHTML = '';
                    });
                    suggestionsList.appendChild(listItem);
                });
            });
        } else {
            document.getElementById('suggestions').innerHTML = '';
        }
    });
</script>

<style>
    .title-representation {
        white-space: pre;
        letter-spacing: 0.5em;
    }
</style>

@endsection
