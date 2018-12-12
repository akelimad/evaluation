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
Route::get('/home', 'HomeController@index');
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

Route::get('config/roles', 'UserController@indexRoles');
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
Route::put('entretiens/{eid}/u/{uid}/updateMotif', 'EntretienController@updateMotif'); //mes entretiens
Route::get('entretiens/filter', 'EntretienController@filterEntretiens');
Route::get('entretiens/calendar', 'EntretienController@calendar');

Route::get('entretiens/{type}/create', 'EntretienController@create');
Route::post('entretiens/store', 'EntretienController@store');
Route::post('entretiens/storeCheckedUsers', 'EntretienController@storeCheckedUsers');
Route::get('entretiens/{e_id}/u/{uid}', 'EntretienController@show');
Route::get('entretiens/{e_id}/edit', 'EntretienController@editEntretien');
Route::get('notifyUserInterview/{eid}/{uid}', 'EntretienController@notifyUserInterview');
Route::post('notifyMentorInterview/{eid}/{uid}', 'EntretienController@notifyMentorInterview');
Route::post('notifyMentorsInterview', 'EntretienController@notifyMentorsInterview');
Route::get('entretiens/{eid}/u/{uid}/appercu', 'EntretienController@apercu');
Route::delete('entretiens/{eid}/delete', 'EntretienController@destroy');
Route::get('entretiens/{eid}/u/{uid}/printPdf', 'EntretienController@printPdf');

Route::get('entretiens/{e_id}/u/{uid}/evaluations', 'ActiviteController@index');
Route::get('entretiens/{e_id}/activites/create', 'ActiviteController@create');
Route::post('entretiens/{e_id}/activites/store', 'ActiviteController@store');
Route::get('entretiens/{e_id}/activites/{id}/edit', 'ActiviteController@edit');

Route::get('config/skills', 'SkillController@indexAdmin');
Route::get('skills/create', 'SkillController@create');
Route::post('skills/store', 'SkillController@store');
Route::get('skills/{id}/edit', 'SkillController@edit');
Route::delete('skills/{eid}/delete', 'SkillController@destroy');
Route::get('entretiens/{e_id}/u/{uid}/competences', 'SkillController@index');
Route::get('skills/updateUserSkills', 'SkillController@updateUserSkills');

Route::get('entretiens/{e_id}/u/{uid}/objectifs', 'ObjectifController@index');
Route::get('entretiens/{e_id}/objectifs/{id}/edit', 'ObjectifController@edit');
Route::get('objectifs', 'ObjectifController@indexAdmin');
Route::get('objectifs/updateNoteObjectifs', 'ObjectifController@updateNoteObjectifs');

Route::get('config/entretienObjectif', 'EntretienObjectifController@index');
Route::get('entretienObjectif/create', 'EntretienObjectifController@create');
Route::get('entretienObjectif/{id}/edit', 'EntretienObjectifController@edit');
Route::post('entretienObjectif/store', 'EntretienObjectifController@store');
Route::get('entretienObjectif/{id}', 'EntretienObjectifController@show');
Route::delete('entretienObjectif/{id}/delete', 'EntretienObjectifController@destroy');

Route::get('entretienObjectif/{oid}/groupes', 'ObjectifController@indexAdmin');
Route::get('entretienObjectif/{oid}/groupes/create', 'ObjectifController@create');
Route::post('entretienObjectif/{oid}/groupes/store', 'ObjectifController@store');
Route::get('entretienObjectif/{oid}/groupes/{gid}/edit', 'ObjectifController@edit');
Route::delete('entretienObjectif/{oid}/groupes/{gid}/delete', 'ObjectifController@destroy');

Route::get('entretiens/{e_id}/u/{uid}/formations', 'FormationController@index');
Route::get('entretiens/{e_id}/formations/create', 'FormationController@create');
Route::post('entretiens/{e_id}/formations/store', 'FormationController@store');
Route::get('entretiens/{e_id}/formations/{id}/edit', 'FormationController@edit');
Route::put('entretiens/formations/{id}/mentorUpdate', 'FormationController@update'); //update status & realise

Route::get('entretiens/{e_id}/documents', 'DocumentController@index');
Route::get('entretiens/{e_id}/documents/create', 'DocumentController@create');
Route::post('entretiens/{e_id}/documents/store', 'DocumentController@store');
Route::get('entretiens/{e_id}/documents/{id}/edit', 'DocumentController@edit');

