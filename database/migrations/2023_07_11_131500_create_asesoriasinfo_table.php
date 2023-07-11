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
        Schema::create('asesoriasinfo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',50);
            $table->string('desc',100);
            $table->decimal('precio', 6, 2); //8888.99
            // $table->Integer('nsemanas',2);
            $table->tinyInteger('active')->default(1);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesoriasinfo');
    }
};
