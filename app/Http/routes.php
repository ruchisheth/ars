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
//dd('tset');
/************************************************************* Login Routes *************************************************************/
Route::group(['middleware' => ['guest']], function() {
	Route::match(['get'],   		'/',            ['as' => 'home',				'uses' => 'LoginController@callLogin']);
	Route::match(['get', 'post'],   '/login',		['as' => 'login',				'uses' => 'LoginController@callLogin']);
	
});

Route::group(['middleware' => ['auth']], function() {
	Route::match(['get'],   '/dashboard',   ['as' => 'user.dashboard',  'uses' => 'LoginController@getDashboard']);
	Route::match(['get'],   '/logout',      ['as' => 'logout',          'uses' => 'LoginController@logout']);
	Route::match(['get'],   '/testLogin',   ['as' => 'test.login',      'uses' => 'LoginController@testLogin']);
});
















/************************************************************* Dashboard Routes *************************************************************/
Route::group(['namespace'=>'Admin','middleware' => ['auth','hasrole:admin']], function () 
{
	Route::match(['get'],   '/admin-dashboard', ['as' => 'admin.home',  'uses' => 'DashboardController@getClients']);	
});
Route::group(['namespace'=>'Admin', 'middleware' => ['auth']], function () {
	Route::match(['post'],  '/timezone', ['as' => 'timezone', 'uses' => 'DashboardController@getUserTimezone']);
});
Route::group(['prefix'=>'fieldrep','namespace'=>'FieldRep','middleware' => ['hasrole:fieldrep']], function () 
{
	Route::match(['get'],   '/home',    ['as' => 'fieldrep.home', 'uses' => 'DashboardController@index']);
});


/************************************************************* Client Routes *************************************************************/
// Route::group(['namespace'=>'Admin','middleware' => ['hasrole:admin']], function () {
Route::group(['namespace'=>'Admin', 'middleware' => ['hasrole:admin']], function () {
    Route::match(['get', 'post'],   '/clients', 					['as' => 'show.clients.get','uses' => 'ClientController@callShowClientList']);
	Route::match(['get'],			'/clients-edit',		        ['as' => 'create.client', 		'uses' => 'ClientController@callCreateClient']);
    Route::match(['get'],			'/clients-edit/{nIdClient}', 	['as' => 'edit.client', 		'uses' => 'ClientController@callShowEditClientForm']);
	Route::match(['post'],			'/add-client', 			        ['as' => 'store.client', 		'uses' => 'ClientController@callSaveClient']); 
	Route::match(['post'],			'/clients-delete',				['as' => 'delete.client',		'uses' => 'ClientController@callDeleteClient']);
// 	Route::match(['post'],			'/delete-logos/{nIdClient}',    ['as' => 'delete.client.logo',	'uses' => 'ClientController@callDeleteClientLogo']);
});

/************************************************************* Chain Routes *************************************************************/
Route::group(['namespace'=>'Admin','middleware' => ['hasrole:admin']], function () {
	Route::match(['get'],           '/chains-edit',                     ['as' => 'chain.create',        'uses' => 'ChainController@create']);
	Route::match(['get'],           '/chains-edit/client/{client_id}',	['as' => 'client.chain.create',	'uses' => 'ChainController@create']);
	Route::match(['post'],          '/add-chain',                       ['as' => 'store.chain',         'uses' => 'ChainController@store']);
	Route::match(['get'],           '/chains-edit/{id}',                ['as' => 'edit.chain',          'uses' => 'ChainController@edit']);
	Route::match(['get'],           '/chains',                          ['as' => 'show.chains.get',     'uses' => 'ChainController@index']);	
	Route::match(['post'],          '/chains',                          ['as' => 'show.chains.post',    'uses' => 'ChainController@getdata']);
	Route::match(['post'],          '/chains-delete',                   ['as' => 'delete.chain',        'uses' => 'ChainController@deleteChain']);
	Route::match(['post'],          '/chain/{chain_id}/get-contact',    ['as' => 'chain.getContact',    'uses' => 'ChainController@getChainContact']);
});

