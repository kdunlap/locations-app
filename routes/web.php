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

Route::get( '/', 'LocationController@index' )->name('locations.index');
Route::get( '/{state_slug}/{city_slug}', 'LocationController@show' )->name('locations.show');
