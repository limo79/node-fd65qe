<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->text('name')->nullable();
            $table->text('text')->nullable();
            $table->text('link')->nullable();
            $table->string('register_date',20)->nullable();
            $table->string('expire_date',20)->nullable();
            $table->integer('pos')->default(0)->nullable();
            $table->string('pic',50)->nullable();
            $table->integer('show')->default(1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sliders');
    }
}
