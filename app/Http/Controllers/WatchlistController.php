<?php
// app/Http/Controllers/WatchlistController.php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\WatchlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlists = Auth::user()->watchlists;
        return view('watchlists.index', compact('watchlists'));
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

    public function addMovie(Request $request, $watchlistId)
    {
        $request->validate(['movie_id' => 'required|integer']);

        WatchlistItem::create([
            'watchlist_id' => $watchlistId,
            'movie_id' => $request->movie_id,
        ]);

        return redirect()->back()->with('success', 'Película añadida a la Watchlist.');
    }

    public function removeMovie($watchlistId, $itemId)
    {
        $item = WatchlistItem::where('watchlist_id', $watchlistId)->findOrFail($itemId);
        $item->delete();

        return redirect()->back()->with('success', 'Película eliminada de la Watchlist.');
    }
}
