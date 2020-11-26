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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
Route::get('profile', 'UserController@profile')->name('profile');

Route::get('user/{id}', 'UserController@show')->name('user.profile');
Route::any('users/form', 'UserController@form')->name('user.form');
Route::post('users/store', 'UserController@store')->name('user.store');

Route::get('users', 'UserController@index')->name('users')->middleware('permission:users');
Route::get('users/table', 'UserController@getTable')->name('users.table');
Route::group(['prefix' => '/', 'middleware' => ['role:ADMIN|RH']], function() {
  Route::delete('user/delete', 'UserController@delete');
  Route::get('users/import', 'UserController@import')->name('users.import');
  Route::get('users/export', 'UserController@export')->name('users.export');
  Route::post('users/import_parse', 'UserController@parseImport');
  Route::post('users/import_process', 'UserController@processImport');
  Route::get('users/demography', 'UserController@demography')->name('users.demography');
  Route::get('campagnes/table', 'EntretienController@getTable')->name('entretiens.table');
  Route::get('campagnes', 'EntretienController@index')->name('entretiens');
  Route::get('entretiens/{id}/show', 'EntretienController@show')->name('entretien.show');
  Route::get('entretiens/evaluations', 'EntretienController@entretiensEval');
  Route::get('entretiens/calendar', 'EntretienController@calendar')->name('entretiens.calendar');
});

Route::group(['prefix' => '/', 'middleware' => ['role:ADMIN']], function() {
  Route::get('surveys/table', 'SurveyController@getTable')->name('surveys.table');
  Route::get('config/surveys', 'SurveyController@index')->name('surveys-list');
  Route::get('config/surveys/form', 'SurveyController@form')->name('survey.form');
  Route::post('surveys/store', 'SurveyController@store')->name('survey.store');
  Route::delete('survey/delete', 'SurveyController@delete')->name('survey.delete');
  Route::get('surveys/{id}/show', 'SurveyController@show')->name('survey.show');

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

  Route::get('skills/table', 'SkillController@getTable')->name('skills.table');
  Route::get('config/skills', 'SkillController@indexAdmin')->name('skills');
  Route::any('skills/form', 'SkillController@form')->name('skill.form');
  Route::post('skills/store', 'SkillController@store');
  Route::delete('skills/delete', 'SkillController@delete');

  Route::get('emails/table', 'EmailController@getTable')->name('emails.table');
  Route::get('config/emails', 'EmailController@index')->name('config.emails');
  Route::any('emails/form', 'EmailController@form')->name('email.form');
  Route::post('emails/store', 'EmailController@store')->name('email.store');
  Route::delete('emails/delete', 'EmailController@delete')->name('email.delete');

  Route::get('config/settings/general/table', 'SettingController@getTable')->name('general.settings.table');
  Route::get('config/settings/general', 'SettingController@general')->name('general.settings');
  Route::any('config/settings/general/form', 'SettingController@form')->name('general.settings.form');

  Route::get('departments/table', 'DepartmentController@getTable')->name('departments.table');
  Route::get('config/settings/departments', 'DepartmentController@index')->name('departments');
  Route::any('department/form', 'DepartmentController@form')->name('department.form');
  Route::post('department/store', 'DepartmentController@store');
  Route::delete('department/delete', 'DepartmentController@delete');

  Route::get('functions/table', 'FonctionController@getTable')->name('functions.table');
  Route::get('config/settings/functions', 'FonctionController@index')->name('functions');
  Route::any('function/form', 'FonctionController@form')->name('function.form');
  Route::post('function/store', 'FonctionController@store')->name('function.store');
  Route::delete('function/delete', 'FonctionController@delete')->name('function.delete');

});

