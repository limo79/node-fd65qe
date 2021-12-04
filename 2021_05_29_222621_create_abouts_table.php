<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name',500)->nullable();
            $table->string('managment',50)->nullable();
            $table->text('tel')->nullable();
            $table->text('support')->nullable();
            $table->text('fax')->nullable();
            $table->text('email')->nullable();
            $table->text('webite')->nullable();
            $table->longText('address')->nullable();
            $table->text('location')->nullable();
            $table->longText('text')->nullable();
            $table->longText('honor')->nullable();
            $table->string('logo',50)->nullable();
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
        Schema::dropIfExists('abouts');
    }
}