/************************************************************* Site Routes *************************************************************/
Route::group(['namespace'=>'Admin','middleware' => ['hasrole:admin']], function () {
	Route::match(['get'],           '/sites-edit',                  ['as' => 'site.create',         'uses' => 'SiteController@create']);
	Route::match(['get'],           '/sites',                       ['as' => 'show.sites.get',      'uses' => 'SiteController@index']);
	Route::match(['get'],           '/sites-edit/chain/{chain_id}',	['as' => 'chain.site.create',   'uses' => 'SiteController@create']);
	Route::match(['post'],          '/sites',                       ['as' => 'show.sites.post',     'uses' => 'SiteController@getdata']);
	Route::match(['post'],          '/add-site',                    ['as' => 'store.site',          'uses' => 'SiteController@store']);
	Route::match(['get'],           '/sites-edit/{id}',             ['as' => 'edit.site',           'uses' => 'SiteController@edit']);
	Route::match(['post'],          '/sites-delete',                ['as' => 'delete.site',         'uses' => 'SiteController@deleteSite']);    
});

/************************************************************* FieldRepRating Routes *************************************************************/
Route::group(['namespace' => 'Admin','middleware' => ['hasrole:admin']], function(){
	Route::match(['post'],          '/ratings-edit',            ['as' => 'store.rating',    'uses' => 'FieldRepRatingController@store']);
	Route::match(['post'],          '/ratings/{fieldrep_id}',   ['as' => 'show.ratings', 	'uses' => 'FieldRepRatingController@getdata']);
	Route::match(['post'],          '/ratings/{rating}/edit',	['as' => 'edit.rating',     'uses' => 'FieldRepRatingController@edit']);
	Route::match(['post'],          '/ratings-delete',          ['as' => 'delete.rating',   'uses' => 'FieldRepRatingController@deleteRating']);
});

/************************************************************* FieldRepPreferanceBan Routes *************************************************************/
Route::group(['namespace' => 'Admin','middleware' => ['hasrole:admin']], function(){
	Route::match(['post'],          '/prefbans-edit',               ['as' => 'store.prefbans',      'uses' => 'FieldRepPrefBanController@store']);
	Route::match(['post'],          '/prefbans/{fieldrep_id}',      ['as' => 'show.prefbans',       'uses' => 'FieldRepPrefBanController@getdata']);
	Route::match(['post'],          '/prefbans/{prefban}/edit',     ['as' => 'edit.prefbans',       'uses' => 'FieldRepPrefBanController@edit']);
	Route::match(['post'],          '/prefbans/{chain_id}/getsite', ['as' => 'prefbans.getsite',    'uses' => 'FieldRepPrefBanController@changesite']);
	Route::match(['post'],          '/prefbans-delete',             ['as' => 'delete.prefban',		'uses' => 'FieldRepPrefBanController@deletePrefBan']);
});

/************************************************************* FieldRepCriteria Routes *************************************************************/
Route::group(['namespace'=>'Admin','middleware' => ['hasrole:admin']], function () {
	Route::match(['post'],          '/fieldrep-set-criteria',   ['as' => 'set.criteria', 'uses' => 'FieldRepCriteriaController@setCriteria']);
});

/************************************************************* FieldRepOrg Routes *************************************************************/
Route::group(['namespace'=>'Admin','middleware' => ['hasrole:admin']], function () {
	Route::match(['get'],           '/fieldreporgs-edit',      ['as' => 'fieldreporgs.create',     'uses' => 'FieldRepOrgController@create']);
	Route::match(['get'],           '/fieldreporgs-edit/{id}', ['as' => 'edit.fieldreporgs',       'uses' => 'FieldRepOrgController@edit']);
	Route::match(['get'],           '/fieldreporgs',           ['as' => 'show.fieldreporgs.get', 	'uses' => 'FieldRepOrgController@index']);
	Route::match(['post'],          '/fieldreporgs',           ['as' => 'show.fieldreporgs.post',	'uses' => 'FieldRepOrgController@getdata']);
	Route::match(['post'],          '/add-fieldreporgs',       ['as' => 'store.fieldreporgs',      'uses' => 'FieldRepOrgController@store']);
	Route::match(['post'],          '/fieldreporgs-delete',    ['as' => 'delete.fieldreporg',      'uses' => 'FieldRepOrgController@deleteFieldRepOrg']);
});

