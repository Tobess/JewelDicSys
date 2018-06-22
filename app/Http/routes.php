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

// 获得品牌logo图片
Route::get('logo/brand/{id}', function ($id) {
    return response()->download(\App\Http\Controllers\Console\BrandController::logo($id));
});

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

    // 标准分类
    Route::controller('standard','StandardController');

    Route::controller('aliases', 'AliasController');
    Route::controller('links', 'DLinksController');
    Route::controller('jerror', 'JErrorController');
    Route::controller('areas', 'AreaController');
});

// 内网公开接口
Route::group(['middleware' => 'sso'], function()
{
    Route::get('analyse/{identify}', 'HomeController@postAnalyse');
});
Route::controller('resource', 'ResourcesController');