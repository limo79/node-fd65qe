<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseExamAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_exam_answers', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('course_exam_id')->unsigned();
            $table->bigInteger('question_id')->unsigned();
            $table->bigInteger('student_id')->unsigned();
            $table->integer('selected')->unsigned();
            $table->integer('true')->unsigned();
            $table->integer('score')->unsigned();
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
        Schema::dropIfExists('course_exam_answers');
    }
}
