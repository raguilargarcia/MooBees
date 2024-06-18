<?php

// HomeController.php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        $latestReviewsFromFollowing = collect();
        $topReviews = collect();

        if ($user) {
            // Obtener los IDs de los usuarios que sigue
            $followingUserIds = $user->followings()->pluck('followed_id');

            // Obtener la última reseña de cada usuario seguido
            $latestReviewsFromFollowing = DB::table('reviews')
                ->select('reviews.id', 'reviews.user_id', 'reviews.movie_id', 'reviews.comment', 'reviews.rating', 'reviews.created_at')
                ->join(DB::raw('(SELECT user_id, MAX(created_at) as latest FROM reviews GROUP BY user_id) as latest_reviews'), function($join) {
                    $join->on('reviews.user_id', '=', 'latest_reviews.user_id')
                         ->on('reviews.created_at', '=', 'latest_reviews.latest');
                })
                ->whereIn('reviews.user_id', $followingUserIds)
                ->get();

            // Convertir el resultado a una colección de Eloquent Models
            $latestReviewsFromFollowing = Review::hydrate($latestReviewsFromFollowing->toArray());
        }

        // Obtener las reseñas populares (esto puede variar según tu lógica actual)
        $topReviews = Review::with(['user', 'likes', 'dislikes'])
            ->withCount('likes', 'dislikes')
            ->orderBy('likes_count', 'desc')
            ->take(5)
            ->get();

        return view('home', compact('latestReviewsFromFollowing', 'topReviews'));
    }
}
