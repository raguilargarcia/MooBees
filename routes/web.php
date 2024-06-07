<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WatchlistController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/peliculas/{id}', [MovieController::class, 'show'])->name('movies.show');

Route::get('/search/{query}/{page}', [SearchController::class, 'search'])->name('search');

Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('registro');
Route::post('/registro', [RegisterController::class, 'register']);

Route::get('/iniciar-sesion', [LoginController::class, 'showLoginForm'])->name('iniciar-sesion');
Route::post('/iniciar-sesion', [LoginController::class, 'login']);
Route::post('/cerrar-sesion', [LoginController::class, 'logout'])->name('cerrar-sesion');

Route::get('/cerrar-sesion', function () {
    Auth::logout();
    return redirect('/');
})->name('cerrar');

Route::get('/la-colmena-moobees', [GameController::class, 'showGame'])->name('showGame');
Route::post('/la-colmena-moobees/next-clue', [GameController::class, 'nextClue'])->name('nextClue');
Route::post('/la-colmena-moobees/guess', [GameController::class, 'guessMovie'])->name('guessMovie');
Route::get('/la-colmena-moobees/search', [GameController::class, 'searchMovies'])->name('searchMovies');

Route::get('/movies/{movie}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create')->middleware('auth');
Route::post('/movies/{movie}/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/perfil/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/perfil/follow/{user}', [ProfileController::class, 'follow'])->name('profile.follow');
    Route::post('/perfil/unfollow/{user}', [ProfileController::class, 'unfollow'])->name('profile.unfollow');
    Route::post('/perfil/search', [ProfileController::class, 'search'])->name('profile.search');
    Route::get('/perfil/{user}', [ProfileController::class, 'view'])->name('profile.view');
    Route::get('/profile/{user}/followers', [ProfileController::class, 'showFollowers'])->name('profile.followers');
    Route::get('/profile/{user}/followings', [ProfileController::class, 'showFollowings'])->name('profile.followings');
});

Route::middleware(['auth'])->group(function () {
    Route::post('reviews/{review}/toggle-like', [LikeController::class, 'toggleLike'])->name('reviews.toggle-like')->middleware('auth');
    Route::post('reviews/{review}/toggle-dislike', [LikeController::class, 'toggleDislike'])->name('reviews.toggle-dislike')->middleware('auth');
    Route::post('reviews/{review}/report', [ReportController::class, 'store'])->name('reviews.report');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

Route::post('/reviews/{review}/report', [ReportController::class, 'store'])->name('reviews.report')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/watchlists', [WatchlistController::class, 'index'])->name('watchlists.index');
    Route::post('/watchlists', [WatchlistController::class, 'store'])->name('watchlists.store');
    Route::get('/watchlists/{watchlist}', [WatchlistController::class, 'show'])->name('watchlists.show');
    Route::post('/watchlists/{watchlist}/add-movie', [WatchlistController::class, 'addMovie'])->name('watchlists.add_movie');
    Route::post('/watchlists/{watchlist}/remove-movie/{item}', [WatchlistController::class, 'removeMovie'])->name('watchlists.remove_movie');
    Route::delete('/watchlists/{id}', [WatchlistController::class, 'destroy'])->name('watchlists.destroy');

});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
