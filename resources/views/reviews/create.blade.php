@extends('layouts.app')

@section('content')
    <div class="container review-form-container">
        <h1>Escribir una Reseña</h1>
        <form action="{{ route('reviews.store', ['movie' => $movieId]) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="comment">Reseña (máximo 200 caracteres)</label>
                <textarea name="comment" id="comment" rows="5" class="form-control" maxlength="200" oninput="updateCharacterCount(this)" required></textarea>
                <small id="characterCount" class="form-text text-muted">0/200 caracteres</small>
            </div>
            <div class="form-group">
                <label for="rating">Calificación</label>
                <div id="star-rating">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="star" data-value="{{ $i }}">&#9733;</span>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating" value="1">
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
        <a href="{{ route('movies.show', ['id' => $movieId]) }}">Volver a la película</a>
    </div>

    <style>
        .star {
            font-size: 2em;
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s;
        }
        .star.filled {
            color: #f0da5f;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('#star-rating .star');
            const ratingInput = document.getElementById('rating');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-value');
                    ratingInput.value = rating;

                    stars.forEach(s => {
                        if (s.getAttribute('data-value') <= rating) {
                            s.classList.add('filled');
                        } else {
                            s.classList.remove('filled');
                        }
                    });
                });
            });
        });

        function updateCharacterCount(textarea) {
            const characterCount = textarea.value.length;
            document.getElementById('characterCount').innerText = `${characterCount}/200 caracteres`;
        }
    </script>
@endsection