/************************************************************* Contact Routes *************************************************************/
Route::group(['namespace'=>'Admin','middleware' => ['auth']], function () {
	Route::match(['post'],          '/contacts-edit',               ['as' => 'store.contact',   'uses' => 'ContactController@store']);
	Route::match(['post'],          '/contacts/{client}/{type}', 	['as' => 'show.contact',    'uses' => 'ContactController@getdata'])->where('type', '[0-9]+');
	Route::match(['post'],          '/contacts/{contact}/edit', 	['as' => 'edit.contact',    'uses' => 'ContactController@edit']);
	Route::match(['post'],          '/contacts-delete',             ['as' => 'delete.contact',  'uses' => 'ContactController@deleteContact']);
});

/************************************************************* Project Routes *************************************************************/
Route::group(['namespace'=>'Admin','middleware' => ['hasrole:admin']], function () {
	Route::match(['get'],           '/projects-edit',                   ['as' => 'create.project',          'uses' => 'ProjectController@create']);
	Route::match(['post'],          '/add-projects',  					['as' => 'store.project', 		    'uses' => 'ProjectController@store']);
	Route::match(['get'],           '/projects-edit/chain/{chain_id}',	['as' => 'chain.project.create',    'uses' => 'ProjectController@create']);
	Route::match(['get'],           '/projects-edit/{id}', 				['as' => 'edit.project', 			'uses' => 'ProjectController@edit']);
	Route::match(['get'],           '/projects',						['as' => 'projects', 				'uses' => 'ProjectController@index']);
	Route::match(['post'],          '/projects', 						['as' => 'projects', 				'uses' => 'ProjectController@getdata']);
	Route::match(['post'],          '/projects-delete',					['as' => 'delete.project',			'uses' => 'ProjectController@destroy']);
});

/************************************************************* Round Routes *************************************************************/
// Route::group(['namespace'=>'Admin','middleware' => ['hasrole:admin']], function () {
Route::group(['namespace'=>'Admin','middleware' => ['hasrole:admin']], function () {
	Route::match(['get'],           '/rounds-edit',                         ['as' => 'create.round',                'uses' => 'RoundController@create']);
	Route::match(['get'],           '/rounds-edit/project/{project_id}',    ['as' => 'project.round.create',        'uses' => 'RoundController@create']);
	Route::match(['get'],           '/rounds-edit/{id}',					['as' => 'edit.round',                  'uses' => 'RoundController@edit']);
	Route::match(['post'],          '/add-round',							['as' => 'store.round', 			    'uses' => 'RoundController@store']);
	Route::match(['get'],           '/rounds', 								['as' => 'show.rounds.get', 		    'uses' => 'RoundController@getdata']);
	Route::match(['get'],           '/rounds', 								['as' => 'show.project.rounds.show',    'uses' => 'RoundController@index']);
	Route::match(['post'],          '/rounds/{project_id?}', 				['as' => 'show.project.rounds.show',    'uses' => 'RoundController@getdata']);
	Route::match(['post'],          '/rounds-delete',						['as' => 'delete.rounds',				'uses' => 'RoundController@destroy']);
});
Route::group(['middleware'	=>	['hasrole:fieldrep']], function () {
	Route::match(['post'],          '/acknowledge-edit',        ['as' => 'edit.acknowledge',    'uses' => 'AcknowledgementsController@edit']);
});

