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
        Schema::create('pacotes', function (Blueprint $table) {
            $table->id();
            $table->string('rastreio');
            $table->string('qtd');
            $table->decimal('peso_aprox', 8, 1);
            $table->decimal('peso', 8, 1)->nullable();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('cliente_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacotes');
    }
};
