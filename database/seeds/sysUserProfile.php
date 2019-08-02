<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class sysUserProfile extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\sysUserProfile::create(
            [
                'username' => 'dev',
                'nama_lengkap' => 'Developer',
                'email' => 'laurentiuskevin44@gmail.com',
                'no_telp' => '081901115314'
            ]
        );
//        DB::table('sys_user_profile')
//            ->insert([
//                'username' => 'dev',
//                'nama_lengkap' => 'Developer',
//                'email' => 'laurentiuskevin44@gmail.com',
//                'no_telp' => '081901115314'
//            ]);
    }
}
