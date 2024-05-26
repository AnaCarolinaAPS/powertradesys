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
        Schema::create('servicos_funcionarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('funcionario_id');
            $table->foreign('funcionario_id')->references('id')->on('funcionarios')->onDelete('cascade');
            $table->string('descricao');
            $table->enum('tipo', ['fixo', 'variavel'])->default('fixo');
            $table->enum('moeda', ['U$', 'G$', 'R$'])->default('G$');
            $table->decimal('valor', 10, 2)->nullable();
            $table->enum('frequencia', ['mensal', 'quinzenal', 'semanal'])->default('mensal');
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicos_funcionarios');
    }
};
