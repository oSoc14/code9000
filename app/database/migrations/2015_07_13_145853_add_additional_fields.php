<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalFields extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('schools', function ($table) {
            $table->string('slug');
            $table->string('url');
            $table->string('address');
            $table->string('phone');
            $table->string('mail');
            $table->boolean('active');

        });

        Schema::table('appointments', function ($table) {
            $table->string('color');
            $table->string('image');
            $table->string('organiser');
            $table->boolean('cancelled');
        });

        Schema::table('calendars', function ($table) {
            $table->string('color');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('schools', function ($table) {
            $table->dropColumn('slug');
            $table->dropColumn('url');
            $table->dropColumn('address');
            $table->dropColumn('phone');
            $table->dropColumn('mail');
            $table->dropColumn('active');

        });

        Schema::table('appointments', function ($table) {
            $table->dropColumn('color');
            $table->dropColumn('image');
            $table->dropColumn('organiser');
            $table->dropColumn('cancelled');
        });

        Schema::table('calendars', function ($table) {
            $table->dropColumn('color');
        });
    }

}
