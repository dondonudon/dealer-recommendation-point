<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingGrMstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_gr_mst', function (Blueprint $table) {
            $table->string('no_booking',10)->primary();
            $table->string('nama',75);
            $table->string('no_telp',20);
            $table->string('no_pol',10);
            $table->string('model_kendaraan',25);
            $table->year('tahun_kendaraan');
            $table->date('tgl_booking');
            $table->time('jam_booking');
            $table->string('tipe_service');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_gr_msts');
    }
}
