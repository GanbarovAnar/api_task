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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('product/get', 'ProductController@get');
Route::get('product/id/{id}', 'ProductController@show');

Route::get('category/get', 'CategoryController@get');


Route::middleware(['auth:api'])->group(function () {
    Route::post('product/create', 'ProductController@create');
    Route::post('product/update/{id}', 'ProductController@update');
    Route::post('product/delete/{id}', 'ProductController@delete');

    Route::post('category/create', 'CategoryController@create');
    Route::post('category/update/{id}', 'CategoryController@update');
    Route::post('category/delete/{id}', 'CategoryController@delete');

});






