<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ShownMovie;
use Illuminate\Support\Facades\Cache;

class GameController extends Controller
{
    private $tmdbApiKey = "6e77f3008b5489918d40768636265cbd";

    public function showGame()
    {
        $movie = Cache::get('daily_movie');
        $step = Cache::get('game_step', 1);

        if (!$movie) {
            $movie = $this->getRandomMovie();

            if (!$movie) {
                return redirect()->route('showGame')->with('error', 'No se pudo obtener la película. Inténtalo de nuevo.');
            }

            Cache::put('daily_movie', $movie, now()->addDay());
            Cache::put('game_step', 1); // Reset the step to 1 when a new movie is set
        }

        $titleRepresentation = $this->getTitleRepresentation($movie['title']);

        return view('game.play', ['movie' => $movie, 'step' => $step, 'titleRepresentation' => $titleRepresentation]);
    }

    public function nextClue(Request $request)
    {
        // Increment the step only when the next clue button is pressed
        $step = Cache::increment('game_step');

        if ($step > 6) {
            Cache::put('game_step', 6);
            $step = 6;
        }

        $movie = Cache::get('daily_movie');
        $titleRepresentation = $this->getTitleRepresentation($movie['title']);
        return view('game.play', ['movie' => $movie, 'step' => $step, 'titleRepresentation' => $titleRepresentation]);
    }

    public function guessMovie(Request $request)
    {
        $guessedMovie = $request->input('movie');
        $movie = Cache::get('daily_movie');
        $step = Cache::get('game_step', 1);

        if (strtolower($guessedMovie) === strtolower($movie['title'])) {
            // Mostrar todas las pistas restantes
            $step = 6; // Set step to the maximum to show all clues
            Cache::put('game_step', $step);
            return view('game.play', ['movie' => $movie, 'step' => $step, 'success' => true]);
        } else {
            // Incrementa el paso por intento incorrecto
            $step = Cache::increment('game_step');
            if ($step > 6) {
                $step = 6;
            }
            $titleRepresentation = $this->getTitleRepresentation($movie['title']);
            return view('game.play', ['movie' => $movie, 'step' => $step, 'incorrectGuess' => true, 'titleRepresentation' => $titleRepresentation]);
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

    private function getRandomMovie()
    {
        $response = Http::get("https://api.themoviedb.org/3/movie/popular", [
            'api_key' => $this->tmdbApiKey,
            'language' => 'es-ES'
        ]);

        if ($response->successful()) {
            $totalPages = $response->json()['total_pages'];

            do {
                $page = rand(1, $totalPages);
                $response = Http::get("https://api.themoviedb.org/3/movie/popular", [
                    'api_key' => $this->tmdbApiKey,
                    'language' => 'es-ES',
                    'page' => $page
                ]);

                if ($response->successful()) {
                    $movies = $response->json()['results'];

                    foreach ($movies as $movie) {
                        $shownMovie = ShownMovie::where('movie_id', $movie['id'])->first();
                        if (!$shownMovie) {
                            $movieDetails = Http::get("https://api.themoviedb.org/3/movie/{$movie['id']}", [
                                'api_key' => $this->tmdbApiKey,
                                'language' => 'es-ES',
                                'append_to_response' => 'credits'
                            ]);

                            if ($movieDetails->successful()) {
                                $movieData = $movieDetails->json();

                                $movie = [
                                    'id' => $movieData['id'],
                                    'title' => $movieData['title'],
                                    'genres' => $movieData['genres'],
                                    'release_date' => $movieData['release_date'],
                                    'overview' => $movieData['overview'],
                                    'director' => $this->getDirector($movieData['credits']['crew']),
                                    'actors' => array_slice($movieData['credits']['cast'], 0, 3)
                                ];

                                return $movie;
                            }
                        }
                    }
                }
            } while (true);
        }

        return null;
    }

    private function getDirector($crew)
    {
        foreach ($crew as $member) {
            if ($member['job'] == 'Director') {
                return $member['name'];
            }
        }
        return 'Desconocido';
    }

    private function getTitleRepresentation($title)
    {
        return implode(' ', array_map(fn($char) => $char == ' ' ? '   ' : '_', str_split($title)));
    }
}

