<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bookingGrTrn extends Model
{
    protected $table = 'booking_gr_trn';
    protected $fillable = ['no_booking','user_fu','status_fu','note'];
}
