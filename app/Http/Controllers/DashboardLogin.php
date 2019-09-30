<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardLogin extends Controller
{
    public function index() {
        if (Session::has('username')) {
            return redirect('/');
        } else {
            return view('dashboard.login');
        }
    }

    public function submit(Request $request) {
        $username = $request->username;
        $password = $request->password;
        $result = '';

        $user = DB::table('sys_user')
            ->where('username','=',$username);

        try {
            if ($user->exists()) {
                $data = $user->first();
                $uPassword = Crypt::decryptString($data->password);
                if ($uPassword == $password) {
                    $result = 'success';
                    Session::put('username',$username);
                    Session::put('status','logged in');
                } else {
                    $result = 'failed';
                }
            } else {
                $result = 'not available';
            }
        } catch (\Exception $ex) {
            dd('Exception Block', $ex);
        }

        return $result;
    }
}
