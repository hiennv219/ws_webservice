<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('title')->nullable();
            $table->longText('content');
            $table->tinyInteger('to_all')->default(1);
            $table->string('to_roles')->nullable();
            $table->string('to_ids')->nullable();
            $table->tinyInteger('type')->unsigned()->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announcements');
    }
}
