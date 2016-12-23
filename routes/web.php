<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('users', 'UsersController@getInfos');
Route::post('users', 'UsersController@postInfos');

Route::get('crawler', 'Crawler@getForm');
Route::post('crawler', 'Crawler@postForm');