/************************************************************* SurveyBuilder Routes *************************************************************/
Route::group(['namespace'=>'Builder', 'middleware' => ['hasrole:admin']], function () {
	Route::match(['get'],           '/survey-template-edit',                ['as' => 'form.builder',            'uses' => 'FormBuilderController@index']);
	Route::match(['get'],           '/survey-template-edit/{template_id}',	['as' => 'edit.form.builder',       'uses' => 'FormBuilderController@edit']);
	Route::match(['post'],          '/create-element/{type}',				['as' => 'make.element',            'uses' => 'FormBuilderController@PostElement']);
	Route::match(['get'],           '/survey/{id}',							['as' => 'fill-survey',             'uses' => 'FormBuilderController@GetSurvey']);
	Route::match(['get'],           '/preview-survey/{id}',                 ['as' => 'preview-survey',		    'uses' => 'FormBuilderController@GetSurvey']);
	Route::match(['post'],          '/file-delete',							['as' => 'delete-file',			    'uses' => 'FormBuilderController@DeleteFile']);
	Route::match(['get'],           '/templates',							['as' => 'templates', 			    'uses' => 'FormBuilderController@show']);
	Route::match(['post'],          '/templates',							['as' => 'templates', 			    'uses' => 'FormBuilderController@getdata']);
	Route::match(['post'],          '/templates-delete', 					['as' => 'delete-template', 	    'uses' => 'FormBuilderController@destroy']);
	Route::match(['post'],          '/save-template-details',				['as' => 'save-template-details',	'uses' => 'FormBuilderController@storeTemplateDetail']);
	Route::match(['post'],          '/validate-template',					['as' => 'validate-template',		'uses' => 'FormBuilderController@validateTemplate']);
	Route::match(['get'],           '/question-tags/{id}',					['as' => 'get.question.tags',		'uses' => 'FormBuilderController@createQuestionTag']);
	Route::match(['post'],          '/question-tags',						['as' => 'list.question.tags',		'uses' => 'FormBuilderController@getQuestionTag']);
	Route::match(['post'],          '/store-question-tags',					['as' => 'store.question.tags',		'uses' => 'FormBuilderController@storeQuestionTag']);
});
// Route::group(['namespace'=>'Builder', 'middleware' => ['hasrole:fieldrep']], function () {
Route::group(['namespace'=>'Builder', 'middleware' => ['auth']], function () {
	Route::match(['get'],           '/survey/{id}/{client_code}',   ['as' => 'fill-survey', 'uses' => 'FormBuilderController@GetSurvey']);
});
Route::group(['namespace'=>'Builder', 'middleware' => ['auth']], function () {
	Route::match(['post'],          '/save-template',           ['as' => 'save.template',           'uses' => 'FormBuilderController@PostTemplate']);
	Route::match(['post'],          '/save-survey',             ['as' => 'save-survey',             'uses' => 'FormBuilderController@PostSurvey']);
	Route::match(['post'],          '/validate-survey-data',    ['as' => 'validate-survey-data',    'uses' => 'FormBuilderController@validateSurveyData']);
});

/************************************************************* Assignment Routes *************************************************************/
Route::group(['namespace'=>'Admin',			'middleware' => ['hasrole:admin']],	function () {
	Route::match(['get'],           '/assignments',                         ['as' => 'get.assignments',             'uses' => 'AssignmentController@index']);
	Route::match(['post'],          '/assignments/{round_id?}',             ['as' => 'post.assignments',            'uses' => 'AssignmentController@getdata'])->where('round_id', '[0-9]+');
	Route::match(['post'],          '/generate-assignment',                 ['as' => 'generate.assignments',        'uses' => 'AssignmentController@generate']);
	Route::match(['post'],          '/schedule-assignment',                 ['as' => 'schedule.assignment',         'uses' => 'AssignmentController@schedule']);
	Route::match(['post'],          '/offer-assignment',                    ['as' => 'offer.assignment',            'uses' => 'AssignmentController@offer']);
	Route::match(['post'],          '/assignments/{assignment_id}/edit',    ['as' => 'edit.assignment',             'uses' => 'AssignmentController@edit']);
	Route::match(['post'],          '/assignments-schedule/edit',			['as' => 'edit.schedule.assignment',    'uses' => 'AssignmentController@scheduleEdit']);
	Route::match(['post'],          '/unschedule-rep',						['as' => 'unschedule.rep',              'uses' => 'AssignmentController@unscheduleRep']);
	Route::match(['post'],          '/assignments-delete',					['as' => 'delete.assignment',			'uses' => 'AssignmentController@destroy']);
});
Route::group(['namespace'=>'Admin',			'middleware' => ['auth']],	function () {
	Route::match(['post'],          '/add-assignment',		['as' => 'add.assignment',      'uses' => 'AssignmentController@store']);
	Route::match(['post'],          '/assignments-counts',  ['as' => 'assignment.count',    'uses' => 'AssignmentController@getAssignmentCounts']);
});
Route::group(['namespace'=>'FieldRep',	'middleware' => ['hasrole:fieldrep'],	'prefix'	=>	'fieldrep'	], function () {
	Route::match(['get'],           '/assignments',         ['as' => 'fieldrep.show.assignments.get',           'uses' => 'AssignmentController@index']);
	Route::match(['post'],          '/assignments',         ['as' => 'fieldrep.show.assignments.post',		    'uses' => 'AssignmentController@getAssignments']);
	Route::match(['get'],           '/assignment-history',  ['as' => 'fieldrep.show.assignments-history.get',	'uses' => 'AssignmentController@gethistory']);
	Route::match(['post'],          '/assignment-history',  ['as' => 'fieldrep.show.assignments-history.post',	'uses' => 'AssignmentController@getAssignments'])->where('round_id', '[0-9]+');
	Route::match(['get'],           '/offers',				['as' => 'fieldrep.show.offers.get', 				'uses' => 'AssignmentController@showOffers']);
	Route::match(['post'],          '/offers',				['as' => 'fieldrep.show.offers.post', 				'uses' => 'AssignmentController@getOffers']);
	Route::match(['post'],          '/response-offers',		['as' => 'fieldrep.response.offers', 				'uses' => 'AssignmentController@responseOffer']);
	Route::match(['post'],          '/assignment-details',	['as' => 'get.assignment.details',					'uses' => 'AssignmentController@getDetails']);
	Route::match(['post'],          '/get-instruction',		['as' => 'fieldrep.get.instruction',				'uses' => 'AssignmentController@getInstruction']);
});

