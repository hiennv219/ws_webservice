<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateAppOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_options', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('key')->unique();
            $table->string('data_type');
            $table->text('value')->nullable();
            $table->string('registered_by')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_options');
    }
}
