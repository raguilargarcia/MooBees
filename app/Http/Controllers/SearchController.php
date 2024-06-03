<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http; // Import the Http facade

class SearchController extends Controller
{
    public function search($query, $page = 1)
    {
        // Realiza la búsqueda utilizando la API correspondiente (en tu caso, TMDb)
        $apiKey = '6e77f3008b5489918d40768636265cbd'; // Reemplaza con tu clave de API de TMDb
        $perPage = 20;
        $apiUrl = "https://api.themoviedb.org/3/search/movie?api_key={$apiKey}&query={$query}&page={$page}&limit={$perPage}&language=es";

        $response = Http::get($apiUrl);

        // Procesa los resultados
        $movies = $response->json()['results'];
        $totalPages = $response->json()['total_pages'];

        // Pasa los resultados a la vista junto con la información de paginación y el término de búsqueda
        return view('movies.search', compact('movies', 'query', 'page', 'totalPages'));
    }
}
