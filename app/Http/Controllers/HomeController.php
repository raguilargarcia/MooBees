<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        // Obtener las 10 reseñas con más likes
        $topReviews = Review::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->take(10)
            ->get();

        $apiKey = '6e77f3008b5489918d40768636265cbd';

        foreach ($topReviews as $review) {
            $response = Http::get("https://api.themoviedb.org/3/movie/{$review->movie_id}?api_key={$apiKey}&language=es");
            $movieData = $response->json();
            if ($response->successful() && isset($movieData['title'])) {
                $review->movie_title = $movieData['title'];
            } else {
                $review->movie_title = 'Película Desconocida';
            }
        }

        return view('home', compact('topReviews'));
    }
}
