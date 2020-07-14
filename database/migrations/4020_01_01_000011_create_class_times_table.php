<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateClassTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_times', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('classroom_id')->unsigned();
            $table->string('subject');
            $table->dateTime('start_at');
            $table->decimal('hours');
            $table->longText('content');
            $table->tinyInteger('confirmed')->default(1);
            $table->tinyInteger('type')->default(0);
            $table->timestamps();

            $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');

            $table->index(['classroom_id', 'start_at', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_times');
    }
}
