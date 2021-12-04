<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('group_id')->unsigned();
            $table->text('title')->nullable();
            $table->longText('ans1')->nullable();
            $table->longText('ans2')->nullable();
            $table->longText('ans3')->nullable();
            $table->longText('ans4')->nullable();
            $table->longText('true')->nullable();
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
        Schema::dropIfExists('questions');
    }
}