Route::group(['prefix' => '/', 'middleware' => ['role:ROOT']], function() {
  Route::get('companies/table', 'CompanyController@getTable')->name('companies.table');
  Route::get('companies', 'CompanyController@index')->name('companies');
  Route::delete('companies/delete', 'CompanyController@delete')->name('companies.delete');
  Route::delete('companies/logo/remove', 'CompanyController@removeLogo')->name('company.remove-logo');

  Route::get('models/table', 'ModeleController@getTable')->name('models.table');
  Route::get('config/settings/models', 'ModeleController@index')->name('models');
  Route::any('model/form', 'ModeleController@form')->name('model.form');
  Route::post('model/store', 'ModeleController@store')->name('model.store');
  Route::delete('model/delete', 'ModeleController@delete')->name('model.delete');

  Route::get('roles/table', 'RoleController@getTable')->name('roles.table');
  Route::get('config/roles', 'RoleController@index')->name('config.roles');
  Route::any('role/form', 'RoleController@form')->name('role.form');
  Route::post('role/store', 'RoleController@store')->name('role.store');
  Route::delete('role/delete', 'RoleController@delete')->name('role.delete');

  Route::get('permissions/table', 'PermissionController@getTable')->name('permissions.table');
  Route::any('config/permissions', 'PermissionController@index')->name('permissions');
  Route::any('permission/form', 'PermissionController@form')->name('permission.form');
  Route::post('permission/store', 'PermissionController@store')->name('permission.store');
  Route::get('permission/delete', 'PermissionController@delete')->name('permission.delete');
});
Route::group(['prefix' => '/', 'middleware' => ['role:ROOT|ADMIN']], function() {
  Route::any('companies/form', 'CompanyController@form')->name('company.form');
  Route::post('companies/store', 'CompanyController@store')->name('company.store');
});

Route::get('entretiens/list', 'EntretienController@entretiensList');
Route::put('entretiens/{eid}/u/{uid}/updateMotif', 'EntretienController@updateMotif');

Route::get('entretiens/form', 'EntretienController@form');
Route::post('entretiens/store', 'EntretienController@store');
Route::post('entretiens/storeCheckedUsers', 'EntretienController@storeCheckedUsers');
Route::get('entretiens/{e_id}/u/{uid}/synthese', 'EntretienController@synthese')->name('anglets.synthese');
Route::get('notifyUserInterview/{eid}/{uid}', 'EntretienController@notifyUserInterview');
Route::post('notifyMentorInterview/{eid}/{uid}', 'EntretienController@notifyMentorInterview');
Route::post('notifyMentorsInterview', 'EntretienController@notifyMentorsInterview');
Route::delete('entretiens/delete', 'EntretienController@delete');
Route::get('entretiens/{id}/notation/download', 'EntretienController@downloadNotation');
Route::post('entretiens/change-status', 'EntretienController@changeStatus')->name('entretien.change-status');
Route::get('entretiens/{id}/clone', 'EntretienController@copier')->name('entretien.clone');
Route::get('entretiens/{id}/stats', 'EntretienController@stats')->name('entretien.stats');

Route::get('entretiens/{e_id}/u/{uid}/evaluation-annuelle', 'EvaluationController@index')->name('anglets.evaluation-annuelle');
Route::get('entretiens/{e_id}/u/{uid}/m/{mid}/feedback360', 'Feedback360Controller@index')->name('anglets.feedback360');



Route::get('entretiens/{e_id}/u/{uid}/competences', 'SkillController@index')->name('anglets.competences');
Route::post('skills/updateUserSkills', 'SkillController@updateUserSkills');

Route::get('entretiens/{e_id}/u/{uid}/objectifs', 'ObjectifController@index')->name('anglets.objectifs');
Route::get('entretiens/{e_id}/objectifs/{id}/edit', 'ObjectifController@edit');
Route::get('objectifs', 'ObjectifController@indexAdmin');
Route::post('objectifs/updateNoteObjectifs', 'ObjectifController@updateNoteObjectifs')->name('updateNoteObjectifs');

Route::get('config/objectifs', 'EntretienObjectifController@index')->name('objectifs');
Route::get('objectifs/table', 'EntretienObjectifController@getTable')->name('objectifs.table');
Route::get('objectif/form', 'EntretienObjectifController@form')->name('objectif.form');
Route::post('objectif/store', 'EntretienObjectifController@store')->name('objectif.store');
Route::get('objectif/{id}', 'EntretienObjectifController@show')->name('objectif.show');
Route::delete('objectif/delete', 'EntretienObjectifController@delete')->name('objectif.delete');

