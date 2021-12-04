<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary();
            $table->string('register',20)->nullable();
            $table->string('name',100)->nullable();
            $table->string('mobile',11)->nullable();
            $table->string('email',200)->nullable();
            $table->string('national',10)->nullable();
            $table->string('birthday',20)->nullable();
            $table->text('cv')->nullable();
            $table->longText('address')->nullable();
            $table->string('pic',50)->default('noPic.jpg')->nullable();
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
        Schema::dropIfExists('teachers');
    }
}
