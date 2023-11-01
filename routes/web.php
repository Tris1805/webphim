<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\Auth\LogoutController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [IndexController::class, 'home'])->name('homepage');
Route::get('/danh-muc/{slug}', [IndexController::class, 'category'])->name('category');
Route::get('the-loai/{slug}', [IndexController::class, 'genre'])->name('genre');
Route::get('/quoc-gia/{slug}', [IndexController::class, 'country'])->name('country');
Route::get('/phim/{slug}', [IndexController::class, 'movie'])->name('movie');
Route::get('/xem-phim/{slug}/{tap}', [IndexController::class, 'watch'])->name('watch');
Route::get('/so-tap', [IndexController::class, 'episode'])->name('so-tap');
Route::get('/nam/{year}', [IndexController::class, 'year']);
Route::get('/tag/{tag}', [IndexController::class, 'tag']);
Route::get('/tim-kiem}', [IndexController::class, 'timkiem'])->name('tim-kiem');
Route::get('/filter}', [IndexController::class, 'filter'])->name('filter');


Auth::routes();
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');


Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resource('category', CategoryController::class);
Route::post('resorting', [CategoryController::class, 'resorting'])->name('resorting');
Route::resource('genre', GenreController::class);
Route::resource('country', CountryController::class);
Route::resource('episode', EpisodeController::class);
Route::get('select-movie', [EpisodeController::class, 'select_movie'])->name('select-movie');
Route::get('add-episode/{id}', [EpisodeController::class, 'add_episode'])->name('add-episode');
Route::resource('info', InfoController::class);

Route::resource('movie', MovieController::class);
Route::get('/update-year-phim', [MovieController::class, 'update_year']);
Route::get('/update-topview-phim', [MovieController::class, 'update_topview']);
Route::post('/filter-topview-phim', [MovieController::class, 'filter_topview']);
Route::get('/filter-topview-default', [MovieController::class, 'filter_default']);
Route::get('/update-season-phim', [MovieController::class, 'update_season']);

Route::get('choose-category', [MovieController::class, 'choose_category'])->name('choose-category');
Route::get('choose-country', [MovieController::class, 'choose_country'])->name('choose-country');
Route::get('choose-status', [MovieController::class, 'choose_status'])->name('choose-status');
Route::get('choose-ToM', [MovieController::class, 'choose_ToM'])->name('choose-ToM');
Route::get('choose-resolution', [MovieController::class, 'choose_resolution'])->name('choose-resolution');
Route::post('choose-updateImage', [MovieController::class, 'choose_updateImage'])->name('choose-updateImage');
Route::post('add-rating', [IndexController::class, 'add_rating'])->name('add-rating');

Route::get('/create_sitemap', function () {
    return Artisan::call('sitemap:create');
});


Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

//Google Login Route
Route::get('auth/google', [\App\Http\Controllers\LoginGoogleController::class, 'redirectToGoogle'])->name('login-by-google');
Route::get('auth/google/callback', [\App\Http\Controllers\LoginGoogleController::class, 'handleGoogleCallback']);
Route::get('logout-home', [\App\Http\Controllers\LoginGoogleController::class, 'logout_home'])->name('logout-home');
