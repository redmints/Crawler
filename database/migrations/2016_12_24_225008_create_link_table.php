<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('link', function(Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('websiteid');
        $table->foreign('websiteid')
              ->references('id')
              ->on('website')
              ->onDelete('restrict')
              ->onUpdate('restrict');
        $table->unsignedInteger('keywordid');
        $table->foreign('keywordid')
              ->references('id')
              ->on('keywords')
              ->onDelete('restrict')
              ->onUpdate('restrict');
        $table->integer('frequency');
        $table->integer('importance');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('link');
    }
}
