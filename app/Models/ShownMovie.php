<?php

// app/Models/ShownMovie.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShownMovie extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id'];
}
