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
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('carga_id');
            $table->foreign('carga_id')->references('id')->on('cargas')->onDelete('cascade');
            $table->unsignedBigInteger('fornecedor');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedors')->onDelete('cascade');
            $table->date('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despesas');
    }
};
