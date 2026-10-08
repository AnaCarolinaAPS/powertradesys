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
        Schema::create('ordem_despesas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordem_id');
            $table->foreign('ordem_id')->references('id')->on('ordems')->onDelete('cascade');
            $table->unsignedBigInteger('motor_proovedors_id');
            $table->foreign('motor_proovedors_id')->references('id')->on('motor_proovedors')->onDelete('cascade');
            $table->date('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordem_despesas');
    }
};
