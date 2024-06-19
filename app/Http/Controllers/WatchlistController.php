<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\WatchlistItem;
use App\Models\Report; // Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlists = Auth::user()->watchlists;
        $reportCount = Report::count();
        return view('watchlists.index', compact('watchlists', 'reportCount'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        Watchlist::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Watchlist creada.');
    }

    public function addMovie(Request $request, $watchlist)
    {
        $request->validate([
            'movie_id' => 'required|integer',
            'movie_title' => 'required|string',
            'movie_poster_path' => 'required|string'
        ]);
    
        // Debugging purpose
        // dd($request->all());
    
        $watchlist = Watchlist::where('id', $watchlist)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    
        WatchlistItem::create([
            'watchlist_id' => $watchlist->id,
            'movie_id' => $request->movie_id,
            'movie_title' => $request->movie_title,
            'movie_poster_path' => $request->movie_poster_path
        ]);
    
        return redirect()->back()->with('success', 'Película añadida a la Watchlist.');
    }

    public function removeMovie($watchlistId, $itemId)
{
    $watchlist = Watchlist::where('id', $watchlistId)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $item = WatchlistItem::where('watchlist_id', $watchlistId)->findOrFail($itemId);
    $item->delete();

    return redirect()->back()->with('success', 'Película eliminada de la Watchlist.');
}

    public function show($watchlistId)
    {
        $watchlist = Watchlist::with('items')->findOrFail($watchlistId);
        return view('watchlists.show', compact('watchlist'));
    }

    public function destroy($id)
{
    $watchlist = Watchlist::where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $watchlist->delete();

    return redirect()->back()->with('success', 'Watchlist eliminada.');
}

}
