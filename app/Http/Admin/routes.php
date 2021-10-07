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

/************************************************************* Artisan Routes *************************************************************/
Route::get('/route-list', function() {
    Artisan::call('route:list');
    dd(Artisan::output());
});

/************************************************************* Login Routes *************************************************************/
Route::group(['middleware' => ['guest']], function() {
	Route::match(['get', 'post'],	'/admin-login',		['as' => 'admin.login',					'uses' => 'LoginController@callLogin']);
});

Route::group(['middleware' => ['auth']], function () {
    /************************************************************* Resource Routes *************************************************************/
	Route::match(['get', 'post'],		'/document-list/{nIdClient?}/{nIdDocument?}',	['as' => 'document-list', 		    'uses' => 'DocumentController@callGetDocumentList']);
	Route::match(['get', 'post'],		'/document/create-folder',						['as' => 'document.create_folder', 	'uses' => 'DocumentController@callCreateFolder']);
	Route::match(['post'],				'/document/delete',								['as' => 'document.delete',			'uses' => 'DocumentController@callDocumentDelete']);
	Route::match(['get'],				'/file-preview/{nIdDocument}/{sDocumentName}',	['as' => 'document.file-preview', 	'uses' => 'DocumentController@callFilePreview']);
	Route::match(['get'],				'/download-file/{nIdDocument}/{sDocumentName}',	['as' => 'document.download-file', 	'uses' => 'DocumentController@callDownloadFile']);
	
	/************************************************************* User Routes *************************************************************/
	Route::match(['get', 'post'],		'/users',				['as' => 'users.list',		'uses' => 'UserController@callShowClientList']);
	Route::match(['post'],				'/create-user',			['as' => 'users.store',		'uses' => 'UserController@callCreateUser']);
	Route::match(['post'],				'/users/{nIdUseruser}', ['as' => 'users.update',	'uses' => 'UserController@callUpdateUser']);
});

/* LATER ON DELETE THESE*/
// Route::group(['middleware' => ['auth']], function () {
// 	Route::match(['get', 'post'],	'/fieldrep/document-list/{nIdClient?}/{nIdDocument?}', 	['as' => 'fieldrep.document-list', 		'uses' => 'DocumentController@callGetFieldRepDocumentList']);
// });
