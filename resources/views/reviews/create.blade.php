<!-- resources/views/reviews/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Escribir una Reseña</h1>
        <form action="{{ route('reviews.store', ['movie' => $movieId]) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="comment">Reseña</label>
                <textarea name="comment" id="comment" rows="5" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="rating">Calificación</label>
                <select name="rating" id="rating" class="form-control" required>
                    <option value="1">1 estrella</option>
                    <option value="2">2 estrellas</option>
                    <option value="3">3 estrellas</option>
                    <option value="4">4 estrellas</option>
                    <option value="5">5 estrellas</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
        <a href="{{ route('movies.show', ['id' => $movieId]) }}">Volver a la película</a>
    </div>
@endsection
