<?php

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::get('/user', [NewsController::class, 'index']);
// Route::post('/insert_news', [NewsController::class, 'store']);
// Route::post('/update_news', [NewsController::class, 'update_news']);
// Route::post('/delete_news', [NewsController::class, 'delete_news']);
// Route::post('/show_news', [NewsController::class, 'show_news']);
// Route::post('/show_news', [NewsController::class, 'show_news']);
// Route::post('/insert_comments', [CommentsController::class, 'createComments']);
// Route::post('/show_comments', [CommentsController::class, 'showComments']);


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/insert_news', [NewsController::class, 'store']);
    Route::post('/update_news', [NewsController::class, 'update_news']);
    Route::post('/delete_news', [NewsController::class, 'delete_news']);
    Route::post('/show_news', [NewsController::class, 'show_news']);
    Route::post('/show_news', [NewsController::class, 'show_news']);
    Route::post('/insert_comments', [CommentsController::class, 'createComments']);
    Route::post('/show_comments', [CommentsController::class, 'showComments']);
});

// Route::group(['middleware' => 'auth:api'], function(){
// //     Route::get('/admin', 'AdminController@index');
//     Route::group(['namespace' => 'news'], function(){
// //         Route::post('/insert_news', [NewsController::class, 'store']);
//     Route::post('/update_news', [NewsController::class, 'update_news']);
//     Route::post('/delete_news', [NewsController::class, 'delete_news']);
//     Route::post('/show_news', [NewsController::class, 'show_news']);
//     Route::post('/show_news', [NewsController::class, 'show_news']);
//     Route::post('/insert_comments', [CommentsController::class, 'createComments']);
//     Route::post('/show_comments', [CommentsController::class, 'showComments']);
//     });
// });