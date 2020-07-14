<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateUserWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_works', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('company');
            $table->string('position')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('start_month')->unsigned()->nullable();
            $table->integer('start_year')->unsigned()->nullable();
            $table->tinyInteger('end_month')->unsigned()->nullable();
            $table->integer('end_year')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_works');
    }
}
