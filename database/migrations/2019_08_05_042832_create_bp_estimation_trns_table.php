<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBpEstimationTrnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bp_estimation_trns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_invoice',10);
            $table->string('item',20);
            $table->integer('qty');
            $table->decimal('subtotal',12,2);

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
        Schema::dropIfExists('bp_estimation_trns');
    }
}
