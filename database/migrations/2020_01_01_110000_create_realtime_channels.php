<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateRealTimeChannels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realtime_channels', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->uuid('code');
            $table->tinyInteger('type')->default(0); // conversation
            $table->timestamps();

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
        Schema::dropIfExists('realtime_channels');
    }
}
