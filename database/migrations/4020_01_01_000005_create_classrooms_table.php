<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateClassroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('closed_by')->unsigned()->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->bigInteger('student_id')->unsigned();
            $table->bigInteger('teacher_id')->unsigned();
            $table->bigInteger('supporter_id')->unsigned();
            $table->string('name');
            $table->decimal('hours');
            $table->tinyInteger('status')->default(1); // OPENING
            $table->timestamps();

            $table->foreign('teacher_id')->references('user_id')->on('teachers')->onDelete('cascade');
            $table->foreign('student_id')->references('user_id')->on('students')->onDelete('cascade');
            $table->foreign('supporter_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['student_id', 'teacher_id', 'supporter_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classrooms');
    }
}
