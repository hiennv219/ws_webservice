<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->integer('conversation_id')->unsigned();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('device_id')->unsigned()->nullable();
            $table->string('content');
            $table->timestamps();

            $table->foreign('conversation_id')->references('id')->on('conversations')
                ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('device_id')->references('id')->on('devices')
                ->onDelete('cascade');

            $table->index(['conversation_id', 'user_id', 'device_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
