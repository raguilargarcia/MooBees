<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ChangeMovieDaily extends Command
{
    protected $signature = 'movie:change-daily';
    protected $description = 'Change the movie daily';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $randomMovie = $this->getRandomMovie();

        if ($randomMovie) {
            session(['movie' => $randomMovie]);
            $this->info('Movie changed successfully.');
        } else {
            $this->error('Failed to change movie.');
        }
    }

    private function getRandomMovie()
{
    $response = Http::get("https://api.themoviedb.org/3/movie/popular", [
        'api_key' => "6e77f3008b5489918d40768636265cbd",
        'language' => 'es-ES',
        'page' => rand(1, 10)
    ]);

    if ($response->successful()) {
        $movies = $response->json()['results'];
        $randomMovie = $movies[array_rand($movies)];

        $movieDetails = Http::get("https://api.themoviedb.org/3/movie/{$randomMovie['id']}", [
            'api_key' => "6e77f3008b5489918d40768636265cbd",
            'language' => 'es-ES',
            'append_to_response' => 'credits' // Solicitar detalles de créditos (actores y director)
        ]);

        if ($movieDetails->successful()) {
            $movieData = $movieDetails->json();

            // Extraer detalles relevantes de la respuesta de la API
            $movie = [
                'title' => $movieData['title'],
                'genres' => $movieData['genres'],
                'release_date' => $movieData['release_date'],
                'overview' => $movieData['overview'],
                'director' => $movieData['credits']['crew'][0]['name'], // Primer director de la lista de créditos
                'actors' => array_slice($movieData['credits']['cast'], 0, 3) // Primeros tres actores de la lista de créditos
            ];

            return $movie;
        }
    }

    return null;
}
}