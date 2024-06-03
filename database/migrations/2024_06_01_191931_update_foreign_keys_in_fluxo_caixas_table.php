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
            // Dropping the old foreign keys
            $table->dropForeign(['fechamento_caixa_id']);
            $table->dropForeign(['caixa_origem_id']);
            $table->dropForeign(['caixa_destino_id']);

            // Renaming the columns using change            
            $table->dropColumn('caixa_origem_id');
            $table->dropColumn('caixa_destino_id');
            $table->dropColumn('fechamento_caixa_id');

            // Adding the new foreign keys
            $table->unsignedBigInteger('fechamento_origem_id');
            $table->unsignedBigInteger('fechamento_destino_id')->nullable();
            $table->foreign('fechamento_origem_id')->references('id')->on('fechamento_caixas')->onDelete('cascade');
            $table->foreign('fechamento_destino_id')->references('id')->on('fechamento_caixas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fluxo_caixas', function (Blueprint $table) {
            // Dropping the new foreign keys
            $table->dropForeign(['fechamento_origem_id']);
            $table->dropForeign(['fechamento_destino_id']);
            $table->dropColumn('fechamento_origem_id');
            $table->dropColumn('fechamento_destino_id');

            // Renaming the columns back to original
            $table->unsignedBigInteger('caixa_origem_id');
            $table->unsignedBigInteger('caixa_destino_id');

            // Adding the old foreign keys back
            $table->foreign('caixa_origem_id')->references('id')->on('caixas')->onDelete('cascade');
            $table->foreign('caixa_destino_id')->references('id')->on('caixas')->onDelete('cascade');

            $table->unsignedBigInteger('fechamento_caixa_id');
            $table->foreign('fechamento_caixa_id')->references('id')->on('fechamento_caixas')->onDelete('cascade');
        });
    }
};
