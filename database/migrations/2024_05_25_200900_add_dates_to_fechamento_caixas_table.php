<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fechamento_caixas', function (Blueprint $table) {
            $table->date('start_date')->default(DB::raw('CURRENT_DATE'))->before('mes'); // Adiciona o campo start_date
            $table->date('end_date')->default(DB::raw('CURRENT_DATE'))->before('mes');   // Adiciona o campo end_date
        });

        // Remover colunas "mes" e "ano"
        Schema::table('fechamento_caixas', function (Blueprint $table) {
            $table->dropColumn('mes');
            $table->dropColumn('ano');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Adicionar novamente as colunas "mes" e "ano"
        Schema::table('fechamento_caixas', function (Blueprint $table) {
            $table->string('mes')->after('end_date');  // Adiciona a coluna "mes" após "end_date"
            $table->integer('ano')->after('mes');      // Adiciona a coluna "ano" após "mes"
        });

        Schema::table('fechamento_caixas', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
        });
    }
};
