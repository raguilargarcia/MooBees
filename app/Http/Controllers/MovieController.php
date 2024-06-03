<?php
// app/Http/Controllers/MovieController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Review;

class MovieController extends Controller
{
    public function show($id)
    {
        $response = Http::get('https://api.themoviedb.org/3/movie/' . $id, [
            'api_key' => '6e77f3008b5489918d40768636265cbd',
            'language' => 'es',
        ]);

        if ($response->ok()) {
            $movieData = $response->json();

            $poster = 'https://image.tmdb.org/t/p/w500' . $movieData['poster_path'] ?? 'https://via.placeholder.com/500x750';
            $title = $movieData['title'] ?? 'Título no disponible';
            $overview = $movieData['overview'] ?? 'Descripción no disponible';
            $release_date = $movieData['release_date'] ?? 'Fecha no disponible';
            $genres = $movieData['genres'] ?? 'Géneros no disponibles';
            $popularity = $movieData['popularity'] ?? 'Popularidad no disponible';
            $vote_average = $movieData['vote_average'] ?? 'Calificación no disponible';
            $runtime = $movieData['runtime'] ?? 'Duración no disponible';
            $tagline = $movieData['tagline'] ?? 'Lema no disponible';
            $cast = $movieData['cast'] ?? 'Actores no disponibles';
            $director = $movieData['director'] ?? 'Director no disponible';
            $production_countries = $movieData['production_countries'] ?? 'País de origen no disponible';

            // Obtener las reseñas de la base de datos utilizando el ID de la película
            $reviews = Review::where('movie_id', $id)->with('user')->get();

            return view('movies.show', [
                'poster' => $poster,
                'title' => $title,
                'overview' => $overview,
                'release_date' => $release_date,
                'genres' => $genres,
                'popularity' => $popularity,
                'vote_average' => $vote_average,
                'runtime' => $runtime,
                'tagline' => $tagline,
                'cast' => $cast,
                'director' => $director,
                'production_countries' => $production_countries,
                'movie_id' => $id,
                'reviews' => $reviews,
            ]);
        } else {
            return redirect()->back()->with('error', 'Error al obtener los detalles de la película.');
        }
    }
}
