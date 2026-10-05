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
        Schema::create('ordems', function (Blueprint $table) {
            $table->id();
            $table->date('data_compra');
            $table->string('referencia');
            $table->string('cliente')->nullable();
            $table->date('data_recebido')->nullable();
            $table->date('data_entrega')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordems');
    }
};
