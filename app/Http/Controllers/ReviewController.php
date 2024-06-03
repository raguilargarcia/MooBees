<?php

// app/Http/Controllers/ReviewController.php
namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create($movieId)
    {
        return view('reviews.create', compact('movieId'));
    }

    public function store(Request $request, $movieId)
    {
        $request->validate([
            'comment' => 'required',
            'rating' => 'required|integer|between:1,5', // Validar que el rating esté entre 1 y 5
        ]);

        $review = new Review();
        $review->comment = $request->input('comment');
        $review->user_id = auth()->id();
        $review->movie_id = $movieId;
        $review->rating = $request->input('rating');
        $review->save();

        return redirect()->route('movies.show', ['id' => $movieId])
            ->with('success', 'Reseña creada exitosamente.');
    }
}
