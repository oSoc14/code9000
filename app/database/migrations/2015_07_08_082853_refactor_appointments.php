<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorAppointments extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('users', function ($table) {
            //Defines the parent event
            $table->integer('parent_id')->unsigned()->nullable();
            $table->dropForeign('parent_id');
            $table->foreign('parent_id')->references('id')->on('appointments')->onDelete('set null');
        });

        Schema::dropIfExists('parent_appointments');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        //Restore appointments table
        Schema::create('parent_appointments', function ($table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->boolean('allday');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->timestamps();

            //Defines the calendar an appointment belongs to
            $table->integer('calendar_id')->unsigned();
            $table->foreign('calendar_id')->references('id')->on('calendars')->onDelete('cascade');
        });


        Schema::table('users', function ($table) {
            //Defines the parent event
            $table->integer('parent_id')->unsigned()->nullable();
            $table->dropForeign('parent_id');
            $table->foreign('parent_id')->references('id')->on('parent_appointments')->onDelete('set null');
        });

    }

}
