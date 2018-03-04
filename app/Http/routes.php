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
Route::get('users/filter', 'UserController@filterUsers');
Route::get('user/create', 'UserController@createUser');
Route::get('user/{id}/edit', 'UserController@createUser');
Route::post('user/store', 'UserController@storeUser');
Route::get('user/{id}', 'UserController@show');
Route::delete('user/{id}/delete', 'UserController@deleteUser');
Route::get('users/import', 'UserController@importUsers');
Route::post('users/import_parse', 'UserController@parseImport');
Route::post('users/import_process', 'UserController@processImport');

Route::get('roles', 'UserController@indexRoles');
Route::get('role/create', 'UserController@createRole');
Route::post('role/store', 'UserController@storeRole');
Route::get('role/{id}/edit', 'UserController@editRole');

Route::get('permissions', 'UserController@indexPermisions');
Route::get('permission/create', 'UserController@createPermission');
Route::post('permission/store', 'UserController@storePermission');
Route::get('permission/{id}/edit', 'UserController@editPermission');

Route::get('entretiens', 'EntretienController@index'); //index
Route::get('entretiens/list', 'EntretienController@entretiensList'); //index
Route::get('entretiens/evaluations', 'EntretienController@entretiensEval'); //mes entretiens

Route::get('entretiens/{type}/create', 'EntretienController@create');
Route::post('entretiens/store', 'EntretienController@store');
Route::post('entretiens/storeCheckedUsers', 'EntretienController@storeCheckedUsers');
Route::get('entretiens/{e_id}', 'EntretienController@show');
Route::get('entretiens/{e_id}/edit', 'EntretienController@editEntretien');

Route::get('entretiens/{e_id}/activites', 'ActiviteController@index');
Route::get('entretiens/{e_id}/activites/create', 'ActiviteController@create');
Route::post('entretiens/{e_id}/activites/store', 'ActiviteController@store');
Route::get('entretiens/{e_id}/activites/{id}/edit', 'ActiviteController@edit');

Route::get('entretiens/{e_id}/skills', 'SkillController@index');
Route::get('entretiens/{e_id}/skills/create', 'SkillController@create');
Route::post('entretiens/{e_id}/skills/store', 'SkillController@store');
Route::get('entretiens/{e_id}/skills/{id}/edit', 'SkillController@edit');

Route::get('entretiens/{e_id}/objectifs', 'ObjectifController@index');
Route::get('entretiens/{e_id}/objectifs/create', 'ObjectifController@create');
Route::post('entretiens/{e_id}/objectifs/store', 'ObjectifController@store');
Route::get('entretiens/{e_id}/objectifs/{id}/edit', 'ObjectifController@edit');

Route::get('entretiens/{e_id}/formations', 'FormationController@index');
Route::get('entretiens/{e_id}/formations/create', 'FormationController@create');
Route::post('entretiens/{e_id}/formations/store', 'FormationController@store');
Route::get('entretiens/{e_id}/formations/{id}/edit', 'FormationController@edit');

Route::get('entretiens/{e_id}/documents', 'DocumentController@index');
Route::get('entretiens/{e_id}/documents/create', 'DocumentController@create');
Route::post('entretiens/{e_id}/documents/store', 'DocumentController@store');
Route::get('entretiens/{e_id}/documents/{id}/edit', 'DocumentController@edit');

Route::get('entretiens/{e_id}/remunerations', 'RemunerationController@index');
Route::get('entretiens/{e_id}/remunerations/create', 'RemunerationController@create');
Route::post('entretiens/{e_id}/remunerations/store', 'RemunerationController@store');
Route::get('entretiens/{e_id}/remunerations/{id}/edit', 'RemunerationController@edit');

Route::get('entretiens/{e_id}/comments', 'CommentController@index');
Route::get('entretiens/{e_id}/comments/create', 'CommentController@create');
Route::post('entretiens/{e_id}/comments/store', 'CommentController@store');
Route::get('entretiens/{e_id}/comments/{id}/edit', 'CommentController@edit');


Route::get('entretiens/{e_id}/decisions', 'DecisionController@index');
Route::get('entretiens/{e_id}/decisions/create', 'DecisionController@create');
Route::post('entretiens/{e_id}/decisions/store', 'DecisionController@store');
Route::get('entretiens/{e_id}/decisions/{id}/edit', 'DecisionController@edit');

Route::get('groupes', 'GroupeController@index');
Route::get('groupes/create', 'GroupeController@create');
Route::post('groupes/store', 'GroupeController@store');
Route::get('groupes/{id}/edit', 'GroupeController@edit');

Route::get('groupes/{gid}/questions', 'QuestionController@index');
Route::get('groupes/{gid}/questions/create', 'QuestionController@create');
Route::post('groupes/{gid}/questions/store', 'QuestionController@store');
Route::get('groupes/{gid}/questions/{qid}/edit', 'QuestionController@edit');
Route::get('groupes/{gid}/questions/{qid}', 'QuestionController@show');

Route::get('questions/preview', 'QuestionController@preview');

