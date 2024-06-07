<?php

// database/migrations/xxxx_xx_xx_create_shown_movies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShownMoviesTable extends Migration
{
    public function up()
    {
        Schema::create('shown_movies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shown_movies');
    }
}

