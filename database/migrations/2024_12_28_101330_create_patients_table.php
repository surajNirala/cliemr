<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('created_by');
            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->string('name');
            $table->string('gender');
            $table->integer('age');
            $table->date('dob')->nullable();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('phone2')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('referred_by_title')->nullable();
            $table->string('referred_by_name')->nullable();
            $table->string('referred_by_speciality')->nullable();
            $table->integer('language_id')->nullable();
            $table->tinyInteger('flag')->default(1);
            $table->tinyInteger('status')->default(1)->comment("Active=1,Inactive=0");
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
        Schema::dropIfExists('patients');
    }
}
