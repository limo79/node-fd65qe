<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_offers', function (Blueprint $table) {
            $table->bigInteger('id')->primary()->unsigned();
            $table->bigInteger('course_id')->unsigned();
            $table->string('start_date',20)->nullable();
            $table->string('end_date',20)->nullable();
            $table->text('title')->nullable();
            $table->longText('text')->nullable();
            $table->integer('percent')->default(0)->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('course_offers');
    }
}
