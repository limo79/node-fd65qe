<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary();
            $table->bigInteger('account_id')->unsigned();
            $table->bigInteger('course_id')->nullable()->unsigned();
            $table->string('date',20)->nullable();
            $table->string('time',20)->nullable();
            $table->integer('type')->default(0)->unsigned();
            $table->bigInteger('paid')->default(0)->nullable();
            $table->longText('for')->nullable();
            $table->string('credit',50)->nullable();
            $table->integer('status')->default(0)->unsigned();
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
        Schema::dropIfExists('transactions');
    }
}
