<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesProspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_prospect', function (Blueprint $table) {
            $table->string('no_sales',25)->primary();
            $table->string('nama_customer',50);
            $table->string('no_telephone',15);
            $table->string('model_kendaraan',25);
            $table->string('kabupaten',25);
            $table->string('kecamatan',25);
            $table->string('alamat',100);
            $table->string('pekerjaan',25)->nullable();
            $table->string('kebutuhan',25)->nullable();
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
        Schema::dropIfExists('sales_prospects');
    }
}
