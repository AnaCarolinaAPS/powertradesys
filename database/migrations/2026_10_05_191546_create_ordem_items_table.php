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
        Schema::create('ordem_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordem_id');
            $table->foreign('ordem_id')->references('id')->on('ordems')->onDelete('cascade');
            $table->unsignedBigInteger('motor_id');
            $table->foreign('motor_id')->references('id')->on('motors')->onDelete('cascade');
            $table->integer('quantidade')->default(1);
            $table->decimal('valor', 10, 2)->nullable();
            $table->enum('tipo_moeda', ['dolar', 'reais', 'guaranis'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordem_items');
    }
};
