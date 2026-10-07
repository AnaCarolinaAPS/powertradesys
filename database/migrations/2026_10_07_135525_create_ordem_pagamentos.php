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
        Schema::create('ordem_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordem_id');
            $table->foreign('ordem_id')->references('id')->on('ordems')->onDelete('cascade');
            $table->unsignedBigInteger('pagamento_id');
            $table->foreign('pagamento_id')->references('id')->on('pagamentos')->onDelete('cascade');
            $table->decimal('valor_recebido', 10, 2); // Adicionando o campo para controlar o valor pago
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordem_pagamentos');
    }
};
