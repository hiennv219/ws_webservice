<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateRegisterLearningRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_learning_requests', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('processed_by_id')->unsigned()->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->bigInteger('student_id')->unsigned();
            $table->bigInteger('teacher_id')->unsigned()->nullable();
            $table->integer('study_level_id')->unsigned()->nullable();
            $table->integer('study_problem_id')->unsigned()->nullable();
            $table->integer('study_course_id')->unsigned()->nullable();
            $table->boolean('for_children')->default(false);
            $table->string('children_full_name')->default('');
            $table->tinyInteger('age_range')->default(0);
            $table->string('learning_targets')->default('');
            $table->string('learning_forms')->default('');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->foreign('processed_by_id')->references('id')->on('users');
            $table->foreign('student_id')->references('user_id')->on('students')->onDelete('cascade');
            $table->foreign('teacher_id')->references('user_id')->on('teachers')->onDelete('cascade');
            $table->foreign('study_level_id')->references('id')->on('meta');
            $table->foreign('study_problem_id')->references('id')->on('meta');
            $table->foreign('study_course_id')->references('id')->on('meta');

            $table->index(['student_id', 'status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('register_learning_requests');
    }
}
