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

Route::get('/test','HomeController@test');

//<！--Student相关模块
Route::get('/studentHome','StudentController@getViewHome');
Route::get('/studentInfomation','StudentController@getViewInformation');
Route::get('/studentCourses','StudentController@getViewCourses');
Route::get('/studentTeams','StudentController@getViewTeams');




Route::get('/jStudentInfo','StudentController@getJsonInfo');
Route::get('/jStudentCourses','StudentController@getJsonCourses');
Route::get('/jStudentCourseInfo','StudentController@getJsonCourseInfo');
Route::get('/jStudentCourseHomeworks','StudentController@getJsonCourseHomeworks');
Route::get('/jStudentCourseSubmitHomework','StudentController@postJsonCourseSubmitHomework');
Route::get('/jStudentTeams','StudentController@getJsonTeams');
//Student相关模块-->


//<!-- Teacher相关模块
Route::get('/teacherHome','TeacherController@getViewHome');


Route::get('/jTeacherInfo','TeacherController@getJsonInfo');
Route::get('/jTeacherCourses','TeacherController@getJsonCourses');
Route::get('/jTeacherCourseInfo','TeacherController@getJsonCourseInfo');
Route::get('/jTeacherCourseStudents','TeacherController@getJsonCourseStudents');
Route::get('/jTeacherCourseHomeworks','TeacherController@getJsonCourseHomeworks');
Route::post('/jTeacherPublishHomework','TeacherController@postJsonPublishHomework');

//Teacher相关模块-->