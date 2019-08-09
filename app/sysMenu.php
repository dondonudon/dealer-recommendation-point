<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sysMenu extends Model
{
    protected $table = 'sys_menu';
    protected $fillable = ['id_group','system','segment_name','nama','url','ord'];
}
