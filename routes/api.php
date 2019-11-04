<?php

use App\sysMenuGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/login/{user}/{pass}',function($user,$pass) {
    $sysUser = DB::table('sys_user')
        ->select('username','password')
        ->where('username','=',$user)
        ->first();

    $result = [];
    if ($pass == Crypt::decryptString($sysUser->password)) {
        $result[]['status'] = 'success';
    } else {
        $result[]['status'] = 'username atau password salah';
    }

    return json_encode($result);
});

Route::get('ganti-password/{user}/{pass}',function ($user,$pass) {
    DB::beginTransaction();
    try {
        DB::table('sys_user')
            ->where('username','=',$user)
            ->update([
                'password' => Crypt::encryptString($pass),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    } catch (Exception $ex) {
        DB::rollBack();
        return response()->json($ex);
    }
    DB::commit();
    return 'success';
});
