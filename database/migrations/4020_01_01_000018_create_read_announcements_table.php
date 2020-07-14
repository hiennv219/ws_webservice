<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateReadAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('read_announcements', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigInteger('user_id')->unsigned();
            $table->integer('announcement_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('announcement_id')->references('id')->on('announcements')->onDelete('cascade');

            $table->primary(['user_id', 'announcement_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('read_announcements');
    }
}
