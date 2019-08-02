<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class sysMenu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\sysMenu::create([
            'id_group' => 1,
            'segment_name' => 'menu-group',
            'nama' => 'Menu Group',
            'url' => 'system-utility/menu-group',
            'ord' => 1,
        ]);
        \App\sysMenu::create([
            'id_group' => 1,
            'segment_name' => 'menu',
            'nama' => 'Menu',
            'url' => 'system-utility/menu',
            'ord' => 2,
        ]);
        \App\sysMenu::create([
            'id_group' => 1,
            'segment_name' => 'user-management',
            'nama' => 'User Management',
            'url' => 'system-utility/user-management',
            'ord' => 3,
        ]);
//        $sysMenu = new \App\sysMenu();
//
//        $sysMenu->id_group = 1;
//        $sysMenu->segment_name = 'menu-group';
//        $sysMenu->nama = 'Menu Group';
//        $sysMenu->url = 'system-utility/menu-group';
//        $sysMenu->ord = 1;
//        $sysMenu->save();
//
//        $sysMenu->id_group = 1;
//        $sysMenu->segment_name = 'menu';
//        $sysMenu->nama = 'Menu';
//        $sysMenu->url = 'system-utility/menu';
//        $sysMenu->ord = 2;
//        $sysMenu->save();
//
//        $sysMenu->id_group = 1;
//        $sysMenu->segment_name = 'user';
//        $sysMenu->nama = 'User Management';
//        $sysMenu->url = 'system-utility/user';
//        $sysMenu->ord = 3;
//        $sysMenu->save();
    }
}
