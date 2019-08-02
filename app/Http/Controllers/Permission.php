<?php

namespace App\Http\Controllers;

use App\sysPermission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Permission extends Controller
{
    public function check($segment, $viewname) {
        if (Session::exists('username')) {
            $username = Session::get('username');
            $permission = DB::table('sys_permission')
                ->join('sys_menu','sys_permission.id_menu','=','sys_menu.id')
                ->where([
                    ['sys_permission.username','=',$username],
                    ['sys_menu.segment_name','=',$segment],
                ]);
            if ($permission->exists()) {
                return view($viewname);
            } else {
                return 'false';
            }
        } else {
            return 'unknown';
        }
    }
}
