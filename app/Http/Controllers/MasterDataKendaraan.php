<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MasterDataKendaraan extends Controller
{
    private function permission($segment) {
        if (Session::exists('username')) {
            $permission = DB::table('sys_permission')
                ->select('username','id_menu')
                ->join('sys_menu','sys_menu.id','=','sys_permission.id_menu')
                ->where([
                    ['sys_menu.segment_name','=',$segment],
                    ['sys_permission.username','=',Session::get('username')],
                ]);
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
                return redirect('/')->with('error','permission denied');
                break;

            default:
                return view('dashboard.masterdata-kendaraan');
                break;
        }
    }
}
