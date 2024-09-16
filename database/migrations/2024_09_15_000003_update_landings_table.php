<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLandingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('landings', function (Blueprint $table) {
        $table->json('fields')->nullable();
        $table->longText('closed_html')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('landings', function (Blueprint $table) {
        $table->dropColumn('fields');
        $table->dropColumn('closed_html');
      });
    }
}
