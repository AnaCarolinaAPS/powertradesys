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
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->time('hora');
            $table->string('responsavel'); //nome do funcionário
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->unsignedBigInteger('freteiro_id');
            $table->foreign('freteiro_id')->references('id')->on('freteiros')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};
