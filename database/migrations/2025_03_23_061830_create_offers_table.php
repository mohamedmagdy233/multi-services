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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_type_id')->constrained()->onDelete('cascade')->cascadeOnUpdate();
            $table->foreignId('sub_service_type_id')->constrained()->onDelete('cascade')->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->cascadeOnUpdate();
            $table->string('title');
            $table->string('location_name')->nullable();
            $table->string('country')->nullable();
            $table->text('body');
            $table->double('price', 8, 2)->nullable();
            $table->boolean('is_open')->default(1)->comment('1:open, 0:close');
            $table->boolean('is_phone_hide')->default(1)->comment(' 0:no 1:yes');
            $table->tinyInteger('status')->default(0)->comment('0:pending, 1:active, 2:rejected');
            $table->string('lat')->nullable();
            $table->string('long')->nullable();

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
        Schema::dropIfExists('offers');
    }
};
