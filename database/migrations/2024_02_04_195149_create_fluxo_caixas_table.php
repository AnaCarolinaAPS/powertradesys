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
        Schema::create('fluxo_caixas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->enum('tipo', ['entrada', 'saida', 'transferencia', 'cambio']);
            $table->foreignId('caixa_origem_id')->references('id')->on('caixas')->onDelete('cascade');
            $table->decimal('valor_origem', 10, 2);
            $table->foreignId('caixa_destino_id')->nullable()->references('id')->on('caixas')->onDelete('cascade');
            $table->decimal('valor_destino', 10, 2)->nullable();
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
            $table->unsignedBigInteger('categoria_id');
            $table->foreign('subcategoria_id')->references('id')->on('categorias')->onDelete('cascade');
            $table->unsignedBigInteger('subcategoria_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fluxo_caixas');
    }
};
