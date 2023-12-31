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
        Schema::create('invoice_pacotes', function (Blueprint $table) {
            $table->id();
            $table->decimal('peso', 8, 1);
            // $table->decimal('valor', 8, 2);
            $table->unsignedBigInteger('pacote_id');
            $table->foreign('pacote_id')->references('id')->on('pacotes')->onDelete('cascade');
            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_pacotes');
    }
};
