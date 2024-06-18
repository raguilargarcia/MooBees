@extends('layouts.app')

@section('content')
<div class="container watchlist-page">
    <h1>{{ $watchlist->name }}</h1>

    <div class="row">
        @foreach ($watchlist->items as $item)
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="{{ $item->movie_poster_path }}" class="card-img-top" alt="{{ $item->movie_title }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $item->movie_title }}</h5>
                    @if (auth()->id() == $watchlist->user_id)
                    <form action="{{ route('watchlists.remove_movie', [$watchlist->id, $item->id]) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
