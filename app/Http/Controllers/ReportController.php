<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request, $reviewId)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $review = Review::findOrFail($reviewId);

        $report = Report::create([
            'user_id' => Auth::id(),
            'review_id' => $review->id,
            'reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'Reseña reportada.');
    }
}
