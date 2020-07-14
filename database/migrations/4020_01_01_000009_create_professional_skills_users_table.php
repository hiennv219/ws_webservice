<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateProfessionalSkillsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professional_skills_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('skill_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->foreign('skill_id')->references('id')->on('professional_skills')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->primary(['skill_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('professional_skills_users');
    }
}
