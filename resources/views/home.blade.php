@extends('layouts.app')

@section('content')

<h2>Buscar Películas</h2>
<div class="input-group mb-3">
    <input id="search-input" type="text" class="form-control" placeholder="Buscar películas" aria-label="Buscar películas" aria-describedby="button-search">
    <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="button" id="button-search">Buscar</button>
    </div>
</div>

<h2 class="popular-heading">Películas Populares</h2>
<div id="popular-carousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <!-- Las diapositivas de películas populares se agregan dinámicamente con JavaScript -->
    </div>
    <a class="carousel-control-prev" href="#popular-carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Anterior</span>
    </a>
    <a class="carousel-control-next" href="#popular-carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Siguiente</span>
    </a>
</div>

<hr>

<h2 class="trending-heading">Películas en Tendencia</h2>
<div id="trending-carousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <!-- Las diapositivas de películas en tendencia se agregan dinámicamente con JavaScript -->
    </div>
    <a class="carousel-control-prev" href="#trending-carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Anterior</span>
    </a>
    <a class="carousel-control-next" href="#trending-carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Siguiente</span>
    </a>
</div>

<hr>

<h2>Reseñas con Más Likes</h2>
<div class="top-reviews">
    @foreach($topReviews as $review)
        <div class="review">
            <p><strong>{{ $review->movie_title }}</strong></p>
            <p>{{ $review->comment }}</p>
            <p>Calificación: {{ $review->rating }} estrellas</p>
            <p>Escrito por: {{ $review->user->name }}</p>
            <p>Likes: {{ $review->likes_count }}</p>
        </div>
    @endforeach
</div>

@endsection