/************************************************************* Assignment Instruction Routes *************************************************************/
Route::group(['namespace' => 'Admin', 'middleware' => ['hasrole:admin'] ], function () {
Route::match(['post'],				'/add-assignment-instructions',					['as' => 'store.instruction',	'uses' => 'InstructionController@store']);
Route::match(['post'],				'/apply-assignment-instructions',				['as' => 'apply.instruction', 	'uses' => 'InstructionController@apply']);
Route::match(['post'],				'/instructions/round/{round_id}', 				['as' => 'show.instructions', 	'uses' => 'InstructionController@getdata'])->where('type', '[0-9]+');
Route::match(['post'],				'/assignment-instructions/{instruction}/edit',	['as' => 'edit.instruction',	'uses' => 'InstructionController@edit']);
Route::match(['post'],				'/assignment-instruction-delete',				['as' => 'delete.assignment',	'uses' => 'InstructionController@destroy']);
Route::match(['post'],				'/delete-attachment/{instruction}',				['as' => 'delete.attachment',	'uses' => 'InstructionController@deleteAttachment']);
Route::match(['post'],				'/get-assignments',								['as' => 'get-assignments',		'uses' => 'InstructionController@getAssignments']);
});

/************************************************************* Survey Routes *************************************************************/
Route::group(['middleware' => ['auth']], function () {
	Route::match(['get'],           '/surveys',             ['as' => 'surveys', 'uses' => 'SurveyController@index']);
	Route::match(['post'],          '/surveys',             ['as' => 'surveys', 'uses' => 'SurveyController@getdata']);
});
Route::group(['middleware' => ['hasrole:admin']], function () {
	Route::match(['post'],          '/survey-change-status',    ['as' => 'mark-partial',        'uses' => 'SurveyController@changeStatus']);
	Route::match(['get'],           '/review-survey/{id}',      ['as' => 'review-survey',       'uses' => 'SurveyController@ReviewSurvey']);
	Route::match(['get'],           '/export-survey/{id}',      ['as' => 'export_survey_data',	'uses' => 'SurveyController@exportSurvey']);
});
// Route::get('/survey_data', ['as' => 'survey_data', 'uses' => 'SurveyController@surveyData']);
// Route::post('/survey_data', ['as' => 'survey_data', 'uses' => 'SurveyController@getSurveyData']);

/************************************************************* Import Routes *************************************************************/
Route::group(['namespace' => 'Admin','middleware' => ['hasrole:admin']], function(){
	Route::match(['get'],			'/imports', 			['as' => 'imports', 	'uses' => 'ImportController@index']);
	Route::match(['post'],			'/import-file', 		['as' => 'import.data',	'uses' => 'ImportController@Import']);	
});

