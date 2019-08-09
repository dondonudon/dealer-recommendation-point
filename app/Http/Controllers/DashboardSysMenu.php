<?php

namespace App\Http\Controllers;

use App\sysMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardSysMenu extends Controller
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
                return view('dashboard.system-menu');
                break;
        }
    }

    public function list() {
        $menu = DB::table('sys_menu')
            ->select('sys_menu.id','id_group','system','sys_menu_group.nama as group','sys_menu.nama','sys_menu.url','sys_menu.segment_name','sys_menu.ord')
            ->join('sys_menu_group','sys_menu.id_group','=','sys_menu_group.id')
            ->get();
        $result['data'] = $menu;

        return json_encode($result);
    }

    public function group() {
        $group = DB::table('sys_menu_group')
            ->select('id','nama')
            ->get();

        return json_encode($group);
    }

    public function add(Request $request) {
        $group = $request->group;
        $system = $request->system_type;
        $nama = $request->nama;
        $url = $request->url;
        $segment_name = $request->segment_name;
        $ord = $request->ord;

        try {
            sysMenu::create([
                'id_group' => $group,
                'system' => $system,
                'segment_name' => $segment_name,
                'nama' => $nama,
                'url' => $url,
                'ord' => $ord
            ]);
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }

        return 'success';
    }

    public function edit(Request $request) {
        $id = $request->id;
        $group = $request->group;
        $system = $request->system_type;
        $nama = $request->nama;
        $url = $request->url;
        $segment_name = $request->segment_name;
        $ord = $request->ord;

        try {
            DB::table('sys_menu')
                ->where('id','=',$id)
                ->update([
                    'id_group' => $group,
                    'system' => $system,
                    'segment_name' => $segment_name,
                    'nama' => $nama,
                    'url' => $url,
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
            DB::table('sys_menu')
                ->where('id','=',$id)
                ->delete();
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }
        return 'success';
    }
}
