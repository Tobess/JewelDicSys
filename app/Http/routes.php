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

Route::get('test', function () {
    return print_r($_SERVER, true);
});

Route::get('/', 'HomeController@getIndex');
Route::get('search', 'HomeController@getSearch');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

// 管理后台
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
    Route::controller('scolor','SColorController');
    Route::controller('scertificate','SCertificateController');
    Route::controller('sclarity','SClarityController');
    Route::controller('scut','SCutController');
    Route::controller('sgrade','SGradeController');
    Route::controller('sshape','SShapeController');
    Route::controller('aliases', 'AliasController');
    Route::controller('links', 'DLinksController');
    Route::controller('jerror', 'JErrorController');
    Route::controller('areas', 'AreaController');
});

// 内网公开接口
Route::group(['domain' => env('APP_DEBUG', false) ? 'dictionary.app' : (env('APP_ENV', null) == 'offline' ? '192.168.1.11' : '192.168.1.11')], function()
{
    Route::get('analyse/{identify}', 'HomeController@getAnalyse');
    Route::controller('resource', 'ResourcesController');
});