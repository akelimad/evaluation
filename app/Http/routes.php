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

Route::get('user/{id}', 'UserController@show');
Route::get('users/form', 'UserController@formUser');
Route::post('users/store', 'UserController@storeUser');

Route::group(['prefix' => '/', 'middleware' => ['role:ADMIN|RH']], function() {
	Route::get('users', 'UserController@indexUsers');
	Route::delete('user/{id}/delete', 'UserController@deleteUser');
	Route::get('users/import', 'UserController@importUsers');
	Route::post('users/import_parse', 'UserController@parseImport');
	Route::post('users/import_process', 'UserController@processImport');
	Route::get('entretiens/index', 'EntretienController@indexEntretien');
	Route::get('entretiens/{id}/show', 'EntretienController@show');
	Route::get('entretiens/evaluations', 'EntretienController@entretiensEval');
	Route::get('entretiens/calendar', 'EntretienController@calendar');
});

Route::group(['prefix' => '/', 'middleware' => ['role:ADMIN']], function() {
	Route::get('config/surveys', 'SurveyController@index')->name('surveys-list');
	Route::get('surveys/form', 'SurveyController@form');
	Route::post('surveys/store', 'SurveyController@store');
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

	Route::get('config/skills', 'SkillController@indexAdmin');
	Route::get('skills/create', 'SkillController@create');
	Route::post('skills/store', 'SkillController@store');
	Route::get('skills/{id}/edit', 'SkillController@edit');
	Route::delete('skills/{eid}/delete', 'SkillController@destroy');

	Route::get('config/emails', 'EmailController@index');
	Route::get('emails/form', 'EmailController@form');
	Route::post('emails/store', 'EmailController@store');
	Route::delete('emails/{id}/delete', 'EmailController@delete');

	Route::get('config/settings/general', 'SettingController@general');

	Route::get('config/setting/departments', 'DepartmentController@index');
	Route::get('department/form', 'DepartmentController@form');
	Route::post('department/store', 'DepartmentController@store');
	Route::delete('department/delete', 'DepartmentController@delete');

	Route::get('config/setting/functions', 'FonctionController@index');
	Route::get('function/form', 'FonctionController@form');
	Route::post('function/store', 'FonctionController@store');
	Route::delete('function/delete', 'FonctionController@delete');

	Route::get('config/roles', 'UserController@indexRoles');
	Route::get('role/create', 'UserController@createRole');
	Route::post('role/store', 'UserController@storeRole');
	Route::get('role/{id}/edit', 'UserController@editRole');

	Route::get('permissions', 'UserController@indexPermisions');
	Route::get('permission/create', 'UserController@createPermission');
	Route::post('permission/store', 'UserController@storePermission');
	Route::get('permission/{id}/edit', 'UserController@editPermission');

});

Route::group(['prefix' => '/', 'middleware' => ['role:ROOT']], function() {
	Route::get('crm', 'CrmController@index');
	Route::delete('crm/{id}/delete', 'CrmController@delete');
	Route::delete('crm/logo/remove', 'CrmController@removeLogo');
});
Route::group(['prefix' => '/', 'middleware' => ['role:ROOT|ADMIN']], function() {
	Route::get('crm/form', 'CrmController@form');
	Route::post('crm/store', 'CrmController@store');
});

Route::post('entretiens/storeEntretienEvals', 'EntretienController@storeEntretienEvals'); 
Route::get('entretiens/list', 'EntretienController@entretiensList'); 
Route::put('entretiens/{eid}/u/{uid}/updateMotif', 'EntretienController@updateMotif');

Route::get('entretiens/form', 'EntretienController@form');
Route::post('entretiens/store', 'EntretienController@store');
Route::post('entretiens/storeCheckedUsers', 'EntretienController@storeCheckedUsers');
Route::get('entretiens/{e_id}/u/{uid}', 'EntretienController@synthese');
Route::get('notifyUserInterview/{eid}/{uid}', 'EntretienController@notifyUserInterview');
Route::post('notifyMentorInterview/{eid}/{uid}', 'EntretienController@notifyMentorInterview');
Route::post('notifyMentorsInterview', 'EntretienController@notifyMentorsInterview');
Route::get('entretiens/{eid}/u/{uid}/appercu', 'EntretienController@apercu')->name('entretien.apercu');
Route::delete('entretiens/{eid}/delete', 'EntretienController@destroy');
Route::get('entretiens/{eid}/u/{uid}/printPdf', 'EntretienController@printPdf');
Route::get('entretiens/{id}/notation/download', 'EntretienController@downloadNotation');

Route::get('entretiens/{e_id}/u/{uid}/evaluations', 'EvaluationController@index');


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
Route::get('entretienObjectif/{oid}/groupes/{gid}/subobjectifs/{subObjId}/form', 'ObjectifController@subObjectifForm');
Route::post('entretienObjectif/{oid}/groupes/store', 'ObjectifController@store');
Route::get('entretienObjectif/{oid}/groupes/{gid}/edit', 'ObjectifController@edit');
Route::delete('entretienObjectif/{oid}/groupes/{gid}/delete', 'ObjectifController@destroy');


Route::get('entretiens/{e_id}/u/{uid}/formations', 'FormationController@index');
Route::get('entretiens/{e_id}/formations/create', 'FormationController@create');
Route::post('entretiens/{e_id}/formations/store', 'FormationController@store');
Route::get('entretiens/{e_id}/formations/{id}/edit', 'FormationController@edit');
Route::put('entretiens/formations/{id}/mentorUpdate', 'FormationController@update'); //update status & realise

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
Route::put('entretiens/{eid}/u/{user}/submit', 'EntretienController@submission');

Route::post('answers/store', 'AnswerController@store');

Route::post('config/settings/store', 'SettingController@store');
