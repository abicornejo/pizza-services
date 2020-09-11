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
//
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'API\UserController@register');
    Route::post('login', 'API\UserController@login');
    Route::get('getOrdersByClient/{id}', 'ProductController@getOrdersByClient');
    Route::get('getDetailByOrderId/{id}', 'ProductController@getDetailByOrderId');
    Route::group(['middleware' => 'auth:api'], function() {
        Route::resource('pizza', 'ProductController');

        Route::get('getSizesByPizzaId/{id}', 'ProductController@getSizesByPizzaId');



        Route::get('getIngredientsByPizzaId/{id}', 'ProductController@getIngredientsByPizzaId');

        Route::post('purchasePizza', 'ProductController@purchasePizza');
    });
});

//Route::prefix('v1')->group(function () {
//    Route::prefix('auth')->group(function () {
//
//        Route::middleware('auth:api')->group(function () {
//
//        });
//
//    });
//});
//Route::middleware('auth:api')->group( function () {
//    //Route::resource('books', 'API\BookController');
//
//});