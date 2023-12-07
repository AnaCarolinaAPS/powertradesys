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
        Schema::create('servicos_despachantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('despachante_id');
            $table->foreign('despachante_id')->references('id')->on('despachantes');
            // $table->enum('tipo', ['aereo', 'maritimo', 'compra', 'outros'])->default('aereo');
            $table->string('descricao');
            // $table->decimal('preco', 10, 2)->nullable();
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicos_despachantes');
    }
};
