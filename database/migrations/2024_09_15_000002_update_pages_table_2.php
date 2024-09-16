<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePagesTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('pages', function (Blueprint $table) {
        $table->json('head_stack')->nullable();
        $table->boolean('is_reviews')->default(1);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('pages', function (Blueprint $table) {
        $table->dropColumn('head_stack');
        $table->dropColumn('is_reviews');
      });
    }
}
