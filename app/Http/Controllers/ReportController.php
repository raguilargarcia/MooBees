<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Review; // Import the Review class
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        if (!Auth::user()->admin) {
            return redirect('/')->with('error', 'No tienes permisos para acceder a esta página.');
        }

        $reportCount = Report::count();
        $reports = Report::with('user', 'review')->get();

        return view('reports.index', compact('reportCount', 'reports'));
    }

    public function accept($reportId)
    {
        if (!Auth::user()->admin) {
            return redirect('/')->with('error', 'No tienes permisos para acceder a esta página.');
        }

        $report = Report::findOrFail($reportId);
        $report->delete();

        return redirect()->back()->with('success', 'Reporte aceptado.');
    }

    public function delete($reportId)
    {
        if (!Auth::user()->admin) {
            return redirect('/')->with('error', 'No tienes permisos para acceder a esta página.');
        }

        $report = Report::findOrFail($reportId);
        $review = Review::findOrFail($report->review_id);

        $review->delete(); // Eliminar la reseña
        $report->delete(); // Eliminar el reporte

        return redirect()->back()->with('success', 'Reporte y reseña eliminados.');
    }
}

