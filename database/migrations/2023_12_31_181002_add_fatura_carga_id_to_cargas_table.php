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
        Schema::table('cargas', function (Blueprint $table) {
            $table->unsignedBigInteger('fatura_carga_id')->nullable();
            $table->foreign('fatura_carga_id')->references('id')->on('fatura_cargas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cargas', function (Blueprint $table) {
            $table->dropForeign(['fatura_carga_id']);
            $table->dropColumn('fatura_carga_id');
        });
    }
};
