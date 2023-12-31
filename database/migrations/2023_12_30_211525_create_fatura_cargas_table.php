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
        Schema::create('fatura_cargas', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->enum('status', ['pendente', 'liberada'])->default('pendente');
            $table->unsignedBigInteger('carga_id');
            $table->foreign('carga_id')->references('id')->on('cargas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fatura_cargas');
    }
};
