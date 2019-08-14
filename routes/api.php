<?php

use App\sysMenuGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

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
    $user = DB::table('sys_user')
        ->select('username','password')
        ->where('username','=',$user)
        ->first();

    $result = [];
    if ($pass == Crypt::decryptString($user->password)) {
        $result[]['status'] = 'success';
    } else {
        $result[]['status'] = 'username atau password salah';
    }

    return json_encode($result);
});
