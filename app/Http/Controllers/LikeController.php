<?php

// app/Http/Controllers/LikeController.php
namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Dislike;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike(Request $request, $reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $userId = Auth::id();

        $existingLike = Like::where('user_id', $userId)
                            ->where('review_id', $reviewId)
                            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return redirect()->back()->with('success', 'Like removido.');
        } else {
            // Remove any existing dislike
            Dislike::where('user_id', $userId)
                   ->where('review_id', $reviewId)
                   ->delete();

            Like::create([
                'user_id' => $userId,
                'review_id' => $reviewId,
            ]);

            return redirect()->back()->with('success', 'Like agregado.');
        }
    }

    public function toggleDislike(Request $request, $reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $userId = Auth::id();

        $existingDislike = Dislike::where('user_id', $userId)
                                  ->where('review_id', $reviewId)
                                  ->first();

        if ($existingDislike) {
            $existingDislike->delete();
            return redirect()->back()->with('success', 'Dislike removido.');
        } else {
            // Remove any existing like
            Like::where('user_id', $userId)
                ->where('review_id', $reviewId)
                ->delete();

            Dislike::create([
                'user_id' => $userId,
                'review_id' => $reviewId,
            ]);

            return redirect()->back()->with('success', 'Dislike agregado.');
        }
    }
}
