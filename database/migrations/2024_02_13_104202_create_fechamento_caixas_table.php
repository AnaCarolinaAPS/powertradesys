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
        Schema::create('fechamento_caixas', function (Blueprint $table) {
            $table->id();
            $table->string('mes');
            $table->string('ano');
            $table->unsignedBigInteger('caixa_id');
            $table->foreign('caixa_id')->references('id')->on('caixas')->onDelete('cascade');
            $table->decimal('saldo_inicial', 10, 2);
            $table->decimal('saldo_final', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fechamento_caixas');
    }
};
