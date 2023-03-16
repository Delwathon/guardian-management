<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->string('gender');
            $table->string('religion');
            $table->string('city')->nullable();
            $table->string('mobile');
            $table->text('address')->nullable();
            $table->string('relationship')->nullable();
            $table->bigInteger('state_id')->unsigned()->nullable();
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('local_government_id')->unsigned()->nullable();
            $table->string('occupation')->nullable();
            $table->foreign('local_government_id')->references('id')->on('local_governments')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
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
        Schema::dropIfExists('guardians');
    }
};