<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::match(['post'], '/timezone', ['as' => 'timezone', 'uses' => 'UserController@callGetUserTimezone']);

Route::group(['middleware' => ['guest']], function() {
	Route::match(['get', 'post'], '/',		['as' => 'user.login', 'uses' => 'UserController@callLogin']);
	Route::match(['get', 'post'], '/login', ['as' => 'user.login', 'uses' => 'UserController@callLogin']);
});

/* cllient routes */
Route::group(['middleware' => ['auth']], function () {

	// Route::get('/dashboard', 		['as' => 'client.dashboard', 'uses' => 'UserController@callShowUserDashboard']); 
	Route::get('/client/dashboard', ['as' => 'client.dashboard', 'uses' => 'ClientController@callShowClientDashboard']);
	Route::get('/client/profile', ['as' => 'client.profile', 'middleware' => ['auth'], function(){
		return \View::make('WebView::client.profile');
	}]);
	Route::get('/client/survey', ['as' => 'client.show-survey', 'middleware' => ['auth'], function(){
		return \View::make('WebView::client.surveys.show_survey');
	}]);
	Route::match(['get'], '/client/view-survey/{nIdSurvey}',	['as' => 'client.view-survey',		'uses' => 'ClientController@callShowSurvey']);
	Route::match(['get'], '/assignments/{sAssignmentStatus?}', 	['as' => 'client.assignment-list', 	'uses' => 'ClientController@callShowAssignmentList']);
	Route::get('/logout', ['as'	=> 'logout',	'uses'	=> 'UserController@callLogout']);

});

/* fieldrep routes */
Route::group(['prefix'=>'fieldrep', 'middleware' => ['auth']], function () {
	Route::match(['get'], '/dashboard', 						['as' => 'fieldrep.dashboard',		 'uses' => 'FieldRepController@callShowFieldRepDashboard']);

	/* assignment-routes */
	Route::match(['get'], '/assignments/{sAssignmentStatus?}', 	['as' => 'fieldrep.assignment-list', 'uses' => 'FieldRepController@callShowAssignmentList']);

	/* offers-routes */
	Route::match(['get'],  '/offers/{sOfferStatus?}', ['as' => 'fieldrep.offer-list',		'uses' => 'FieldRepController@callShowOfferList']);
	Route::match(['post'], '/offers/accept',		  ['as' => 'fieldrep.accept.offers',	'uses' => 'FieldRepController@callAcceptOffer']);
	Route::match(['post'], '/offers/reject',		  ['as' => 'fieldrep.reject.offers',	'uses' => 'FieldRepController@callRejectOffer']);

	/* survey routes */
	Route::match(['get'],  '/survey/{nIdSurvey}/{sClientCode}', ['as' => 'fill-survey',   'uses' => 'SurveyController@GetSurvey']);
	Route::match(['post'], '/save-survey',				 		['as' => 'save-survey',   'uses' => 'SurveyController@PostSurvey']);
	Route::match(['get'],  '/view-survey/{nIdSurvey}',			['as' => 'view-survey',	  'uses' => 'SurveyController@callShowSurvey']);
	
	/* documents routes */
	Route::match(['get', 'post'],	'/document-list/{nIdClient?}/{nIdDocument?}', 	['as' => 'fieldrep.document-list', 		'uses' => 'ResourceController@callGetDocumentList']);
	// Route::match(['get'],			'/file-preview/{nIdDocument?}',					['as' => 'document.file-preview', 	'uses' => 'ResourceController@callFilePreview']);

	/* account settings */
	Route::match(['get', 'post'], '/account-setting', ['as' => 'fieldrep.account-setting', 'uses' => 'FieldRepController@callAccountSetting']);
	Route::match(['get', 'post'], '/project_types',   ['as' => 'fieldrep.account-setting-project-types', 'uses' => 'FieldRepController@callShowFieldRepProjectType']);
});

Route::group(['middleware' => ['auth']], function () {
	Route::match(['post'], '/save-template',					['as' => 'save.template', 'uses' =>	'SurveyController@PostTemplate']);
});