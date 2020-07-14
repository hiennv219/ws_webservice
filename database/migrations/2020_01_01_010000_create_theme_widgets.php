<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateThemeWidgets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_widgets', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('widget_name');
            $table->string('theme_name')->default('');
            $table->string('placeholder');
            $table->text('constructing_data');
            $table->boolean('active')->default(true);
            $table->tinyInteger('order')->unsigned()->default(0);
            $table->timestamps();

            $table->index(['widget_name', 'placeholder', 'order', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theme_widgets');
    }
}
