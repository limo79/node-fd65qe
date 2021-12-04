<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTitleContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_title_contents', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('course_title_id')->unsigned();
            $table->text('title')->nullable();
            $table->longText('text')->nullable();
            $table->string('file',50)->nullable();
            $table->string('ext',5)->nullable();
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
        Schema::dropIfExists('course_title_contents');
    }
}
