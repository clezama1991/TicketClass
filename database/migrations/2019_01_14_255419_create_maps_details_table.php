<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMapsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maps_details', function (Blueprint $table) {
            $table->increments('id');            
            $table->unsignedInteger('maps_id');
            $table->foreign('maps_id')->references('id')->on('maps');
            $table->string('rows');
            $table->string('cols');
            $table->string('coords');
            $table->boolean('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { 
    }
}
