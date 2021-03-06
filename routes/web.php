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

Auth::routes();

Route::get('/', 'Web\HomeController@welcome')->name('welcome')->middleware('guest');
Route::get('/home', 'Web\HomeController@index')->name('home')->middleware('auth:web');
Route::get('/oauth', 'Web\HomeController@oauth')->name('oauth')->middleware('auth:web');
Route::get('/unregister', 'Web\HomeController@unregister')->name('unregister')->middleware('auth:web');
