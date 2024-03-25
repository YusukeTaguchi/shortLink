<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateViewsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('views', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 500);
            $table->integer('viewed')->default(0);
            $table->dateTime('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('views');
    }
}
