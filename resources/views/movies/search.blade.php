@extends('layouts.app')

@section('content')

<h2>Buscar Películas</h2>
<div class="input-group mb-3">
    <input id="search-input" type="text" class="form-control" placeholder="Buscar películas" aria-label="Buscar películas" aria-describedby="button-search">
    <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="button" id="button-search">Buscar</button>
    </div>
</div>

<h2>Resultados de la búsqueda</h2>

<ul id="movies-list">
    @foreach ($movies as $movie)
        <li data-id="{{ $movie['id'] }}"><img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}">
        {{ $movie['title'] }}</li>
    @endforeach
</ul>

<a href="/">Volver</a>


@if ($totalPages > 1)
        <div class="pagination">
            {{-- Calcular el rango de páginas a mostrar --}}
            @php
                $startPage = max($page - 2, 1);
                $endPage = min($startPage + 4, $totalPages);
                $hasStartEllipsis = $startPage > 1;
                $hasEndEllipsis = $endPage < $totalPages;

                // Ajustar el rango si la primera página está entre los 5 números
                if ($startPage <= 1) {
                    $endPage = min($endPage + (1 - $startPage), $totalPages);
                    $startPage = 1;
                } elseif ($startPage - 1 <= 1) { // Si estamos a menos de 5 de la primera página, ajustamos
                    $startPage = 1;
                }

                // Ajustar el rango si la última página está entre los 5 números
                if ($endPage >= $totalPages) {
                    $startPage = max($startPage - ($endPage - $totalPages), 1);
                    $endPage = $totalPages;
                } elseif ($totalPages - $endPage <= 1) { // Si estamos a menos de 5 de la última página, ajustamos
                    $endPage = $totalPages;
                }
            @endphp

            {{-- Enlace para la primera página --}}
            @if ($startPage != 1)
                <a href="{{ route('search', ['query' => $query, 'page' => 1]) }}">1</a>
            @endif

            {{-- Enlaces de paginación --}}
            @for ($i = $startPage; $i <= $endPage; $i++)
                @if ($i != $page)
                    <a href="{{ route('search', ['query' => $query, 'page' => $i]) }}">{{ $i }}</a>
                @else
                    <span class="active">{{ $i }}</span>
                @endif
            @endfor

            {{-- Enlace para la última página --}}
            @if ($endPage != $totalPages)
                <a href="{{ route('search', ['query' => $query, 'page' => $totalPages]) }}">{{ $totalPages }}</a>
            @endif
        </div>
    @endif

{{-- Estilos CSS --}}
    <style>
        .pagination {
            display: block;
            margin-top: 20px;
            padding-bottom: 20px;
            text-align: center;
        }

        .pagination a, .pagination span {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 5px;
            border-radius: 3px;
            color: #333;
            background-color: #fff;
            text-decoration: none;
        }

        .pagination a:hover {
            background-color: #c36;
        }

        .pagination .active {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        ul {
            display: flex;
            flex-wrap: wrap;
            list-style: none;
            padding: 0;
        }

        li {
            width: 200px;
            margin: 10px;
            text-align: center;
        }

        #movies-list li:hover {
    cursor: pointer;
}

        img {
            width: 100%;
            border-radius: 5px;
        }

        h2 {
            margin-top: 20px;
        }
        
    </style>
@endsection