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

Route::get('/', 'UserController@home')->name('home');



Route::group(['middleware' => 'disablepreventback'],function(){
	Auth::routes();
  Route::get('/index', 'UserController@index')->name('index');
  Route::get('/user-registration','LoginController@userRegisration')->name('userRegisration');
  Route::get('/userList','registrationController@userList')->name('userList');
  Route::get('/case_entry','SDOCourtController@case_entry')->name('case_entry');
  Route::get('/case_list','SDOCourtController@case_list')->name('case_list');
  Route::get('/grievance_list', 'GrievanceController@grievance_list')->name('grievance_list');
  Route::get('forworded_grievance_list', 'GrievanceController@forworded_grievance_list')->name('forworded_grievance_list');

  //Route::get('/home', 'HomeController@index');
});

Route::get('/login','LoginController@login')->name('login');
Route::post('/login-action','LoginController@loginAction')->name('loginAction');

Route::post('/userRegistrationAction','LoginController@userRegistrationAction')->name('userRegistrationAction');


Route::post('/userlist_datatable','registrationController@userlist_datatable')->name('userlist_datatable');

Route::post('/user_edit','registrationController@user_edit')->name('user_edit');
Route::post('/user_delete','registrationController@user_delete')->name('user_delete');


Route::get('/log-out','LoginController@logout')->name('logout');









/****************************SDO Court*******************/

Route::post('/save_case','SDOCourtController@save_case')->name('save_case');

Route::post('/caselist_datatable','SDOCourtController@caselist_datatable')->name('caselist_datatable');
Route::post('/case_edit','SDOCourtController@case_edit')->name('case_edit');
Route::post('/case_delete','SDOCourtController@case_delete')->name('case_delete');




/*********************************************************/

Route::post('/save_otp','registrationController@save_otp')->name('save_otp');
Route::post('/check_otp','registrationController@check_otp')->name('check_otp');


/****************************Change Password*******************/
Route::get('/change_pin','LoginController@change_pin')->name('change_pin');
Route::post('/user_pin_change','LoginController@user_pin_change')->name('user_pin_change');
Route::get('/edit_profile','LoginController@edit_profile')->name('edit_profile');
Route::post('/user_name_change','LoginController@user_name_change')->name('user_name_change');



Route::get('/forgotPassword','registrationController@forgotPassword')->name('forgotPassword');

Route::post('/saveOtpForLogin','registrationController@saveOtpForLogin')->name('saveOtpForLogin');
Route::post('/checkOtpForLogin','registrationController@checkOtpForLogin')->name('checkOtpForLogin');
Route::post('/passwordChange','registrationController@passwordChange')->name('passwordChange');





Route::get('/search_case', 'SDOCourtController@search_case')->name('search_case');
Route::get('refreshcaptcha', 'SDOCourtController@refreshCaptcha');
Route::post('capchavalidation', 'SDOCourtController@capchavalidation');





//******************************Grivance List******************************//
Route::post('/save_otp_for_grievance','GrievanceController@save_otp_for_grievance')->name('save_otp_for_grievance');
Route::post('/check_otp_for_grievance','GrievanceController@check_otp_for_grievance')->name('check_otp_for_grievance');
Route::get('/grievance', 'GrievanceController@grivense')->name('grievance');
Route::post('/grivanceSave', 'GrievanceController@grivanceSave')->name('grivanceSave');

Route::post('grievance_datatable', 'GrievanceController@grievance_datatable');

Route::post('view_user', 'GrievanceController@view_user');
Route::post('user_list', 'GrievanceController@user_list');
Route::post('save_forword', 'GrievanceController@save_forword');

Route::post('forwored_grievance_datatable', 'GrievanceController@forwored_grievance_datatable');

Route::get('session', function () {
   return view('layout.session');
});


Route::get('/todays_hearing', 'SDOCourtController@todays_hearing')->name('todays_hearing');

Route::post('/caselist_datatable_for_todays_hearing', 'SDOCourtController@caselist_datatable_for_todays_hearing')->name('caselist_datatable_for_todays_hearing');

//grievance Status 

Route::get('/grievance_status', 'GrievanceStatusController@grievance_status')->name('grievance_status');
Route::get('refreshcaptcha', 'SDOCourtController@refreshCaptcha');
Route::post('grievance_statuss', 'GrievanceStatusController@grievance_statuss');











