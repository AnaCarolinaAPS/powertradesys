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
        Schema::create('despesa_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('despesa_id');
            $table->foreign('despesa_id')->references('id')->on('despesas')->onDelete('cascade');
            $table->unsignedBigInteger('servico_fornecedor_id');
            $table->foreign('servico_fornecedor_id')->references('id')->on('servicos_fornecedors')->onDelete('cascade');
            $table->string('referencia')->nullable();
            $table->decimal('valor', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despesa_items');
    }
};
