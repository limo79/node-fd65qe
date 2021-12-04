<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('account_id')->unsigned();
            $table->string('name',100)->nullable();
            $table->string('username',100)->nullable();
            $table->string('password',255)->nullable();
            $table->string('mobile',12)->nullable();
            $table->string('last',100)->nullable();
            $table->integer('level')->default(1)->nullable();
            $table->boolean('block')->default(false)->nullable();
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
        Schema::dropIfExists('accounts');
    }
}
