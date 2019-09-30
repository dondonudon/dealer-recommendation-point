<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class sysUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\sysUser::updateOrCreate(
            ['username' => 'dev'],
            ['password' => Crypt::encryptString('dev')]
        );
    }
}
