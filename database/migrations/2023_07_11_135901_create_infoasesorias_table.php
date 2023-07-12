<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('infoasesorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',50);
            $table->string('desc',100);
            $table->decimal('precio', 6, 2); //8888.99
            $table->tinyInteger('active')->default(1);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('statuscv_id')->default(12);
            $table->foreign('statuscv_id')->references('id')->on('infostatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infoasesorias');
    }
};
