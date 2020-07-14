<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateTopicsTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics_teachers', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('topic_id')->unsigned();
            $table->bigInteger('teacher_id')->unsigned();

            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
            $table->foreign('teacher_id')->references('user_id')->on('teachers')->onDelete('cascade');

            $table->primary(['topic_id', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics_teachers');
    }
}
