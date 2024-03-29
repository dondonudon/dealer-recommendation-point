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

Route::get('storage/{file}',function ($file) {
    return response()->file(storage_path('app/public/'.$file));
});

Route::post('/overview/list', 'DashboardOverview@list');
Route::get('/overview/session-flush', 'DashboardOverview@sessionFlush');

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
Route::post('system-utility/user-management/area-permission', 'DashboardSysUser@areaPermission');
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

Route::get('master-data/kendaraan', 'MasterDataKendaraan@index');
Route::post('master-data/kendaraan/list', 'MasterDataKendaraan@list');
Route::post('master-data/kendaraan/add', 'MasterDataKendaraan@add');
Route::post('master-data/kendaraan/edit', 'MasterDataKendaraan@edit');
Route::post('master-data/kendaraan/delete', 'MasterDataKendaraan@delete');

Route::get('master-data/konten-gambar', 'MasterDataKontenGambar@index');
Route::post('master-data/konten-gambar/list', 'MasterDataKontenGambar@list');
Route::post('master-data/konten-gambar/upload/{info}', 'MasterDataKontenGambar@upload');
Route::post('master-data/konten-gambar/preview', 'MasterDataKontenGambar@preview');
Route::post('master-data/konten-gambar/delete', 'MasterDataKontenGambar@delete');

Route::get('booking-general-repair/monitoring-dan-follow-up','BookingGRMonitoring@index');
Route::post('booking-general-repair/monitoring-dan-follow-up/list','BookingGRMonitoring@list');
Route::post('booking-general-repair/monitoring-dan-follow-up/notes','BookingGRMonitoring@notes');
Route::post('booking-general-repair/monitoring-dan-follow-up/update-fu','BookingGRMonitoring@updateFU');
Route::post('booking-general-repair/monitoring-dan-follow-up/update-datang','BookingGRMonitoring@updateDatang');
Route::get('booking-general-repair/monitoring-dan-follow-up/export/{start}/{end}/{status}','BookingGRMonitoring@export');

Route::get('booking-general-repair/input-booking','BookingGRInput@index');
Route::post('booking-general-repair/input-booking/upload','BookingGRInput@upload');
Route::get('booking-general-repair/input-booking/download-sample','BookingGRInput@download');

Route::get('booking-general-repair/performance-result','BookingGRPerformanceResult@index');
Route::post('booking-general-repair/performance-result/list','BookingGRPerformanceResult@list');

Route::get('booking-general-repair/monitoring-booking','BookingGRMonitorBooking@index');
Route::post('booking-general-repair/monitoring-booking/list','BookingGRMonitorBooking@list');

Route::get('body-paint-estimation/monitoring-dan-follow-up','BPEstimationMonitoringFu@index');
Route::post('body-paint-estimation/monitoring-dan-follow-up/list','BPEstimationMonitoringFu@list');
Route::post('body-paint-estimation/monitoring-dan-follow-up/trn','BPEstimationMonitoringFu@trn');
Route::post('body-paint-estimation/monitoring-dan-follow-up/update-fu','BPEstimationMonitoringFu@updateFU');

Route::get('body-paint-estimation/performance-result','BPEstimationPerformanceResult@index');
Route::post('body-paint-estimation/performance-result/list','BPEstimationPerformanceResult@list');

Route::get('sales-prospect/monitoring','SalesProspectMonitoring@index');
Route::post('sales-prospect/monitoring/list','SalesProspectMonitoring@list');
Route::get('sales-prospect/monitoring/export/{startDate}/{endDate}/{statusFU}/{salesman}','SalesProspectMonitoring@export');

Route::get('sales-prospect/inject-to-salesman','SalesProspectInject@index');
Route::post('sales-prospect/inject-to-salesman/list','SalesProspectInject@list');
Route::post('sales-prospect/inject-to-salesman/inject','SalesProspectInject@inject');

Route::get('sales-prospect/input-prospect','SalesProspectInput@index');
Route::post('sales-prospect/input-prospect/upload','SalesProspectInput@upload');
Route::get('sales-prospect/input-prospect/sample',function () {
    return \Illuminate\Support\Facades\Storage::download('public/sample-upload_prospect.xlsx');
});

Route::get('sales-prospect/performance-result','SalesProspectPerformanceResult@index');
Route::post('sales-prospect/performance-result/list','SalesProspectPerformanceResult@list');

Route::get('reminder-service/data','ReminderServiceData@index');
