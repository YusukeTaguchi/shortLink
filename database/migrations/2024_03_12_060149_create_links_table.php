<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 300);
            $table->string('thumbnail_image', 300)->nullable();
            $table->text('notes');
            $table->integer('domain_id')->nullable();
            $table->integer('type_display')->nullable();
            $table->string('original_link', 1000)->nullable();
            $table->string('slug', 500)->nullable();
            $table->text('description', 65535)->nullable();
            $table->text('keywords', 65535)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 => InActive, 1 => Published, 2 => Draft');
            $table->tinyInteger('fake')->default(0)->comment('0 => On, 1 => Off');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('clicked')->default(0);
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
        Schema::drop('links');
    }
}
