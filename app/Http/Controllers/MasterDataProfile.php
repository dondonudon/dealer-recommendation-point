<?php

namespace App\Http\Controllers;

use App\sysUserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MasterDataProfile extends Controller
{
    private function permission($segment) {
        if (Session::exists('username')) {
            $permission = DB::table('sys_permission')
                ->select('username','id_menu')
                ->join('sys_menu','sys_menu.id','=','sys_permission.id_menu')
                ->where('sys_menu.segment_name','=',$segment);
            if ($permission->exists()) {
                return 'true';
            } else {
                return 'not available';
            }
        } else {
            return 'login';
        }
    }

    public function index(Request $request) {
        $segment = $request->segment(2);
        $permit = $this->permission($segment);

        switch ($permit) {
            case 'login':
                return redirect('login');
                break;

            case 'not available':
                return redirect('/');
                break;

            default:
                return view('dashboard.masterdata-profile');
                break;
        }
    }

    public function list() {
        $username = Session::get('username');
//        $username = 'kvn';

        try {
            $profile = sysUserProfile::where('username','=',$username)->first();
        } catch (\Exception $ex) {
            dd('Exception Block', $ex);
        }

        return json_encode($profile);
    }

    public function edit(Request $request) {
        $username = Session::get('username');
        $namaLengkap = $request->nama_lengkap;
        $email = $request->email;
        $noTelp = $request->no_telp;
        $result = [];

        if ($request->password_lama !== null) {
            $passlama = DB::table('sys_user')->where('username','=',$username)->first();
            if (Crypt::decryptString($passlama->password) == $request->password_lama) {
                DB::table('sys_user')
                    ->where('username','=',$username)
                    ->update([
                        'password' => Crypt::encryptString($request->password)
                    ]);
            } else {
                $result[] = 'password lama salah';
            }
        }

        try {
            $check = DB::table('sys_user_profile')
                ->where('username','=',$username);
            if ($check->exists()) {
                DB::table('sys_user_profile')->update([
                    'nama_lengkap' => $namaLengkap,
                    'email' => $email,
                    'no_telp' => $noTelp,
                ]);
            } else {
                $profile = new sysUserProfile();

                $profile->username = $username;
                $profile->nama_lengkap = $namaLengkap;
                $profile->email = $email;
                $profile->no_telp = $noTelp;

                $profile->save();
            }
            $result[] = 'success';
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }
        return json_encode($result);
    }
}
