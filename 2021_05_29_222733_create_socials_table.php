<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socials', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('telegram',100)->nullable();
            $table->string('instagram',100)->nullable();
            $table->string('twiter',100)->nullable();
            $table->string('sorosh',100)->nullable();
            $table->string('whatsapp',100)->nullable();
            $table->string('igap',100)->nullable();
            $table->string('robika',100)->nullable();
            $table->string('aparat',100)->nullable();
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
        Schema::dropIfExists('socials');
    }
}