/************************************************************* Export Routes *************************************************************/
Route::group(['namespace' => 'Admin', 'middleware' => ['hasrole:admin']], function () {
	Route::match(['get'],			'/exports/{entity?}', 								['as' => 'export', 				 		'uses' => 'ExportController@exportView']);
	Route::match(['get'],			'/export-unexport',									['as' => 'export.unexported', 	 		'uses' => 'ExportController@exportAllUnExportedSurvey']);
	Route::match(['post'],			'api/dropdown/projects', 							['as' => 'get.client.projects',	 		'uses' => 'ExportController@getClientProject']);
	Route::match(['post'],			'api/dropdown/rounds', 								['as' => 'get.project.rounds', 	 		'uses' => 'ExportController@getProjectRound']);
	Route::match(['post'],			'api/dropdown/sitecodes', 							['as' => 'get.project.sitecode', 		'uses' => 'ExportController@getProjectSiteCode']);
	Route::match(['post'],			'api/dropdown/questions', 							['as' => 'get.round.questions',	 		'uses' => 'ExportController@getRoundQuestion']);
	Route::match(['post'],			'/export-survey', 									['as' => 'export.survey.data',			'uses' => 'ExportController@exportSurveyData']);
	Route::match(['post'],			'/export-fieldrep',									['as' => 'export.fieldrep.data', 		'uses' => 'ExportController@exportFieldrepData']);
	Route::match(['get'],			'/exported-survey/{sFolderDate?}/{sFolderHour?}', 	['as' => 'exported.surveys', 			'uses' => 'ExportController@callShowAutoExportedSurvey']);
	Route::match(['get'],			'/export-survye/download', 							['as' => 'exported.survey.download',	'uses' => 'ExportController@callShowAutoExportedSurvey']);
	Route::match(['get'],			'/export-survye/delete', 							['as' => 'exported.survey.delete',		'uses' => 'ExportController@callShowAutoExportedSurvey']);
});

/************************************************************* Report Routes *************************************************************/
Route::group(['namespace'=>'Admin','middleware' => ['hasrole:admin']], function () {
	Route::match(['get'],			'/reports/site-geolocations',		['as' => 'show.geolocations.get',			'uses' => 'ReportController@index']);
	Route::match(['get'],			'/reports/fieldrep-geolocations',	['as' => 'show.fieldrep_geolocations.get',	'uses' => 'ReportController@fieldrepGeoLocations']);
	Route::match(['post'],			'/reports/geolocations', 			['as' => 'show.geolocations.post',			'uses' => 'ReportController@getdata']);
	Route::match(['post'],			'/reports/fieldrep-geolocations',	['as' => 'show.fieldrep_geolocations.post',	'uses' => 'ReportController@getfieldrepGeoLocations']);
	Route::match(['post'],			'/refresh-geocodes', 				['as' => 'refresh.site_geocode',			'uses' => 'ReportController@refreshGeoCodes']);
	Route::match(['post'],			'/refresh-fieldrep-geocodes', 		['as' => 'refresh.fieldrep_geocode',		'uses' => 'ReportController@refreshFieldrepGeoCodes']);
});

/************************************************************* FeedBack Routes *************************************************************/
Route::group(['middleware' => ['guest']], function() {
	Route::match(['post'], 			'/notify',							['as' => 'store.feedback',	'uses' => 'FeedBackController@store']);
	Route::match(['get'], 			'/feedback/{code}/{client_code}',	['as' => 'get.feedback',	'uses' => 'FeedBackController@create']);
	Route::match(['post'], 			'/feedback/{code}/{client_code}',	['as' => 'send.feedback',	'uses' => 'FeedBackController@store']);
});

/************************************************************* Setting Routes *************************************************************/
Route::group(['namespace'=>'Admin','middleware' => ['hasrole:admin']], function () {
	Route::match(['post'],			'/lists',								['as' => 'lists',					'uses' => 'SettingController@getlistdata']);
	Route::match(['post'],			'/lists-delete',						['as' => 'delete.client',			'uses' => 'SettingController@deleteList']);
	Route::match(['post'],			'/lists-edit',							['as' => 'store.list',				'uses' => 'SettingController@store']); 
	Route::match(['post'],			'/lists-item-edit',						['as' => 'create.lists_item',		'uses' => 'SettingController@list_item_store']);
	Route::match(['post'],			'/list_item-delete',					['as' => 'delete.list_item',		'uses' => 'SettingController@deleteListItem']);
	Route::match(['post'],			'/lists-item-edit/{list_item}/edit',	['as' => 'list_item.edit',			'uses' => 'SettingController@edit']);
	Route::match(['post'],			'/update/list-item-order',				['as' => 'change.listitem.order',	'uses' => 'SettingController@ListItemOrder']);
	Route::match(['post'],			'/lists-item-edit/{list_type}',			['as' => 'edit.list.item',			'uses' => 'SettingController@listitem']);
	Route::match(['get', 'post'],	'/setting/ftp',							['as' => 'setting.ftp',				'uses' => 'SettingController@callSaveFTPCredential']);
});
Route::group(['namespace' => 'Admin', 'middleware' => ['auth']], function () {
	Route::match(['get'],			'/settings',							['as' => 'settings',			'uses' => 'SettingController@index']);
	Route::match(['post'],			'/general-settings-edit', 				['as' => 'store.setting',		'uses' => 'SettingController@GeneralSettingStore']);
	Route::match(['post'],			'/delete-setting-logo/{logo}',			['as' => 'delete.setting.logo',	'uses' => 'SettingController@deleteLogo']);
	Route::match(['post'],			'/gettimezonedate',						['as' => 'get.timezone.date',	'uses' => 'SettingController@getTimeZoneDate']);
});

