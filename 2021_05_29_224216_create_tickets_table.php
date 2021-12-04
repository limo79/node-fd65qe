<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('account_id')->unsigned();
            $table->string('date',20)->nullable();
            $table->string('time',20)->nullable();
            $table->text('title')->nullable();
            $table->longText('text')->nullable();
            $table->string('attach',50)->nullable();
            $table->boolean('status')->default(false);
            $table->longText('reply')->nullable();
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
        Schema::dropIfExists('tickets');
    }
}
