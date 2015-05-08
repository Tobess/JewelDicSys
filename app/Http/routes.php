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

Route::get('/', 'HomeController@index');
Route::get('/p', function(){
    $words = [];
    App\Word::split('an,qianzujinjiezhi', $words);
    return Response::json($words);
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::group(['namespace' => 'Console', 'prefix' => 'console'], function()
{
    Route::resource('/', 'ConsoleController');
    Route::resource('users', 'UserController');
    Route::resource('brands', 'BrandController');
    Route::resource('colors', 'ColorController');
    Route::resource('crafts', 'CraftController');
    Route::resource('grades', 'GradeController');
    Route::resource('materials', 'MaterialController');
    Route::resource('morals', 'MoralController');
    Route::resource('rules', 'RuleController');
    Route::resource('styles', 'StyleController');
    Route::resource('varieties', 'VarietyController');
});