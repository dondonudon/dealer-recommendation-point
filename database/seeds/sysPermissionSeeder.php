<?php

use Illuminate\Database\Seeder;

class sysPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\sysPermission::create([
            'username' => 'dev',
            'id_menu' => 1,
        ]);

        \App\sysPermission::create([
            'username' => 'dev',
            'id_menu' => 2,
        ]);

        \App\sysPermission::create([
            'username' => 'dev',
            'id_menu' => 3,
        ]);
    }
}
