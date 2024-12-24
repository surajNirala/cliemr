<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicineLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicine_libraries', function (Blueprint $table) {
            $table->id();            
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('medicine_type_id')->nullable();
            $table->bigInteger('medicine_id')->nullable();
            $table->string('dosage1')->nullable();
            $table->string('dosage2')->nullable();
            $table->string('dosage3')->nullable();
            $table->bigInteger('medicine_administration_id')->nullable();
            $table->string('unit')->nullable();
            $table->string('time')->nullable();
            $table->string('where')->nullable();
            $table->string('generic_name')->nullable();
            $table->string('frequency')->nullable();
            $table->string('duration')->nullable();
            $table->string('quantity')->nullable();
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('medicine_libraries');
    }
}
