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
Route::get('/home', 'HomeController@index');

Route::get('/teacher', 'HtmlController@getHtmlFile');
Route::get('/teacherClass', 'HtmlController@getTeachClass');
Route::get('/student', 'HtmlController@getStudent');
Route::get('/studentCourse', 'HtmlController@getStudentCourse');
Route::get('/myLogin', function () {
    return view('login');
});
Route::get('/', 'HomeController@index');

Route::get('/test','HomeController@test');

//<！--Student相关模块
Route::get('/studentHome','StudentController@getViewHome');
Route::get('/studentInformation','StudentController@getViewInformation');
Route::get('/studentCourses','StudentController@getViewCourses');
Route::get('/studentTeams','StudentController@getViewTeams');




Route::get('/jStudentInfo','StudentController@getJsonInfo');
Route::get('/jStudentCourses','StudentController@getJsonCourses');
Route::get('/jStudentCourseInfo','StudentController@getJsonCourseInfo');
Route::get('/jStudentCourseHomeworks','StudentController@getJsonCourseHomeworks');
Route::get('/jStudentTeams','StudentController@getJsonTeams');
//Student相关模块-->
