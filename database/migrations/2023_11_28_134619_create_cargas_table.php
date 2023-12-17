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
        Schema::create('cargas', function (Blueprint $table) {
            $table->id();
            $table->date('data_enviada');
            $table->date('data_recebida')->nullable();
            $table->text('observacoes')->nullable();
            // $table->unsignedBigInteger('fornecedor_id');
            // $table->foreign('fornecedor_id')->references('id')->on('fornecedors')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargas');
    }
};
