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
Route::middleware('auth:api')->post('product/create', 'ProductController@create');
Route::middleware('auth:api')->post('product/update/{id}', 'ProductController@update');
Route::middleware('auth:api')->post('product/delete/{id}', 'ProductController@delete');


Route::get('category/get', 'CategoryController@get');
Route::middleware('auth:api')->post('category/create', 'CategoryController@create');
Route::middleware('auth:api')->post('category/update/{id}', 'CategoryController@update');
Route::middleware('auth:api')->post('category/delete/{id}', 'CategoryController@delete');


