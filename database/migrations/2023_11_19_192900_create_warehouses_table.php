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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('wr');
            $table->date('data');
            // Adicione a chave estrangeira para a tabela shippers
            $table->unsignedBigInteger('shipper_id');
            $table->foreign('shipper_id')->references('id')->on('shippers')->onDelete('cascade');
            $table->timestamps();
            // Criar observações
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
