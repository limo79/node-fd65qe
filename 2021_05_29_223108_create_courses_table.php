<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigInteger('id')->primary()->unsigned();
            $table->bigInteger('group_id')->unsigned();
            $table->bigInteger('teacher_id')->unsigned();
            $table->integer('type')->unsigned();
            $table->text('title')->nullable();
            $table->longText('text')->nullable();
            $table->string('start_date',20)->nullable();
            $table->string('end_date',20)->nullable();
            $table->string('from_time',20)->nullable();
            $table->string('to_time',20)->nullable();
            $table->string('days',100)->nullable();
            $table->integer('capacity')->default(30)->unsigned();
            $table->bigInteger('price')->unsigned();
            $table->string('pic',20)->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('courses');
    }
}
