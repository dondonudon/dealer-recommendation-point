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
Route::post('system-utility/menu-group/list', 'DashboardSysMenuGroup@list');
Route::post('system-utility/menu-group/add', 'DashboardSysMenuGroup@add');
Route::post('system-utility/menu-group/edit', 'DashboardSysMenuGroup@edit');
Route::post('system-utility/menu-group/delete', 'DashboardSysMenuGroup@delete');

Route::get('system-utility/menu', 'DashboardSysMenu@index');
Route::post('system-utility/menu/list', 'DashboardSysMenu@list');
Route::post('system-utility/menu/group', 'DashboardSysMenu@group');
Route::post('system-utility/menu/add', 'DashboardSysMenu@add');
Route::post('system-utility/menu/edit', 'DashboardSysMenu@edit');
Route::post('system-utility/menu/delete', 'DashboardSysMenu@delete');

Route::get('system-utility/user-management', 'DashboardSysUser@index');
Route::post('system-utility/user-management/list', 'DashboardSysUser@list');
Route::post('system-utility/user-management/menu', 'DashboardSysUser@menu');
Route::post('system-utility/user-management/add', 'DashboardSysUser@add');
Route::post('system-utility/user-management/edit', 'DashboardSysUser@edit');
Route::post('system-utility/user-management/user-permission', 'DashboardSysUser@userPermission');
Route::post('system-utility/user-management/reset', 'DashboardSysUser@reset');
Route::post('system-utility/user-management/delete', 'DashboardSysUser@delete');

Route::get('master-data/profile', 'MasterDataProfile@index');
Route::post('master-data/profile/list', 'MasterDataProfile@list');
Route::post('master-data/profile/edit', 'MasterDataProfile@edit');

Route::get('master-data/salesman', 'MasterDataSalesman@index');
Route::post('master-data/salesman/list', 'MasterDataSalesman@list');
Route::post('master-data/salesman/user', 'MasterDataSalesman@user');
Route::post('master-data/salesman/add', 'MasterDataSalesman@add');
Route::post('master-data/salesman/delete', 'MasterDataSalesman@delete');

//Route::get('master-data/kendaraan', '');
