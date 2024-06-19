<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Review; // Add this line
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $reportCount = Report::count();
        $reports = Report::with('user', 'review')->get();

        return view('reports.index', compact('reportCount', 'reports'));
    }

    public function store(Request $request, $reviewId)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $review = Review::findOrFail($reviewId);

        Report::create([
            'user_id' => Auth::id(),
            'review_id' => $review->id,
            'reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'Reseña reportada.');
    }

    public function accept($reportId)
    {
        $report = Report::findOrFail($reportId);
        $report->delete();

        return redirect()->back()->with('success', 'Reporte aceptado.');
    }

    public function delete($reportId)
    {
        $report = Report::findOrFail($reportId);
        $review = Review::findOrFail($report->review_id);

        $review->delete(); // Eliminar la reseña
        $report->delete(); // Eliminar el reporte

        return redirect()->back()->with('success', 'Reporte y reseña eliminados.');
    }
}

