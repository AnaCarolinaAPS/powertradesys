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
        Schema::create('contas_pagars', function (Blueprint $table) {
            $table->id();
            $table->string('descricao')->nullable();
            $table->decimal('valor', 10, 2);
            $table->enum('moeda', ['U$', 'G$', 'R$'])->default('G$');
            $table->date('data_vencimento');
            $table->foreign('contas_fixa_id')->nullable()->references('id')->on('contas_fixas')->onDelete('cascade');
            $table->unsignedBigInteger('contas_fixa_id')->nullable(); // Se for uma conta recorrente
            $table->foreign('categoria_id')->nullable()->references('id')->on('categorias')->onDelete('cascade');
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->foreign('subcategoria_id')->nullable()->references('id')->on('categorias')->onDelete('cascade');
            $table->unsignedBigInteger('subcategoria_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas_pagars');
    }
};
