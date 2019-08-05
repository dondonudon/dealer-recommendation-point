<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class msKendaraan extends Model
{
    protected $table = 'ms_kendaraan';
    protected $fillable = ['category','model','tahun'];
}
