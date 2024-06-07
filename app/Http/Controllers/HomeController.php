<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        // Obtener las reseñas con más likes, ordenadas por cantidad de likes
        $topReviews = Review::with('user', 'likes', 'dislikes')
                            ->withCount('likes', 'dislikes')
                            ->orderBy('likes_count', 'desc')
                            ->take(5)
                            ->get();

        // Pasar las reseñas a la vista 'home'
        return view('home', ['topReviews' => $topReviews]);
    }
}