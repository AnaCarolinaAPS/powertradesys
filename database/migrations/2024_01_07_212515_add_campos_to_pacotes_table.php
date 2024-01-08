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
        Schema::table('pacotes', function (Blueprint $table) {
            $table->integer('altura')->nullable();
            $table->integer('largura')->nullable();
            $table->integer('profundidade')->nullable();
            $table->decimal('volume', 8, 1)->nullable();
            $table->enum('tipo', ['envelope', 'caixa', 'pallet'])->default('caixa');
            $table->text('observacoes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pacotes', function (Blueprint $table) {
            $table->dropColumn('altura');
            $table->dropColumn('largura');
            $table->dropColumn('profundidade');
            $table->dropColumn('volume');
            $table->dropColumn('tipo');
            $table->dropColumn('observacoes');
        });
    }
};
