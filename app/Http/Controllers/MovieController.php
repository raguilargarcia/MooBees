<?php
// app/Http/Controllers/MovieController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Review;
use App\Models\Watchlist; // Importa el modelo de Watchlist
use Illuminate\Support\Facades\Auth; // Importa Auth

class MovieController extends Controller
{
    public function show($id)
    {
        // Obtener los detalles de la película desde la API
        $apiKey = '6e77f3008b5489918d40768636265cbd';
        $response = Http::get("https://api.themoviedb.org/3/movie/{$id}?api_key={$apiKey}&language=es");
        $movieData = $response->json();

        // Obtener las reseñas de la base de datos
        $reviews = Review::where('movie_id', $id)->with('user')->get();

        // Obtener las watchlists del usuario autenticado
        $watchlists = Auth::check() ? Auth::user()->watchlists : null;

        return view('movies.show', [
            'poster' => 'https://image.tmdb.org/t/p/w500' . ($movieData['poster_path'] ?? 'https://via.placeholder.com/500x750'),
            'title' => $movieData['title'] ?? 'Título desconocido',
            'overview' => $movieData['overview'] ?? 'Sin descripción',
            'release_date' => $movieData['release_date'] ?? 'Fecha desconocida',
            'vote_average' => $movieData['vote_average'] ?? 'N/A',
            'runtime' => $movieData['runtime'] ?? 'N/A',
            'tagline' => $movieData['tagline'] ?? '',
            'director' => $this->getDirector($id),
            'reviews' => $reviews,
            'movie_id' => $id,
            'watchlists' => $watchlists,
        ]);
    }

    private function getDirector($movieId)
    {
        $apiKey = '6e77f3008b5489918d40768636265cbd';
        $response = Http::get("https://api.themoviedb.org/3/movie/{$movieId}/credits?api_key={$apiKey}&language=es");
        $credits = $response->json();

        $director = collect($credits['crew'])->firstWhere('job', 'Director');
        return $director['name'] ?? 'Desconocido';
    }
}
