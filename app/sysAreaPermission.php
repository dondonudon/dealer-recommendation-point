<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sysAreaPermission extends Model
{
    protected $table = 'sys_area_permission';

    protected $fillable = ['username','id_menu_group'];
}
