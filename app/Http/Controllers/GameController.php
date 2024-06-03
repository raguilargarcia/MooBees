<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GameController extends Controller
{
    private $tmdbApiKey = "6e77f3008b5489918d40768636265cbd";

    public function showGame()
    {
        // Obtener el número de pasos de la sesión, o establecerlo en 1 si no existe
        $step = session('step', 1);

        if (!session()->has('movie')) {
            $randomMovie = $this->getRandomMovie();

            if (!$randomMovie) {
                return redirect()->route('showGame')->with('error', 'No se pudo obtener la película. Inténtalo de nuevo.');
            }

            session(['movie' => $randomMovie]);
        }

        $movie = session('movie');

        // Pasar el número de pasos a la vista
        return view('game.play', ['movie' => $movie, 'step' => $step]);
    }
    public function nextClue(Request $request)
    {
        // Obtener el número de pasos de la sesión, o establecerlo en 1 si no existe
        $step = session('step', 1) + 1;
        $movie = session('movie');

        if ($step > 6) {
            return view('game.play', ['movie' => $movie, 'step' => 6]);
        }

        // Guardar el número de pasos en la sesión
        session(['step' => $step]);

        return view('game.play', ['movie' => $movie, 'step' => $step]);
    }


    public function guessMovie(Request $request)
    {
        $guessedMovie = $request->input('movie');
        $movie = session('movie');
        $step = $request->input('step', 1);

        if (strtolower($guessedMovie) === strtolower($movie['title'])) {
            return view('game.play', ['movie' => $movie, 'step' => $step, 'success' => true]);
        } else {
            // Si todas las pistas ya se han mostrado, mostrar el título de la película
            if ($step >= 3) {
                $step = 4; // Mostrar el título en el cuarto paso
            }
            // Si no quedan más pistas, saltar a la siguiente pista
            elseif ($step == 2) {
                $step = 3;
            } else {
                $step += 1;
            }
            return view('game.play', ['movie' => $movie, 'step' => $step, 'incorrectGuess' => true]);
        }
    }

    public function searchMovies(Request $request)
    {
        $query = $request->input('query');

        $response = Http::get("https://api.themoviedb.org/3/search/movie", [
            'api_key' => $this->tmdbApiKey,
            'language' => 'es-ES',
            'query' => $query
        ]);

        if ($response->successful()) {
            return response()->json($response->json()['results']);
        }

        return response()->json([]);
    }

    public function resetGame()
    {
        // Eliminar tanto la película como el número de pasos de la sesión
        session()->forget(['movie', 'step']);
        return redirect()->route('showGame');
    }

    private function getRandomMovie()
    {
        $response = Http::get("https://api.themoviedb.org/3/movie/popular", [
            'api_key' => $this->tmdbApiKey,
            'language' => 'es-ES',
            'page' => rand(1, 10)
        ]);

        if ($response->successful()) {
            $movies = $response->json()['results'];
            $randomMovie = $movies[array_rand($movies)];

            $movieDetails = Http::get("https://api.themoviedb.org/3/movie/{$randomMovie['id']}", [
                'api_key' => $this->tmdbApiKey,
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
