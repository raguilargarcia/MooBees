<!-- resources/views/watchlists/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mis Watchlists</h1>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('watchlists.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="watchlist_name">Nombre de la Watchlist</label>
            <input type="text" class="form-control" id="watchlist_name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Crear Watchlist</button>
    </form>

    @foreach ($watchlists as $watchlist)
    <div class="watchlist mt-4">
        <h3>{{ $watchlist->name }}</h3>
        <ul id="watchlist-items-{{ $watchlist->id }}">
            @foreach ($watchlist->items as $item)
            <li id="watchlist-item-{{ $item->id }}">
                {{ $item->movie_title }}
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.add-movie-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                var watchlistId = form.dataset.watchlistId;
                var movieId = form.querySelector('input[name="movie_id"]').value;
                var csrfToken = form.querySelector('input[name="_token"]').value;

                fetch(`/watchlists/${watchlistId}/add-movie`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        movie_id: movieId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        var newItem = document.createElement('li');
                        newItem.id = `watchlist-item-${data.item.id}`;
                        newItem.innerHTML = `${data.item.movie_title}
                            <form action="/watchlists/${watchlistId}/remove-movie/${data.item.id}" method="POST" style="display:inline;">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>`;
                        document.getElementById(`watchlist-items-${watchlistId}`).appendChild(newItem);
                    } else {
                        alert('Error al añadir la película a la Watchlist.');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
@endsection
