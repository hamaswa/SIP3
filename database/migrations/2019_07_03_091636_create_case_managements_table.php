<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaseManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_managements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("user_id");
            $table->string("subject");
            $table->string("name");
            $table->string("taxi_no");
            $table->string("contact_no");
            $table->string("incident_location");
            $table->string("pickup_point_a");
            $table->string("pickup_point_b");
            $table->string("case_type");
            $table->string("case_status");
            $table->text("comments");
            $table->softDeletes();
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
        Schema::dropIfExists('case_managements');
    }
}
