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
        Schema::create('folha_pagamento_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('folha_pagamento_id');
            $table->foreign('folha_pagamento_id')->references('id')->on('folha_pagamentos')->onDelete('cascade');
            $table->unsignedBigInteger('servicos_funcionario_id');
            $table->foreign('servicos_funcionario_id')->references('id')->on('servicos_funcionarios')->onDelete('cascade');
            $table->date('data');
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
        Schema::dropIfExists('folha_pagamento_items');
    }
};
