<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable()->unique();
            $table->string('email')->nullable()->unique();

            $table->string('google_email')->nullable()->unique();
            $table->string('facebook_email')->nullable()->unique();
            $table->string('apple_email')->nullable()->unique();
            $table->enum('social_type', ['google', 'facebook', 'apple'])->nullable();

            $table->string('password');
            $table->string('image')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('user_type')->default(1)->comment('1 = leader, 0 = User');


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
        Schema::dropIfExists('users');
    }
};
