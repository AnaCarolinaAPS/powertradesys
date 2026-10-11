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
        Schema::table('ordem_pagamentos', function (Blueprint $table) {
            $table->decimal('valor_cotacao', 10, 2)->nullable(); // Adicionando o campo para controlar o valor convertido para U$
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordem_pagamentos', function (Blueprint $table) {
            $table->dropColumn(['valor_cotacao']);
        });
    }
};
