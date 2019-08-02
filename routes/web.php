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
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/', 'DashboardOverview@index');

Route::get('login', 'DashboardLogin@index');
Route::post('login/submit', 'DashboardLogin@submit');

Route::get('system-utility/menu-group', 'DashboardSysMenuGroup@index');

Route::get('system-utility/menu', 'DashboardSysMenu@index');
Route::post('system-utility/menu/list', 'DashboardSysMenu@list');
Route::post('system-utility/menu/group', 'DashboardSysMenu@group');
Route::post('system-utility/menu/add', 'DashboardSysMenu@add');
Route::post('system-utility/menu/edit', 'DashboardSysMenu@edit');
Route::post('system-utility/menu/delete', 'DashboardSysMenu@delete');

Route::get('system-utility/user', 'DashboardSysUser@index');
