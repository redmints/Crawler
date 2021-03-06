<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('website', function(Blueprint $table) {
        $table->increments('id');
        $table->string('url', 10000);
        $table->index('url');
        $table->string('title', 10000)->default("title");
        $table->boolean('etat')->default(false);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('website');
    }
}
