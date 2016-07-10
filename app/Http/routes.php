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
Route::get('/teacherCourse', 'HtmlController@getTeachCourse');
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
Route::get('/jStudentCourseHomeworkDetail','StudentController@getJsonCourseHomeworkDetail');
Route::post('/jStudentCourseSubmitHomework','StudentController@postJsonCourseSubmitHomework');
Route::post('/jStudentCourseSubmitHomeworkFile','StudentController@postJsonCourseSubmitHomeworkFile');
Route::get('/jStudentCourseDeleteHomeworkFile','StudentController@postJsonCourseDeleteHomeworkFile');
Route::get('/jStudentSubmitHomeworkResource','StudentController@getJsonCourseSubmitHomeworkResource');
Route::get('/jStudentSubmitHomeworkResources','StudentController@getJsonCourseSubmitHomeworkResources');
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
Route::get('/jTeacherHomeworkSubmits','TeacherController@getJsonHomeworkSubmits');

//Teacher相关模块-->

//<!-- Team相关模块
Route::get('/teamIndex', 'TeamController@teamIndex');
Route::get('/jGetAllTeams', 'TeamController@getAllTeams');
Route::get('/jGetOwnerTeams', 'TeamController@getMyTeams');
Route::post('/jStudentCreateTeam', 'TeamController@postJsonCreateTeam');
Route::post('/jStudentApplyTeam', 'TeamController@getApplyJoinTeam');
//Team相关模块 -->


#SA 相关操作
Route::post('/admin/getEAInfo', 'SAController@getEAInfo'); //获取所有EA信息列表
// Route::post('/admin/editEAInfo',''); //修改EA信息
// Route::post('/admin/delEAInfo','');  //删除EA信息
// Route::post('/admin/addEAInfo', ''); //添加EA信息