<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRealtimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realtimes', function (Blueprint $table) {
            $table->increments('id');
            $table->string("extension",50);
            $table->char("ext_status",1);
            $table->string("ext_status_text",50);
            $table->string("info",50);
            $table->string("queue",50);
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
        Schema::dropIfExists('realtimes');
    }
}