Route::get('entretiens/{eid}/u/{uid}/salaires', 'SalarieController@index');
Route::get('entretiens/{eid}/u/{uid}/salaires/create', 'SalarieController@create');
Route::post('entretiens/{eid}/u/{uid}/salaires/store', 'SalarieController@store');
Route::get('entretiens/{eid}/u/{uid}/salaires/{id}/edit', 'SalarieController@edit');

Route::get('entretiens/{eid}/u/{uid}/commentaires', 'CommentController@index');
Route::get('entretiens/{eid}/u/{uid}/commentaires/create', 'CommentController@create');
Route::post('entretiens/{eid}/u/{uid}/commentaires/store', 'CommentController@store');
Route::get('entretiens/{eid}/u/{uid}/commentaires/{id}/edit', 'CommentController@edit');
Route::put('entretiens/{eid}/u/{uid}/commentaires/{cid}/mentorUpdate', 'CommentController@mentorUpdate');

Route::get('entretiens/{eid}/u/{uid}/carrieres', 'CarreerController@index');
Route::get('entretiens/{eid}/u/{uid}/carrieres/create', 'CarreerController@create');
Route::post('entretiens/{eid}/u/{uid}/carrieres/store', 'CarreerController@store');
Route::get('entretiens/{eid}/u/{uid}/carrieres/{id}/edit', 'CarreerController@edit');
Route::put('entretiens/{eid}/u/{uid}/carrieres/{cid}/mentorUpdate', 'CarreerController@mentorUpdate');

Route::get('config/surveys', 'SurveyController@index')->name('surveys-list');
Route::get('surveys/create', 'SurveyController@create');
Route::post('surveys/store', 'SurveyController@store');
Route::get('surveys/{id}/edit', 'SurveyController@edit');
Route::delete('surveys/{id}/delete', 'SurveyController@destroy');
Route::get('surveys/{id}', 'SurveyController@show');

Route::get('surveys/{sid}/groupes', 'GroupeController@index');
Route::get('surveys/{sid}/groupes/create', 'GroupeController@create');
Route::post('surveys/{sid}/groupes/store', 'GroupeController@store');
Route::get('surveys/{sid}/groupes/{gid}/edit', 'GroupeController@edit');
Route::delete('surveys/{sid}/groupes/{gid}/delete', 'GroupeController@destroy');

Route::get('surveys/{sid}/groupes/{gid}/questions', 'QuestionController@index');
Route::get('surveys/{sid}/groupes/{gid}/questions/create', 'QuestionController@create');
Route::post('surveys/{sid}/groupes/{gid}/questions/store', 'QuestionController@store');
Route::get('surveys/{sid}/groupes/{gid}/questions/{qid}/edit', 'QuestionController@edit');
Route::get('surveys/{sid}/groupes/{gid}/questions/{qid}', 'QuestionController@show');
Route::delete('surveys/{sid}/groupes/{gid}/questions/{qid}/delete', 'QuestionController@destroy');
Route::get('surveys/{sid}/groupes/{gid}/questions', 'QuestionController@index');
Route::post('answers/store', 'AnswerController@store');

Route::get('config/emails', 'EmailController@index');
Route::get('emails/create', 'EmailController@create');
Route::post('emails/store', 'EmailController@store');
Route::get('emails/{id}', 'EmailController@show');
Route::get('emails/{id}/edit', 'EmailController@edit');
Route::delete('emails/{id}/delete', 'EmailController@delete');

Route::get('emailActions', 'ActionController@index');
Route::get('emailActions/create', 'ActionController@create');
Route::post('emailActions/store', 'ActionController@store');
Route::get('emailActions/{id}', 'ActionController@show');
Route::get('emailActions/{id}/edit', 'ActionController@edit');
Route::delete('emailActions/{id}/delete', 'ActionController@delete');
Route::post('emails/actions/{actionId}/attach', 'ActionController@attachEmailAtion');

Route::get('config/settings', 'SettingController@index');
Route::get('setting/{id}/edit', 'SettingController@edit');
Route::post('setting/{id}/store', 'SettingController@store');

Route::get('crm', 'UserController@indexCrm');