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

Route::get('/', 'HomeController@getIndex');
Route::get('search', 'HomeController@getSearch');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::group(['namespace' => 'Console', 'prefix' => 'console'], function()
{
    Route::get('/', 'DashboardController@getIndex');
    Route::controller('dashboard', 'DashboardController');
    Route::resource('users', 'UserController');
    Route::controller('brands', 'BrandController');
    Route::controller('colors', 'ColorController');
    Route::controller('crafts', 'CraftController');
    Route::controller('grades', 'GradeController');
    Route::controller('materials', 'MaterialController');
    Route::controller('morals', 'MoralController');
    Route::controller('rules', 'RuleController');
    Route::controller('styles', 'StyleController');
    Route::controller('varieties', 'VarietyController');
    Route::controller('aliases', 'AliasController');
});