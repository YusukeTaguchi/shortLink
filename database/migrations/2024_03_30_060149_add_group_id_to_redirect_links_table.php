<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupIdToRedirectLinksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('redirect_links', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('redirect_links', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }
}
