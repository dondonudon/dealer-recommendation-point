<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class msKonten extends Model
{
    protected $table = 'ms_konten';
    protected $fillable = ['file_name','file_location'];
}
