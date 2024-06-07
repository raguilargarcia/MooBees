<?php
// app/Console/Commands/UpdateDailyMovie.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\ShownMovie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateDailyMovie extends Command
{
    protected $signature = 'movie:update-daily';
    protected $description = 'Update the daily movie and reset game steps';

    private $tmdbApiKey = "6e77f3008b5489918d40768636265cbd";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Inicio del comando de actualización de película diaria');

        // Eliminar el registro más antiguo si hay más de 500 registros
        if (ShownMovie::count() >= 500) {
            ShownMovie::oldest()->first()->delete();
        }

        // Obtener una nueva película aleatoria que no haya sido mostrada
        $newMovie = $this->getRandomMovie();
        if ($newMovie) {
            // Registrar la nueva película como mostrada
            ShownMovie::create(['movie_id' => $newMovie['id']]);

            // Actualizar la película en la caché
            Cache::put('daily_movie', $newMovie, now()->addDay());

            // Resetear los pasos de adivinación
            Cache::put('game_step', 1);

            Log::info('Película diaria actualizada y pasos del juego reiniciados con éxito.');
            $this->info('Daily movie updated and game steps reset successfully.');
        } else {
            Log::error('Falló la actualización de la película diaria.');
            $this->error('Failed to update the daily movie.');
        }
    }

    private function getRandomMovie()
    {
        Log::info('Obteniendo una nueva película aleatoria.');

        // Obtener el número total de páginas
        $response = Http::get("https://api.themoviedb.org/3/movie/popular", [
            'api_key' => $this->tmdbApiKey,
            'language' => 'es-ES'
        ]);

        if ($response->successful()) {
            $totalPages = $response->json()['total_pages'];

            Log::info("Total de páginas: {$totalPages}");

            do {
                // Seleccionar una página aleatoria
                $page = rand(1, $totalPages);
                $response = Http::get("https://api.themoviedb.org/3/movie/popular", [
                    'api_key' => $this->tmdbApiKey,
                    'language' => 'es-ES',
                    'page' => $page
                ]);

                if ($response->successful()) {
                    $movies = $response->json()['results'];

                    foreach ($movies as $movie) {
                        // Verificar si la película ya ha sido mostrada
                        $shownMovie = ShownMovie::where('movie_id', $movie['id'])->first();
                        if (!$shownMovie) {
                            $movieDetails = Http::get("https://api.themoviedb.org/3/movie/{$movie['id']}", [
                                'api_key' => $this->tmdbApiKey,
                                'language' => 'es-ES',
                                'append_to_response' => 'credits' // Solicitar detalles de créditos (actores y director)
                            ]);

                            if ($movieDetails->successful()) {
                                $movieData = $movieDetails->json();

                                // Extraer detalles relevantes de la respuesta de la API
                                $movie = [
                                    'id' => $movieData['id'],
                                    'title' => $movieData['title'],
                                    'genres' => $movieData['genres'],
                                    'release_date' => $movieData['release_date'],
                                    'overview' => $movieData['overview'],
                                    'director' => $this->getDirector($movieData['credits']['crew']), // Primer director de la lista de créditos
                                    'actors' => array_slice($movieData['credits']['cast'], 0, 3) // Primeros tres actores de la lista de créditos
                                ];

                                Log::info("Nueva película seleccionada: {$movie['title']}");

                                return $movie;
                            }
                        }
                    }
                }
            } while (true);
        }

        Log::error('No se pudo obtener una nueva película.');
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
}
