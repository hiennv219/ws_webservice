<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreatePolls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->tinyInteger('multi_choice')->default(0); // single choice
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('poll_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('poll_id')->unsigned();
            $table->string('locale');
            $table->string('name');
            $table->string('description')->nullable();

            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');

            $table->unique(['poll_id', 'locale']);
            $table->index(['poll_id', 'locale']);
        });

        Schema::create('poll_choices', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('poll_id')->unsigned();
            $table->integer('votes')->unsigned()->default(0);
            $table->integer('order')->unsigned()->default(0);
            $table->timestamps();

            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');

            $table->index(['poll_id', 'order', 'created_at']);
        });

        Schema::create('poll_choice_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('choice_id')->unsigned();
            $table->string('locale');
            $table->string('name');

            $table->foreign('choice_id')->references('id')->on('poll_choices')->onDelete('cascade');

            $table->unique(['choice_id', 'locale']);
            $table->index(['choice_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poll_choice_translations');
        Schema::dropIfExists('poll_choices');
        Schema::dropIfExists('poll_translations');
        Schema::dropIfExists('polls');
    }
}
