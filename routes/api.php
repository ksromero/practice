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
//List all Businesses
Route::get('businesses', 'BusinessController@index');

//Create new Entity
Route::post('business', 'BusinessController@store');

//Update Entity
Route::put('business', 'BusinessController@store');

//List single Business
Route::get('business/{id}', 'BusinessController@show');

//Delete Business
Route::delete('business/{id}', 'BusinessController@destroy');


