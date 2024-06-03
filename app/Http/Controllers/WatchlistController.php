<?php
// app/Http/Controllers/WatchlistController.php

// app/Http/Controllers/WatchlistController.php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\WatchlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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

    public function addMovie(Request $request)
    {
        $request->validate([
            'watchlist_id' => 'required|integer|exists:watchlists,id',
            'movie_id' => 'required|integer',
            'movie_title' => 'required|string'
        ]);

        $watchlist = Watchlist::where('id', $request->watchlist_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        WatchlistItem::create([
            'watchlist_id' => $request->watchlist_id,
            'movie_id' => $request->movie_id,
            'movie_title' => $request->movie_title,
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
}


