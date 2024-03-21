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
        Schema::table('cargas', function (Blueprint $table) {
            $table->decimal('peso_guia', 10, 2)->nullable();
            $table->string('guia_aerea')->nullable();
            $table->unsignedBigInteger('transportadora_id')->nullable();
            $table->foreign('transportadora_id')->references('id')->on('fornecedors')->onDelete('cascade');
            // $table->unsignedBigInteger('servico_id');
            // $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cargas', function (Blueprint $table) {
            $table->dropForeign(['transportadora_id']);
            $table->dropColumn('transportadora_id');
            $table->dropColumn('peso_guia');
            $table->dropColumn('guia_aerea');
            // $table->dropForeign(['servico_id']);
            // $table->dropColumn('servico_id');
        });
    }
};
