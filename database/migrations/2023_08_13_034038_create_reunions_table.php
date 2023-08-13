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
        Schema::create('reunions', function (Blueprint $table) {
            $table->id();
            $table->string('tema',50);
            $table->string('urlmeet',80);
            $table->unsignedBigInteger('expert_id');
            $table->foreign('expert_id')->references('id')->on('users');
            $table->unsignedBigInteger('registro_id');
            $table->foreign('registro_id')->references('id')->on('registros');
            $table->dateTime('fecha');
            $table->boolean('confirmacion')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reunions');
    }
};
