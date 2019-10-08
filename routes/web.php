<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great! 
|
*/
 
Auth::routes();
Route::get('/', 'Cms\HomeController@index');

//route::get("/","cms\HomeController@index");
Route::group(['prefix' => 'cms', 'middleware' => 'auth'], function() {
    Route::resource('/', 'Cms\HomeController');
    Route::resource('sub_permissions', 'Cms\PermissionController');
    Route::resource('/users','Cms\UserController');
    Route::resource('/case_management', 'Cms\CaseManagementController');
    Route::resource('/case_comments', 'Cms\CaseCommentsController');
    Route::get('/dstats/', 'Cms\HomeController@dashboardStats');
    Route::get('/dashqueuereport/', 'Cms\HomeController@dashboardQueueReport');

	Route::get('/iouserreport', 'Cms\ReportsController@ioUserReport');
    Route::get('/iocallreport', 'Cms\ReportsController@ioCallReport');
    Route::post('/iocallreport', 'Cms\ReportsController@ioCallReportDatatable')->name("iocall_report");

    Route::get('/iuserreport', 'Cms\ReportsController@iUserReport');
    Route::post('/iuserreport_subdata','Cms\ReportsController@iCallReport')->name("iuserreport_subdata");

    Route::get('/ouserreport', 'Cms\ReportsController@oUserReport');
    Route::post('/ouserreport_subdata', 'Cms\ReportsController@oCallReport')->name("ouserreport_subdata");


    Route::get('/internalreport', 'Cms\ReportsController@internalReport');
    Route::post('/internalreport_subdata', 'Cms\ReportsController@internalDetailedReport')->name("internalreport_subdata");


    Route::get('/billreport', 'Cms\ReportsController@billReport');

    Route::get('/backcalls', 'Cms\ReportsController@abandonCalls')->name("backcalls");
    Route::get('/backcalls/download', 'Cms\ReportsController@abandonCallsDownload')->name("downloadcallback");

    Route::get('/realtime', 'Cms\ReportsController@showRealTime');
    Route::get('/realtimereport/{interface}', 'Cms\ReportsController@realTimeReport')->name("realtimereport");
    Route::get('/realtime/stats', 'Cms\ReportsController@realTime');
    Route::get('ext_realtime','Cms\ReportsController@realTimeFull')->name("realtime_ext.index");
    Route::post('/ext_realtime', 'Cms\ReportsController@realTimeDetails')->name("realtime_ext.getdetails");;

    Route::get('/queuestats', 'Cms\ReportsController@showQueueStatsReport');

    Route::get('/distribution', 'Cms\DistributionController@index');
    Route::post('/distribution', 'Cms\DistributionController@distribution');
    Route::post('/subdata', 'Cms\DistributionController@distributionSubData');
    Route::post('/distribution/csvexport', 'Cms\DistributionController@distributionSubDataExportCSV')->name("dist_export");

    Route::get('/queuestats/stats', 'Cms\ReportsController@queueStatsReport');

    Route::post('/queuereport', 'Cms\ReportsController@queueReport');
    Route::get('/queuereport', 'Cms\ReportsController@showQueueReport');
    Route::get('/queuereport/stats', 'Cms\ReportsController@queueReport');

	Route::post('/changepassword', 'Cms\HomeController@resetPassword');
	Route::get('/changepassword', 'Cms\HomeController@showChangePassword');
});

Route::group(['prefix' => 'admin',], function () {
  Route::get('/', 'AdminAuth\LoginController@showLoginForm');
  Route::get('/login', 'AdminAuth\LoginController@showLoginForm');
  Route::post('/login', 'AdminAuth\LoginController@login');


  Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm');
  Route::post('/register', 'AdminAuth\RegisterController@register');

  Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset');
  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
  
  
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {
    Route::resource('extensions', 'Admin\ExtensionController');
    Route::resource('queue', 'Admin\QueueController');
    Route::resource('permissions', 'Admin\PermissionController');
    Route::resource('nusers', 'Admin\UserController');
    Route::get('nusers/changepassword/{id}', 'Admin\UserController@showChangePassowrdForm')
        ->name("show_change_user_pass_form");
    Route::Post('nusers/changepassword', 'Admin\UserController@changePassowrd')
        ->name("change_user_pass");
	Route::post('/changepassword', 'Admin\HomeController@resetPassword');
  	Route::get('/changepassword', 'Admin\HomeController@showChangePassword');
    Route::post('/logout', 'AdminAuth\LoginController@logout');
    Route::get('/logout', 'AdminAuth\LoginController@logout');
    Route::post('/getextension', 'Admin\ExtensionController@getExt');
    Route::post('/addextension', 'Admin\ExtensionController@addExt');
    Route::post('/deleteextension', 'Admin\ExtensionController@deleteExt');
    Route::post('/getqueue', 'Admin\QueueController@getQueue')->name("getqueue");
    Route::post('/addqueue', 'Admin\QueueController@addQueue')->name("addqueue");
    Route::post('/deletequeue', 'Admin\QueueController@deleteQueue')->name("deletequeue");
});