<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRedirectLinksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('redirect_links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('domain', 1000);
            $table->string('url', 1000);
            $table->tinyInteger('status')->default(0)->comment('0 => InActive, 1 => Published, 2 => Draft');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('viewed')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('redirect_links');
    }
}
