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
        Schema::table('fluxo_caixas', function (Blueprint $table) {
            $table->enum('tipo', ['entrada', 'saida', 'despesa', 'salario', 'transferencia', 'cambio'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fluxo_caixas', function (Blueprint $table) {
            $table->enum('tipo', ['entrada', 'saida', 'despesa', 'transferencia', 'cambio'])->change();
        });
    }
};
