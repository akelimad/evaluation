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

Route::auth();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@home');

Route::get('profile', 'UserController@profile');
Route::get('users', 'UserController@indexUsers');
Route::get('user/create', 'UserController@createUser');
Route::post('user/store', 'UserController@storeUser');
Route::get('user/{id}', 'UserController@show');

Route::get('roles', 'UserController@indexRoles');
Route::get('role/create', 'UserController@createRole');
Route::post('role/store', 'UserController@storeRole');

Route::get('permissions', 'UserController@indexPermisions');
Route::get('permission/create', 'UserController@createPermission');
Route::post('permission/store', 'UserController@storePermission');

Route::get('entretiens', 'EntretienController@index');
Route::get('entretiens/evaluations', 'EntretienController@entretiensEval');
Route::get('entretiens/professionnels', 'EntretienController@entretiensProf');
Route::get('entretiens', 'EntretienController@index');
Route::get('entretiens/evaluation/create', 'EntretienController@createEval');
Route::get('entretiens/professionnel/create', 'EntretienController@createProf');
Route::post('entretiens/store', 'EntretienController@store');
Route::get('entretiens/{type}/{id}', 'EntretienController@showEntretien');

Route::get('entretiens/{type}/{id}/activites', 'ActiviteController@index');
Route::get('entretiens/activites/create', 'ActiviteController@create');
Route::post('entretiens/activites/store', 'ActiviteController@store');
Route::get('entretiens/activites/{id}/edit', 'ActiviteController@edit');
