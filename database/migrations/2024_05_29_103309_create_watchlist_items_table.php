<?php

// database/migrations/xxxx_xx_xx_create_watchlist_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatchlistItemsTable extends Migration
{
    public function up()
    {
        Schema::create('watchlist_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('watchlist_id');
            $table->unsignedBigInteger('movie_id');
            $table->timestamps();

            $table->foreign('watchlist_id')->references('id')->on('watchlists')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('watchlist_items');
    }
}

