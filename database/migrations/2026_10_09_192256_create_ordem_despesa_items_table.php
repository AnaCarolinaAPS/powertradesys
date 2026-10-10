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
        Schema::create('ordem_despesa_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordem_despesa_id');
            $table->foreign('ordem_despesa_id')->references('id')->on('ordem_despesas')->onDelete('cascade');
            $table->unsignedBigInteger('motor_proovedor_servico_id');
            $table->foreign('motor_proovedor_servico_id')->references('id')->on('motor_proovedor_servicos')->onDelete('cascade');
            $table->string('referencia')->nullable();
            $table->enum('tipo_moeda', ['dolar', 'reais', 'guaranis'])->default('dolar');
            $table->decimal('valor', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordem_despesa_items');
    }
};
