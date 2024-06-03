@extends('layouts.app')
@section('content')

<body>
    <h1>Juego de Adivinación de Películas</h1>

    @if(session('error'))
    <p style="color: red;">{{ session('error') }}</p>
    @endif

    @if(isset($success) && $success)
    <p style="color: green;">¡Correcto! Has adivinado la película.</p>
    @else
    <div>
        @if($step >= 1)
        <p><strong>Géneros:</strong> {{ implode(', ', array_map(fn($genre) => $genre['name'], $movie['genres'])) }}</p>
        @endif
        @if($step >= 2)
        <p><strong>Fecha de Estreno:</strong> {{ $movie['release_date'] }}</p>
        @endif
        @if($step >= 3)
        <p><strong>Sinopsis:</strong> {{ $movie['overview'] }}</p>
        @endif

        @if($step >= 4)
        <p><strong>Director:</strong> Director: {{ $movie['director'] }}</p>
        @endif

        @if($step >= 5)
        <p><strong>Actores Principales:</strong> Actores Principales:
            @foreach($movie['actors'] as $actor)
            {{ $actor['name'] }}{{ !$loop->last ? ',' : '' }}
            @endforeach
        </p>
        @endif

        @if($step == 6)
        <p><strong>Título:</strong> {{ $movie['title'] }}</p>
        @endif
    </div>

    @if(isset($incorrectGuess) && $incorrectGuess)
    <p style="color: red;">La adivinanza es incorrecta. ¡Inténtalo de nuevo!</p>
    @endif

    <div class="form-container">
        @if($step < 6) <form id="guessForm" action="{{ route('guessMovie') }}" method="POST">
            @csrf
            <input type="hidden" name="step" value="{{ $step }}">
            <input type="text" id="movieInput" name="movie" placeholder="Escribe tu adivinanza" autocomplete="off">
            <button type="submit" {{ $step == 7 ? 'disabled' : '' }}>Adivinar</button>
            </form>
            @endif
            <ul id="suggestions"></ul>
    </div>

    @if($step < 6) <form action="{{ route('nextClue') }}" method="POST">
        @csrf
        <input type="hidden" name="step" value="{{ $step }}">
        <button type="submit">Siguiente Pista</button>
        </form>
        @else
        <p><strong>Volver al <a href="/">inicio</a></strong></p>
        @endif
        @endif

        <form action="{{ route('resetGame') }}" method="POST" class="reset-button">
            @csrf
            <button type="submit">Reiniciar Juego</button>
        </form>

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
                }
            });
        </script>
</body>
@endsection