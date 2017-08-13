<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'queues', 'namespace' => 'Queues'], function() {
    Route::get('fetch-star-wars-entity', 'FetchStarWarsEntityController')
         ->name('queues.fetch-star-wars-entity');
});
