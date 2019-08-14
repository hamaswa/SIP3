<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueuestatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queuestats', function (Blueprint $table) {
            $table->increments('id');
            $table->string("queue",50);
            $table->integer("incall");
            $table->integer("answer");
            $table->integer("abandon");
            $table->integer("holdtime");
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
        Schema::dropIfExists('queuestats');
    }
}
