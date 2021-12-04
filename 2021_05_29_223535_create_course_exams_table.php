<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_exams', function (Blueprint $table)
        {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('course_id')->unsigned();
            $table->integer('type')->default(0)->unsigned();
            $table->text('title')->nullable();
            $table->longText('text')->nullable();
            $table->string('start_date',20)->nullable();
            $table->string('from_time',20)->nullable();
            $table->string('to_time',20)->nullable();
            $table->integer('total')->default(100)->unsigned();
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
        Schema::dropIfExists('course_exams');
    }
}
