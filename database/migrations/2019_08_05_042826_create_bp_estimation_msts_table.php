<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBpEstimationMstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bp_estimation_mst', function (Blueprint $table) {
            $table->string('no_estimation',10)->primary();
            $table->string('nama',75);
            $table->string('no_telp',20);
            $table->string('no_pol',10);
            $table->string('model_kendaraan',25);
            $table->decimal('grand_total',12,2);
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
        Schema::dropIfExists('bp_estimation_msts');
    }
}
