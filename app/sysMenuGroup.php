<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sysMenuGroup extends Model
{
    protected $table = 'sys_menu_group';
    protected $fillable = ['segment_name','nama','icon','ord'];
}
