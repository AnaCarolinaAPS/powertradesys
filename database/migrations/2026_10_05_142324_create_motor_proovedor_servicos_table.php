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
        Schema::create('motor_proovedor_servicos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_servico', ['compra', 'despacho', 'envio', 'pickup', 'travessia', 'nota', 'outros'])->default('compra');
            $table->string('descricao')->nullable();
            $table->decimal('preco', 10, 2)->nullable();
            $table->enum('tipo_moeda', ['dolar', 'reais', 'guaranis'])->nullable();
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->unsignedBigInteger('motor_proovedor_id');
            $table->foreign('motor_proovedor_id')->references('id')->on('motor_proovedors');
            $table->unsignedBigInteger('motor_id');
            $table->foreign('motor_id')->references('id')->on('motors');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motor_proovedor_servicos');
    }
};
