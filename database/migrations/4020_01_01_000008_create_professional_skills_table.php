<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateProfessionalSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professional_skills', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->timestamps();

            $table->index(['created_at']);
        });

        Schema::create('professional_skill_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('skill_id')->unsigned();
            $table->string('locale');
            $table->string('name');
            $table->text('description')->nullable();

            $table->foreign('skill_id')->references('id')->on('professional_skills')->onDelete('cascade');

            $table->unique(['skill_id', 'locale']);
            $table->index(['skill_id', 'locale', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('professional_skill_translations');
        Schema::dropIfExists('professional_skills');
    }
}
