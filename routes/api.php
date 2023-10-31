<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('register', 'App\Http\Controllers\API\RegisterController@register');
Route::post('login', 'App\Http\Controllers\API\RegisterController@login');
Route::middleware('auth:api')->group( function () {
    Route::post('products/import', 'App\Http\Controllers\API\ProductController@importCSV');
            Route::resource('products', 'App\Http\Controllers\API\ProductController');
            Route::post('/logout', 'App\Http\Controllers\API\RegisterController@logout');
});
