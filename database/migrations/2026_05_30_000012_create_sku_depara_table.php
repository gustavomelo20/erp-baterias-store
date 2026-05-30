<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sku_depara', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('loja_id')->constrained('lojas')->cascadeOnDelete();
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->cascadeOnDelete();
            $table->string('sku_fornecedor');
            $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['empresa_id', 'loja_id', 'fornecedor_id', 'sku_fornecedor'], 'sku_depara_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sku_depara');
    }
};