/************************************************************* Security Routes *************************************************************/
Route::group(['middleware' => ['auth']], function() {
	Route::match(['post'],			'/security/password',					['as' => 'password.reset',		'uses' => 'SecurityController@resetPwd']);
});

/************************************************************* Notification Routes *************************************************************/
Route::group(['namespace'=>'FieldRep','middleware' => ['hasrole:fieldrep']], function () {
	Route::match(['get'],			'/notification/notification-listing',	['as' => 'notification.notification-listing',       'uses' => 'NotificationController@callShowNotifications']);
});

/************************************************************* Profile Routes *************************************************************/
Route::group(['namespace'=>'Admin','middleware' => ['auth']], function () 
{
	Route::match(['get'],			'/admin-profile',	['as' => 'admin.profile', 'uses' => 'ProfileController@getProfile']);
	Route::match(['post'],			'/save-profile', 	['as' => 'save.profile',  'uses' => 'ProfileController@postProfile']);
});

/************************************************************* FieldRep Routes *************************************************************/
Route::group(['namespace'=>'Admin','middleware' => ['hasrole:admin']], function () {
	Route::match('get',				'/fieldreps-edit',					['as' => 'fieldrep.create',		'uses'	=> 'FieldRepController@create']);
	Route::match('get',				'/fieldreps',						['as' => 'show.fieldreps.get',	'uses'	=> 'FieldRepController@index']);
	Route::match('get',				'/fieldreps-edit/{id}',				['as' => 'edit.fieldrep',		'uses'	=> 'FieldRepController@edit']);
	Route::match('post',			'/fieldreps',						['as' => 'show.fieldreps.post',	'uses'	=> 'FieldRepController@getdata']);
	Route::match('post',			'/recent_activity/{fieldrep_id}',	['as' => 'recent_activity',		'uses'	=> 'FieldRepController@recent_activity']);
	Route::match('post',			'/fieldreps-delete',				['as' => 'delete.fieldrep',		'uses'	=> 'FieldRepController@deleteFieldRep']);
	Route::match('post',			'/respond-to-application',			['as' => 'respond.application',	'uses'	=> 'FieldRepController@respondToApplication']);
	//Route::post('/add-fieldrep', 										['as' => 'store.fieldrep',				'uses' => 'FieldRepController@store']);
	//Route::post('/add-fieldrep_otherdetails', 						['as' => 'store.fieldrep_otherdetails',	'uses' => 'FieldRepController@store_otherdetails']);
	//Route::post('/add-store_interestedin', 							['as' => 'store.store_interestedin',	'uses' => 'FieldRepController@store_interestedin']);
	//Route::post('/add-store_availability', 							['as' => 'store.store_availability',	'uses' => 'FieldRepController@store_availability']);
});
Route::group(['prefix' => 'fieldrep', 'namespace' => 'FieldRep', 'middleware' => ['hasrole:fieldrep']], function () {
	Route::match(['get'],			'/calendar',		['as' => 'fieldrep.show.events.get',	'uses' => 'AssignmentController@showEvents']);
	Route::match(['get'],			'/get-events',		['as' => 'fieldrep.show.events.post',	'uses' => 'AssignmentController@getEvents']);
	Route::match(['get'],			'/profile',			['as' => 'fieldrep.show.profile.get',	'uses' => 'FieldRepProfileController@getProfile']);
	Route::match(['get'],			'/preview-file',	['as' => 'get-preview',					'uses' => 'AssignmentController@showPreview']);
});
Route::group(['namespace'=>'Admin','middleware' => ['auth']], function () {
	Route::match(['post'],			'/add-fieldrep',				['as' => 'store.fieldrep',				'uses' => 'FieldRepController@store']);
	Route::match(['post'],			'/add-fieldrep_otherdetails',	['as' => 'store.fieldrep_otherdetails',	'uses' => 'FieldRepController@store_otherdetails']);
	Route::match(['post'],			'/add-store_interestedin',		['as' => 'store.store_interestedin',	'uses' => 'FieldRepController@store_interestedin']);
	Route::match(['post'],			'/add-store_availability',		['as' => 'store.store_availability',	'uses' => 'FieldRepController@store_availability']);
});
Route::group(['prefix'=>'fieldrep', 'namespace' => 'Admin'], function () {
Route::match(['get'],				'/{client_code}/register',			['as' => 'register.fieldrep',	'uses' => 'FieldRepController@getRegister']);
Route::match(['post'],				'/{client_code}/validate/{index}',	['as' => 'validate.fieldrep',	'uses' => 'FieldRepController@validateData']);
Route::match(['post'],				'/{client_code}/register',			['as' => 'register.fieldrep',	'uses' => 'FieldRepController@registerFieldRep']);
});

