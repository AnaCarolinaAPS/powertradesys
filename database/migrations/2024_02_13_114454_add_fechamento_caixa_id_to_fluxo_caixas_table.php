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
            $table->unsignedBigInteger('fechamento_caixa_id');
            $table->foreign('fechamento_caixa_id')->references('id')->on('fechamento_caixas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fluxo_caixas', function (Blueprint $table) {
            $table->dropForeign(['servico_id']);
            $table->dropColumn('servico_id');
        });
    }
};
