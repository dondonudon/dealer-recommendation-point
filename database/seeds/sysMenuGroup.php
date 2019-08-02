<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class sysMenuGroup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\sysMenuGroup::create(
            [
                'segment_name' => 'system-utility',
                'nama' => 'System Utility',
                'icon' => 'fas fa-code',
                'ord' => 1,
            ]
        );
//        $sysMenuGroup = new \App\sysMenuGroup();
//
//        $sysMenuGroup->segment_name = 'system-utility';
//        $sysMenuGroup->nama = 'System Utility';
//        $sysMenuGroup->icon = 'fas fa-code';
//        $sysMenuGroup->ord = 1;
//
//        $sysMenuGroup->save();
    }
}
