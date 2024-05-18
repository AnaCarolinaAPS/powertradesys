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
        Schema::create('despesa_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('despesa_id');
            $table->unsignedBigInteger('pagamento_id');
            $table->decimal('valor_recebido', 10, 2); // Adicionando o campo para controlar o valor pago
            $table->foreign('despesa_id')->references('id')->on('despesas')->onDelete('cascade');
            $table->foreign('pagamento_id')->references('id')->on('pagamentos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despesa_pagamentos');
    }
};
