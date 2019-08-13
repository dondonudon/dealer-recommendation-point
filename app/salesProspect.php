<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class salesProspect extends Model
{
    protected $table = 'sales_prospect';
    protected $fillable = ['no_sales','nama_customer','no_telephone','model_kendaraan','kabupaten','kecamatan','alamat','pekerjaan','kebutuhan','username'];
}