/************************************************************* Document Routes *************************************************************/
Route::group(['prefix'	=>	'fieldrep', 'namespace' => 'FieldRep',	'middleware' => ['hasrole:fieldrep'],], function () {
	Route::match(['get', 'post'],	'/document-list/{nIdClient?}/{nIdDocument?}', 	['as' => 'fieldrep.document-list', 		'uses' => 'DocumentController@callGetDocumentList']);
	Route::match(['get'],			'/file-preview/{nIdDocument}/{sDocumentName}',	['as' => 'fieldrep.document.file-preview', 	'uses' => 'DocumentController@callFilePreview']);
	Route::match(['get'],			'/download-file/{nIdDocument}/{sDocumentName}',	['as' => 'fieldrep.document.download-file', 	'uses' => 'DocumentController@callDownloadFile']);
});

/************************************************************* User Routes *************************************************************/
Route::group(['namespace'=>'Admin','middleware' => ['hasrole:admin']], function () {
	// 	Route::get('/users',					['as' => 'show.users.get',	'uses' => 'User`Controller@index']);
	// 	Route::post('/users',	 				['as' => 'show.user',		'uses' => 'UserCo`ntroller@getdata']);
	// 	Route::get('/users-edit', 				['as' => 'create.user',		'uses' => 'UserC`ontroller@create']);
	// 	Route::post('/add-user', 				['as' => 'store.user', 		'uses' => 'User`Controller@store']);
	// 	Route::get('api/dropdown/user_level',	['as' => 'get.user_level',	'uses' => 'UserController@getUserLevel']);
});

/************************************************************* SuperAdmin Routes *************************************************************/
Route::group(['namespace'=>'SuperAdmin','middleware' => ['hasrole:super_admin']], function () {
	Route::match(['get'],			'/home',			['as' => 'super_admin.home',	'uses' => 'DashboardController@index']);
	Route::match(['get'],			'/show-clients',	['as' => 'show.clients',		'uses' => 'ClientController@index']);
	Route::match(['post'],			'/show-clients', 	['as' => 'show.clients.get', 	'uses' => 'ClientController@getdata']);
	Route::match(['get'],			'/create-clients',	['as' => 'create.clients',		'uses' => 'ClientController@create']);
	Route::match(['post'],			'/save-clients',	['as' => 'save.clients',		'uses' => 'ClientController@store']);
});
Route::group(['namespace'=>'SuperAdmin','middleware' => ['auth']], function () {
	Route::match(['post'],			'/email/send-invitation/{invite_type}',	['as' => 'send-invitation', 'uses' => 'ClientController@sendInvitation']);
});

/*
* temporary route
*/
Route::get('/keypairs',		['as' => 'replace.keypairs',	'uses' => 'SurveyController@replaceKeyPairs']);
Route::get('/testview',		['as' => 'test.view',			'uses' => 'Admin\ClientController@testView']);
Route::get('route-list', function(){
	$exitCode = Artisan::call('route:list');
});
//LaravelLogsViewer::routes();