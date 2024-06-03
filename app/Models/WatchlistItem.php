<?php

// app/Models/WatchlistItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchlistItem extends Model
{
    use HasFactory;

    protected $fillable = ['watchlist_id', 'movie_id'];

    public function watchlist()
    {
        return $this->belongsTo(Watchlist::class);
    }
}

