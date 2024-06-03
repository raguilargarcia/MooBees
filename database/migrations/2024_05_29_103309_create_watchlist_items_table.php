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
            $table->foreignId('watchlist_id')->constrained()->onDelete('cascade');
            $table->integer('movie_id');
            $table->string('movie_title');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('watchlist_items');
    }
}