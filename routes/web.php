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

Route::get('language/{locale}', function ($locale) {
    session(['locale' => $locale]);
    // echo session()->get('locale') ;die;
    return redirect()->back();
})->name('change_language');

Route::group(['middleware' => ['sessioncheking', 'disablepreventback']], function () {
    Auth::routes();
    Route::get('/index', 'UserController@index');
    Route::get('/userRegisration', 'LoginController@userRegisration');
    Route::get('/userList', 'registrationController@userList');
    Route::get('/case_entry', 'SDOCourtController@case_entry');
    Route::get('/case_list', 'SDOCourtController@case_list');
    Route::get('/grievance_list', 'GrievanceController@grievance_list');
    Route::get('/forwarded_grievance_list', 'GrievanceController@forwarded_grievance_list');
    Route::post('logdetails', 'LogdetailsController@logdetails');
    Route::post('logview', 'LogdetailsController@logview');
    Route::get('/close_grievance_list', 'GrievanceController@close_grievance_list');
    Route::get('/resolve_grievance_list', 'GrievanceStatusController@resolve_grievance_list');
    Route::get('/pending_report', 'ReportController@pending_report');
    Route::get('/log_details', function () {
        return view('log_details');
    })->name('log_details');
    //Route::get('/home', 'HomeController@index');
});
  Route::get('/', 'UserController@home');
  Route::get('/login', 'LoginController@login');
  Route::post('/userlist_datatable', 'registrationController@userlist_datatable');
  Route::post('/caselist_datatable', 'SDOCourtController@caselist_datatable');
  Route::post('refreshcaptcha', 'SDOCourtController@refreshCaptcha');
  Route::post('capchavalidation', 'SDOCourtController@capchavalidation');
  Route::get('/grievance', 'GrievanceController@grivense');
  Route::post('/save_otp_for_grievance', 'GrievanceController@save_otp_for_grievance');
  Route::post('/check_otp_for_grievance', 'GrievanceController@check_otp_for_grievance');
  Route::post('grievance_datatable', 'GrievanceController@grievance_datatable');
  Route::post('view_user', 'GrievanceController@view_user');
  Route::post('user_list', 'GrievanceController@user_list');
  Route::post('view_user_for_forward', 'GrievanceController@view_user_for_forward');
  Route::post('closed_grievance_datatable', 'GrievanceController@closed_grievance_datatable');
  Route::get('session', function () {
      return view('layout.session');
  });
  Route::get('/todays_hearing', 'SDOCourtController@todays_hearing');
  Route::post('/caselist_datatable_for_todays_hearing', 'SDOCourtController@caselist_datatable_for_todays_hearing');
  Route::get('/grievance_status', 'GrievanceStatusController@grievance_status');
  Route::get('refreshcaptcha', 'SDOCourtController@refreshCaptcha');
  Route::post('/save_otp_for_grievancestatus', 'GrievanceStatusController@save_otp_for_grievancestatus')->name('save_otp_for_grievancestatus');
  Route::post('/check_otp_for_grievancestatus', 'GrievanceStatusController@check_otp_for_grievancestatus')->name('check_otp_for_grievancestatus');

  ////////Resolve

  Route::post('resolve_grievance_datatable', 'GrievanceStatusController@resolve_grievance_datatable');

  /////Report

  Route::post('pending_grievance_datatable', 'ReportController@pending_grievance_datatable');
  Route::post('show_pending_grievance', 'ReportController@show_pending_grievance');
  Route::post('forwored_grievance_datatable', 'GrievanceController@forwored_grievance_datatable');

Route::group(['middleware' => 'userlogdetails'], function () {
    Route::post('/login-action', 'LoginController@loginAction');
    Route::post('/userRegistrationAction', 'LoginController@userRegistrationAction');
    Route::post('/user_edit', 'registrationController@user_edit');
    Route::post('/user_delete', 'registrationController@user_delete');
    Route::post('/logout', 'LoginController@logout');

    /****************************SDO Court*******************/

    Route::post('/save_case', 'SDOCourtController@save_case');
    Route::post('/case_edit', 'SDOCourtController@case_edit');
    Route::post('/case_delete', 'SDOCourtController@case_delete');

    /****************************Change Password*******************/

    Route::post('/saveOtpForLogin', 'registrationController@saveOtpForLogin');
    Route::post('/checkOtpForLogin', 'registrationController@checkOtpForLogin');

    Route::get('/search_case', 'SDOCourtController@search_case');

    //******************************Grivance List******************************//
    Route::post('/grivanceSave', 'GrievanceController@grivanceSave');
    Route::post('save_forword', 'GrievanceController@save_forword');
    Route::post('close_grievance', 'GrievanceController@close_grievance');

    //grievance Status

    Route::post('grievance_statuss', 'GrievanceStatusController@grievance_statuss');
});
