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
        Schema::create('contas_fixas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->enum('moeda', ['U$', 'G$', 'R$'])->default('G$');
            $table->decimal('valor', 10, 2);
            $table->date('data_vencimento'); // Exemplo: sempre no dia 27
            $table->foreign('categoria_id')->nullable()->references('id')->on('categorias')->onDelete('cascade');
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->foreign('subcategoria_id')->nullable()->references('id')->on('categorias')->onDelete('cascade');
            $table->unsignedBigInteger('subcategoria_id')->nullable();
            $table->boolean('ativa')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas_fixas');
    }
};
