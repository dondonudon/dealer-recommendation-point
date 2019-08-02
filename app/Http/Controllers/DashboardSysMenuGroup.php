<?php

namespace App\Http\Controllers;

use App\sysMenu;
use App\sysMenuGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardSysMenuGroup extends Controller
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
                return view('dashboard.system-menu-group');
                break;
        }
    }

    public function list() {
        $menu = DB::table('sys_menu_group')
            ->select('id','nama','segment_name','icon','ord')
            ->get();
        $result['data'] = $menu;

        return json_encode($result);
    }

    public function add(Request $request) {
        $nama = $request->nama;
        $segment_name = $request->segment_name;
        $icon = $request->icon;
        $ord = $request->ord;

        try {
            sysMenuGroup::create([
                'segment_name' => $segment_name,
                'nama' => $nama,
                'icon' => $icon,
                'ord' => $ord
            ]);
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }

        return 'success';
    }

    public function edit(Request $request) {
        $id = $request->id;
        $nama = $request->nama;
        $segment_name = $request->segment_name;
        $icon = $request->icon;
        $ord = $request->ord;

        try {
            DB::table('sys_menu_group')
                ->where('id','=',$id)
                ->update([
                    'segment_name' => $segment_name,
                    'nama' => $nama,
                    'icon' => $icon,
                    'ord' => $ord
                ]);
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }
        return 'success';
    }

    public function delete(Request $request) {
        $id = $request->id;

        try {
            DB::table('sys_menu_group')
                ->where('id','=',$id)
                ->delete();
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }
        return 'success';
    }
}
