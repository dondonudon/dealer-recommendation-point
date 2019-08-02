<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sysUserProfile extends Model
{
    protected $table = 'sys_user_profile';
    protected $fillable = ['username','nama_lengkap','email','no_telp'];
}
