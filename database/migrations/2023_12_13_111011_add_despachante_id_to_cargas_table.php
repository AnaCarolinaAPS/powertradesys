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
            $table->foreign('despachante_id')->nullable()->references('id')->on('fornecedors')->onDelete('cascade');
            $table->unsignedBigInteger('despachante_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cargas', function (Blueprint $table) {
            $table->dropForeign(['despachante_id']);
            $table->dropColumn('despachante_id');
        });
    }
};
