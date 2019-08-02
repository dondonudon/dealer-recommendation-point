<?php

namespace App\Http\Controllers;

use App\sysMenu;
use App\sysMenuGroup;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public static function sidebarMenu() {
        $group = sysMenuGroup::all();
        $menu = sysMenu::all();

        $groupSelected = [];
        foreach ($menu as $m) {
            if (!in_array($m->id_group,$groupSelected)) {
                $groupSelected[] = $m->id_group;
            }
        }

        $counter = 0;
        $sidebar = [];
        foreach ($group as $g) {
            if (in_array($g->id, $groupSelected)) {
                $sidebar[$counter]['group'] = [
                    'segment_name' => $g->segment_name,
                    'nama' => $g->nama,
                    'icon' => $g->icon
                ];
                foreach ($menu as $m) {
                    if ($m->id_group == $g->id) {
                        $sidebar[$counter]['menu'][] = [
                            'segment_name' => $m->segment_name,
                            'nama' => $m->nama,
                            'url' => $m->url,
                        ];
                    }
                }
            }
        }
        return $sidebar;
    }
}
