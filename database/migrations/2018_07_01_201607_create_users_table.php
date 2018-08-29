<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_name')->nullable();
            $table->string('email');
            $table->string('password')->nullable();
            $table->string('pin')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('cell')->nullable();
            $table->double('initial_weight', 5, 2)->nullable();
            $table->double('goal_weight', 5, 2)->default(0);
            $table->string('barcode')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('admin')->default(false);
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
        Schema::drop('users');
    }
}
