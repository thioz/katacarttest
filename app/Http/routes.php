<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('demo.index');
});

Route::get('/cart', ['uses' => 'CartController@index']);
Route::get('/rules', ['uses' => 'RulesController@index']);


Route::get('/api/products', ['uses' => 'ApiProductsController@index']);
Route::get('/api/products/rules', ['uses' => 'ApiProductsController@rules']);
Route::post('/api/products/update', ['uses' => 'ApiProductsController@update']);
