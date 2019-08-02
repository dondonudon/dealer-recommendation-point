<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardOverview extends Controller
{
    private function permission() {
        if (Session::exists('username')) {
            return 'true';

//            $permission = DB::table('sys_permission')
//                ->select('username','id_menu')
//                ->join('sys_menu','sys_menu.id','=','sys_permission.id_menu')
//                ->where('sys_menu.segment_name','=',$segment);
//            if ($permission->exists()) {
//                return 'true';
//            } else {
//                return 'not available';
//            }
        } else {
            return 'login';
        }
    }

    public function index() {
        $check = $this->permission();

        if ($check == 'true') {
            return view('dashboard.overview');
        } else {
            return redirect('login');
        }
    }
}
