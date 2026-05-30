<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table): void {
            $table->string('sku')->nullable()->after('nome');
            $table->unique(['empresa_id', 'loja_id', 'sku']);
        });
    }

    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table): void {
            $table->dropUnique(['empresa_id', 'loja_id', 'sku']);
            $table->dropColumn('sku');
        });
    }
};
