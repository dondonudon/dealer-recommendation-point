<?php

namespace App\Http\Controllers;

use App\sysAreaPermission;
use App\sysMenu;
use App\sysMenuGroup;
use App\sysPermission;
use App\sysUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardSysUser extends Controller
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
                return view('dashboard.system-user-management');
                break;
        }
    }

    public function list() {
        $user = DB::table('sys_user')
            ->select('username','isDel')
            ->get();
        $result['data'] = $user;

        return json_encode($result);
    }

    public static function menu() {
        $group = sysMenuGroup::all();
        $result = [];

        foreach ($group as $g) {
            $menu = DB::table('sys_menu')
                ->where('id_group','=',$g->id);
            if ($menu->exists()) {
                $dataMenu = $menu->get();
                $listMenu = [];
                foreach ($dataMenu as $m) {
                    $listMenu[] = [
                        'id' => $m->id,
                        'nama' => $m->nama,
                    ];
                }
                $result[] = [
                    'group' => $g->nama,
                    'menu' => $listMenu,
                ];
            }
        }
        return $result;
    }

    public function add(Request $request) {
        $username = $request->username;
        $password = Crypt::encryptString($username);
        $permission = $request->menu_permission;

        try {
            if (DB::table('sys_user')->where('username','=',$username)->doesntExist()) {
                sysUser::create([
                    'username' => $username,
                    'password' => $password,
                ]);
                foreach ($permission as $p) {
                    sysPermission::create([
                        'username' => $username,
                        'id_menu' => $p
                    ]);
                }
                if (isset($request->area_permission)) {
                    $areaPermission = $request->area_permission;
                    foreach ($areaPermission as $area) {
                        sysAreaPermission::create([
                            'username' => $username,
                            'id_menu_group' => $area,
                        ]);
                    }
                }
                $result = 'success';
            } else {
                $result = 'terdaftar';
            }

        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }

        return $result;
    }

    public function userPermission(Request $request) {
        $username = $request->username;
        $permission = DB::table('sys_permission')
            ->select('id_menu')
            ->where('username','=',$username)
            ->get();

        return json_encode($permission);
    }

    public function areaPermission(Request $request) {
        $username = $request->username;
        $permission = DB::table('sys_area_permission')
            ->select('id_menu_group')
            ->where('username','=',$username)
            ->get();

        return json_encode($permission);
    }

    public function edit(Request $request) {
        $username = $request->username;
        $permission = $request->menu_permission;

        try {
            DB::table('sys_permission')->where('username','=',$username)->delete();
            foreach ($permission as $p) {
                sysPermission::create([
                    'username' => $username,
                    'id_menu' => $p
                ]);
            }
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }
        return 'success';
    }

    public function delete(Request $request) {
        $username = $request->username;

        try {
            DB::table('sys_user')
                ->where('username','=',$username)
                ->update([
                    'isDel' => 1
                ]);
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }
        return 'success';
    }

    public function reset(Request $request) {
        $username = $request->username;
        $password = Crypt::encryptString($username);

        try {
            DB::table('sys_user')
                ->where('username','=',$username)
                ->update([
                    'password' => $password
                ]);
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }
        return 'success';
    }
}
