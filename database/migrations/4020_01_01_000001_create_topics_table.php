<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->timestamps();

            $table->index(['created_at']);
        });

        Schema::create('topic_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('topic_id')->unsigned();
            $table->string('locale');
            $table->string('name');
            $table->text('description')->nullable();

            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');

            $table->unique(['topic_id', 'locale']);
            $table->index(['topic_id', 'locale', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topic_translations');
        Schema::dropIfExists('topics');
    }
}
