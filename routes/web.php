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

Route::get('/', 'ControleurRecherche\Recherche@getForm');

Route::get('resultat/{keywords}/{start}/', 'ControleurRecherche\Recherche@getResults');
Route::post('resultat', 'ControleurRecherche\Recherche@postForm');

Route::get('crawler', 'ControleurCrawler\Crawler@getForm');
Route::post('crawler', 'ControleurCrawler\Crawler@postForm');

Route::get('stats', 'ControleurCrawler\Statistique@getForm');
Route::post('stats', 'ControleurCrawler\Statistique@postForm');
