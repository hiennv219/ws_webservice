<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateSalaryJumpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_jumps', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->decimal('jump');
            $table->string('currency');
            $table->dateTime('apply_from');
            $table->timestamps();

            $table->index(['apply_from', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_jumps');
    }
}
