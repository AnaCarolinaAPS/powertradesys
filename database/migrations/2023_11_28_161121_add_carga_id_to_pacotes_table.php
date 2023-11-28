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
        Schema::table('pacotes', function (Blueprint $table) {
            $table->unsignedBigInteger('carga_id')->nullable();
            $table->foreign('carga_id')->references('id')->on('cargas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pacotes', function (Blueprint $table) {
            $table->dropForeign(['carga_id']);
            $table->dropColumn('carga_id');
        });
    }
};
