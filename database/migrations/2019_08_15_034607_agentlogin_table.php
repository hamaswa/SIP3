<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgentloginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agentlogin', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event');
            $table->string('privilege');
            $table->string('queue');
            $table->string('login_time');
            $table->string('logout_time');
            $table->string('membername');
            $table->string('interface');
            $table->string('stateinterface');
            $table->string('membership');
            $table->string('penalty');
            $table->string('callstaken');
            $table->string('lastcall');
            $table->string('incall');
            $table->string('status');
            $table->string('paused');
            $table->string('pausedreason');
            $table->string('ringinuse');
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
        Schema::dropIfExists('agentlogin');
    }
}
