<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
//            sysMenu::class,
//            sysMenuGroup::class,
            sysUser::class,
//            sysUserProfile::class,
//            sysPermissionSeeder::class,
        ]);
    }
}
