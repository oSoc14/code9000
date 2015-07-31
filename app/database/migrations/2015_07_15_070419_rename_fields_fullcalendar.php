<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class RenameFieldsFullcalendar
 *
 * Rename fields to be native to fullcalendar.js
 */
class RenameFieldsFullcalendar extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function ($table) {
            $table->renameColumn('start_date', 'start');
            $table->renameColumn('end_date', 'end');
            $table->renameColumn('allday', 'allDay');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function ($table) {
            $table->renameColumn('start', 'start_date');
            $table->renameColumn('end', 'end_date');
            $table->renameColumn('allDay', 'allday');
        });
    }

}
