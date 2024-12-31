<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('created_by');
            $table->bigInteger('patient_id');
            $table->string('invoice')->unique();
            $table->string('mode')->default('case');
            $table->string('service');
            $table->float('unit_price', 8, 2)->default(0);
            $table->float('paid', 8, 2)->default(0);
            $table->float('discount', 8, 2)->default(0);
            $table->string('gst')->nullable();
            $table->tinyInteger('flag')->default(1);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('bills');
    }
}
