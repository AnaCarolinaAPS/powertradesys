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
        Schema::create('entrega_pacotes', function (Blueprint $table) {
            $table->id();
            $table->decimal('peso', 8, 1)->nullable();
            $table->integer('qtd')->nullable();
            $table->unsignedBigInteger('pacote_id');
            $table->foreign('pacote_id')->references('id')->on('pacotes')->onDelete('cascade');
            $table->unsignedBigInteger('entrega_id');
            $table->foreign('entrega_id')->references('id')->on('entregas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrega_pacotes');
    }
};
