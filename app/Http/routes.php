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
Route::get('/file',function(){
    return view('file');
});
Route::post('/fileUpLoader','FileController@fileUpLoader');
    