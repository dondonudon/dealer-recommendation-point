<?php

namespace App\Http\Controllers;

use App\sysMenu;
use App\sysMenuGroup;
use App\sysPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Dashboard extends Controller
{
    public static function sidebarMenu() {
        $username = Session::get('username');
        $group = sysMenuGroup::all();
        $menu = DB::table('sys_permission')
            ->select('sys_menu.id_group','sys_menu.segment_name','sys_menu.nama','sys_menu.url')
            ->join('sys_menu','sys_menu.id','=','sys_permission.id_menu')
            ->where([
                ['sys_permission.username','=',$username],
                ['sys_menu.system','=','website'],
            ])
            ->get();

        $groupSelected = [];
        foreach ($menu as $m) {
            if (!in_array($m->id_group,$groupSelected)) {
                $groupSelected[] = $m->id_group;
            }
        }

        $counter = 0;
        $sidebar = [];
        foreach ($group as $g) {
            $dtMenu = [];
            if (in_array($g->id, $groupSelected)) {
                $group = [
                    'segment_name' => $g->segment_name,
                    'nama' => $g->nama,
                    'icon' => $g->icon
                ];
                foreach ($menu as $m) {
                    if ($m->id_group == $g->id) {
                        $dtMenu[] = [
                            'segment_name' => $m->segment_name,
                            'nama' => $m->nama,
                            'url' => $m->url,
                        ];
                    }
                }
                $sidebar[$counter] = [
                    'group' => $group,
                    'menu' => $dtMenu,
                ];
                $counter++;
            }
        }
        return $sidebar;
    }
}
