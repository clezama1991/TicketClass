<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeatAttemdeesTable extends Migration
{
    /**
     * Run the migrations. 
     *
     * @return void
     */
    public function up()
    {

        /**
         * Add table seats_tickets
         *
         * @return void
         */
        Schema::create('seats_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('row');
            $table->integer('column');
            $table->integer('ticket_id')->unsigned()->index();
            $table->tinyInteger('is_available')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');           
        });

        /**
         * Add table seat_attemdees
         *
         * @return void
         */
        Schema::create('seat_attemdees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attendees_id')->unsigned()->index();
            $table->integer('seat_id')->unsigned()->index();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('attendees_id')->references('id')->on('attendees')->onDelete('cascade');
            $table->foreign('seat_id')->references('id')->on('seats_tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seats_tickets');
        Schema::dropIfExists('seat_attemdees');
    }
}
