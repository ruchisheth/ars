<?php

Route::match(['get'],				'/',			['as' => 'home',				'uses' => 'HomeController@callGetHome']);
Route::group(['middleware' => ['guest']], function() {
	Route::match(['get', 'post'],	'/login',		['as' => 'login',				'uses' => 'LoginController@callLogin']);
	// Route::match(['get'],			'/admin',		['as' => 'super-admin.login',	'uses' => 'LoginController@getlogin']);
	// Route::match(['get'],			'/admin/login',	['as' => 'super-admin.login',	'uses' => 'LoginController@getlogin']);
});
Route::group(['middleware' => ['auth']], function() {
	Route::match(['get'],			'/dashboard',	['as' => 'user.dashboard',	'uses' => 'LoginController@getDashboard']);
	Route::match(['get'],			'/logout',		['as' => 'logout',			'uses' => 'LoginController@callLogout']);
	Route::match(['get'],			'/testLogin',	['as' => 'test.login',		'uses' => 'LoginController@testLogin']);
});


// Route::group(['namespace'=>'SuperAdmin','middleware' => ['hasrole:super_admin']], function () 
Route::group(['middleware' => ['auth']], function () 
{
	Route::match(['get'],				'/dashboard',						['as' => 'dashboard',						'uses' => 'DashboardController@index']);
	Route::match(['get', 'post'],		'/admins',							['as' => 'admin_list',			        	'uses' => 'AdminController@callShowAdmins']);
	Route::match(['get', 'post'],		'/create-admin',					['as' => 'create.admin',					'uses' => 'AdminController@callCreateAdmin']);
	Route::match(['get', 'post'],		'/admin-detail/{nIdAdmin}', 		['as' => 'superadmin.admin_detail',			'uses' => 'AdminController@callGetAdminStatistics']);
	Route::match(['get', 'post'],		'/add-subscription/{nIdAdmin}', 	['as' => 'superadmin.add_subscripition',	'uses' => 'AdminController@callAddSubScription']);
	Route::match(['get', 'post'],		'/admin-subscription/{nIdAdmin}', 	['as' => 'superadmin.admin_subscripition',	'uses' => 'AdminController@callListAdminSubScription']);
	Route::match(['post'],				'/active-subscription', 			['as' => 'superadmin.active-subscription',	'uses' => 'AdminController@callActiveAdminSubScription']);
	Route::match(['post'],				'/inactive-subscription', 			['as' => 'superadmin.inactive-subscription','uses' => 'AdminController@callInActiveAdminSubScription']);
	// Route::match(['post'],				'/save-clients',					['as' => 'save.clients',					'uses' => 'ClientController@store']);
	// Route::match(['post'],				'/show-clients', 	['as' => 'show.clients.get', 	'uses' => 'ClientController@getdata']);

	/************************************************************* Profile Routes *************************************************************/
	Route::match(['get', 'post'],	'/profile',			['as' => 'admin.profile',		'uses' => 'ProfileController@callProfile']);
	Route::match(['post'],			'/password-reset',	['as' => 'password.reset',		'uses' => 'ProfileController@callResetPassword']);
});

Route::group(['namespace'=>'SuperAdmin','middleware' => ['auth']], function () 
{
	Route::post('/email/send-invitation/{invite_type}',["as"=>'send-invitation','uses'=>'ClientController@sendInvitation']);

});