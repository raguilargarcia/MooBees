<?php

// app/Http/Controllers/ReviewController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReviewController extends Controller
{
    use AuthorizesRequests; // Asegúrate de incluir este trait
    
    public function create($movieId)
    {
        return view('reviews.create', compact('movieId'));
    }

    public function store(Request $request, $movieId)
    {
        $request->validate([
            'comment' => 'required|string|max:200',
            'rating' => 'required|integer|min:1|max:5', // Validar que el rating esté entre 1 y 5
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

    public function edit(Review $review)
    {
        $this->authorize('update', $review);
        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);
        $request->validate([
            'comment' => 'required|max:200',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review->update($request->all());

        return redirect()->route('profile.show', auth()->user()->username)
                         ->with('success', 'Reseña actualizada correctamente.');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        $review->delete();

        return redirect()->route('profile.show', auth()->user()->username)
                         ->with('success', 'Reseña eliminada correctamente.');
    }
}