Route::get('formations/table', 'FormationController@getTable')->name('formations.table');
Route::get('entretiens/{e_id}/u/{uid}/formations', 'FormationController@index')->name('anglets.formations');
Route::any('entretiens/{e_id}/formations/create', 'FormationController@create')->name('formation.add');
Route::post('entretiens/{e_id}/formations/store', 'FormationController@store')->name('formation.store');
Route::any('entretiens/{e_id}/formations/{id}/edit', 'FormationController@edit')->name('formation.edit');
Route::delete('formations/delete', 'FormationController@delete')->name('formations.delete');
Route::put('entretiens/formations/{id}/mentorUpdate', 'FormationController@update'); //update status & realise

Route::get('primes/table', 'SalarieController@getTable')->name('primes.table');
Route::get('entretiens/{eid}/u/{uid}/primes', 'SalarieController@index')->name('anglets.primes');
Route::any('entretiens/{eid}/u/{uid}/salaires/create', 'SalarieController@create')->name('prime.add');
Route::post('entretiens/{eid}/u/{uid}/salaires/store', 'SalarieController@store')->name('prime.store');
Route::any('entretiens/{eid}/u/{uid}/salaires/{id}/edit', 'SalarieController@edit')->name('prime.edit');
Route::delete('primes/delete', 'SalarieController@delete')->name('prime.delete');

Route::get('entretiens/{eid}/u/{uid}/commentaires', 'CommentController@index')->name('anglets.commentaires');
Route::any('entretiens/{eid}/u/{uid}/commentaires/create', 'CommentController@create')->name('comment.add');
Route::post('entretiens/{eid}/u/{uid}/commentaires/store', 'CommentController@store')->name('comment.store');
Route::any('entretiens/{eid}/u/{uid}/commentaires/{id}/edit', 'CommentController@edit')->name('comment.edit');
Route::put('entretiens/{eid}/u/{uid}/commentaires/{cid}/mentorUpdate', 'CommentController@mentorUpdate')->name('mentor.replay');

Route::get('entretiens/{eid}/u/{uid}/carrieres', 'CarreerController@index')->name('anglets.carrieres');
Route::get('entretiens/{eid}/u/{uid}/carrieres/create', 'CarreerController@create');
Route::post('entretiens/{eid}/u/{uid}/carrieres/store', 'CarreerController@store');
Route::get('entretiens/{eid}/u/{uid}/carrieres/{id}/edit', 'CarreerController@edit');
Route::put('entretiens/{eid}/u/{uid}/carrieres/{cid}/mentorUpdate', 'CarreerController@mentorUpdate');
Route::put('entretiens/{eid}/u/{user}/submit', 'EntretienController@submission');

Route::post('answers/store', 'AnswerController@store');

Route::post('config/settings/store', 'SettingController@store');

Route::get('teams/table', 'TeamController@getTable')->name('teams.table');
Route::get('config/teams', 'TeamController@index')->name('teams');
Route::get('config/teams/{id}/get-users', 'TeamController@get')->name('get-users');
Route::any('configs/teams/form', 'TeamController@form')->name('team.form');
Route::post('configs/teams/store', 'TeamController@store')->name('team.store');
Route::delete('team/delete', 'TeamController@delete')->name('team.delete');

Route::get('entretiens/users/table', 'EntretienUserController@getTable')->name('entretien_user.table');
Route::get('entretiens/users/notes/table', 'EntretienUserController@getTableNotes')->name('entretien_user.notes.table');
Route::get('entretiens/{id}/appercu', 'EntretienUserController@apercu')->name('entretien.apercu');
Route::get('entretiens/{id}/printPdf', 'EntretienUserController@printPdf')->name('entretien.download-pdf');
Route::post('entretien/users/reminder', 'EntretienUserController@reminder')->name('entretien.users.reminder');
Route::delete('entretien/users/delete', 'EntretienUserController@delete')->name('entretien.users.delete');
Route::post('entretiens/answers/export', 'EntretienUserController@export')->name('entretien.users.export');
Route::any('entretiens/users/reopen', 'EntretienUserController@reopen')->name('entretien.users.reopen');


Route::get('table/translations', 'TranslationController@getTable')->name('interface.translations.table');
Route::get('interface/translations', 'TranslationController@index')->name('interface.translations');
Route::post('interface/translations/store', 'TranslationController@store')->name('translations.store');
Route::get('languages/table', 'LanguageController@getTable')->name('languages.table');
Route::get('languages', 'LanguageController@index')->name('languages');
Route::any('languages/form', 'LanguageController@form')->name('languages.form');

