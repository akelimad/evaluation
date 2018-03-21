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
Route::get('/dashboard', 'HomeController@dashboard');

Route::get('profile', 'UserController@profile');
Route::get('users', 'UserController@indexUsers');
Route::get('users/filter', 'UserController@filterUsers');
Route::get('user/create', 'UserController@createUser');
Route::get('user/{id}/edit', 'UserController@editUser');
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
Route::get('entretiens/index', 'EntretienController@indexEntretien'); //index
Route::post('entretiens/storeEntretienEvals', 'EntretienController@storeEntretienEvals'); 
Route::get('entretiens/list', 'EntretienController@entretiensList'); 
Route::get('entretiens/evaluations', 'EntretienController@entretiensEval'); //mes entretiens
Route::put('entretiens/{id}/update', 'EntretienController@update'); //mes entretiens

Route::get('entretiens/{type}/create', 'EntretienController@create');
Route::post('entretiens/store', 'EntretienController@store');
Route::post('entretiens/storeCheckedUsers', 'EntretienController@storeCheckedUsers');
Route::get('entretiens/{e_id}/u/{uid}', 'EntretienController@show');
Route::get('entretiens/{e_id}/edit', 'EntretienController@editEntretien');
Route::get('notifyUserInterview/{eid}/{uid}', 'EntretienController@notifyUserInterview');

Route::get('entretiens/{e_id}/u/{uid}/evaluations', 'ActiviteController@index');
Route::get('entretiens/{e_id}/activites/create', 'ActiviteController@create');
Route::post('entretiens/{e_id}/activites/store', 'ActiviteController@store');
Route::get('entretiens/{e_id}/activites/{id}/edit', 'ActiviteController@edit');

Route::get('skills', 'SkillController@indexAdmin');
Route::get('skills/create', 'SkillController@create');
Route::post('skills/store', 'SkillController@store');
Route::get('skills/{id}/edit', 'SkillController@edit');
Route::get('entretiens/{e_id}/u/{uid}/Competences', 'SkillController@index');
Route::get('skills/updateUserSkills', 'SkillController@updateUserSkills');

Route::get('entretiens/{e_id}/u/{uid}/objectifs', 'ObjectifController@index');
Route::get('entretiens/{e_id}/objectifs/{id}/edit', 'ObjectifController@edit');
Route::get('objectifs', 'ObjectifController@indexAdmin');
Route::get('objectifs/updateNoteObjectifs', 'ObjectifController@updateNoteObjectifs');

Route::get('entretienObjectif/{oid}/objectifs/create', 'ObjectifController@create');
Route::post('entretienObjectif/{oid}/objectifs/store', 'ObjectifController@store');

Route::get('entretienObjectif', 'EntretienObjectifController@index');
Route::get('entretienObjectif/create', 'EntretienObjectifController@create');
Route::get('entretienObjectif/{id}/edit', 'EntretienObjectifController@edit');
Route::post('entretienObjectif/store', 'EntretienObjectifController@store');
Route::get('entretienObjectif/{oid}', 'EntretienObjectifController@show');

Route::get('entretiens/{e_id}/u/{uid}/formations', 'FormationController@index');
Route::get('entretiens/{e_id}/formations/create', 'FormationController@create');
Route::post('entretiens/{e_id}/formations/store', 'FormationController@store');
Route::get('entretiens/{e_id}/formations/{id}/edit', 'FormationController@edit');
Route::put('entretiens/formations/{id}/mentorUpdate', 'FormationController@update'); //update status & realise

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


Route::get('surveys', 'SurveyController@index')->name('surveys-list');
Route::get('surveys/create', 'SurveyController@create');
Route::post('surveys/store', 'SurveyController@store');
Route::get('surveys/{id}/edit', 'SurveyController@edit');
Route::delete('surveys/{id}/delete', 'SurveyController@delete');
Route::get('surveys/{id}', 'SurveyController@show');

Route::get('surveys/{sid}/groupes', 'GroupeController@index');
Route::get('surveys/{sid}/groupes/create', 'GroupeController@create');
Route::post('surveys/{sid}/groupes/store', 'GroupeController@store');
Route::get('surveys/{sid}/groupes/{gid}/edit', 'GroupeController@edit');

Route::get('surveys/{sid}/groupes/{gid}/questions', 'QuestionController@index');
Route::get('surveys/{sid}/groupes/{gid}/questions/create', 'QuestionController@create');
Route::post('surveys/{sid}/groupes/{gid}/questions/store', 'QuestionController@store');
Route::get('surveys/{sid}/groupes/{gid}/questions/{qid}/edit', 'QuestionController@edit');
Route::get('surveys/{sid}/groupes/{gid}/questions/{qid}', 'QuestionController@show');
Route::get('surveys/{sid}/groupes/{gid}/questions', 'QuestionController@index');

Route::post('answers/store', 'AnswerController@store');