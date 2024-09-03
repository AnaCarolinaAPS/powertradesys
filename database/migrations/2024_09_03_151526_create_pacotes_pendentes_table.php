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
        Schema::create('pacotes_pendentes', function (Blueprint $table) {
            $table->id();
            $table->string('rastreio');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->date('data_pedido');
            $table->date('data_miami')->nullable();
            $table->enum('status', ['aguardando', 'solicitado', 'buscando', 'em sistema', 'encontrado'])->default('aguardando');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacotes_pendentes');
    }
};
