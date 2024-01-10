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
        Schema::table('invoices', function (Blueprint $table) {
            // $table->unsignedBigInteger('fatura_carga_id');
            // $table->foreign('fatura_carga_id')->references('id')->on('fatura_cargas')->onDelete('set null');

            $table->unsignedBigInteger('fatura_carga_id');
            $table->foreign('fatura_carga_id')->references('id')->on('fatura_cargas')->onDelete('cascade');

            // $table->foreign('fatura_cargas_id')
            //     ->references('id')
            //     ->on('fatura_cargas')
            //     ->onDelete('set null'); // Ajuste conforme a lógica desejada ao deletar a fatura_cargas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['fatura_carga_id']);
            $table->dropColumn('fatura_carga_id');
        });
    }
};
