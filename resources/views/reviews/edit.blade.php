@extends('layouts.app')

@section('content')
<div class="container review-edit-page">
    <h1>Editar Reseña</h1>
    <form action="{{ route('reviews.update', $review->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="comment">Reseña</label>
            <textarea name="comment" id="comment" rows="5" class="form-control" required>{{ $review->comment }}</textarea>
        </div>
        <div class="form-group">
            <label for="rating">Calificación</label>
            <div id="star-rating">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="star {{ $i <= $review->rating ? 'filled' : '' }}" data-value="{{ $i }}">&#9733;</span>
                @endfor
            </div>
            <input type="hidden" name="rating" id="rating" value="{{ $review->rating }}">
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
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
</script>
@endsection
