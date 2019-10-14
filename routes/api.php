<?php

use Illuminate\Http\Request;

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
// routes/api.php
//Create
Route::post('products', "ProductController@store");
//Find one element
Route::get('products/{product}', "ProductController@show");
//List all
Route::get('products', "ProductController@index");
//Update
Route::put('products/{product}', "ProductController@update");
//Delete
Route::delete('products/{product}', "ProductController@destroy");
