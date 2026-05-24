<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table): void {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('lojas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('nome');
            $table->timestamps();

            $table->unique(['empresa_id', 'nome']);
        });

        Schema::create('loja_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('loja_id')->constrained('lojas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['loja_id', 'user_id']);
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
        });

        Schema::table('produtos', function (Blueprint $table): void {
            $table->dropUnique('produtos_nome_unique');
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('loja_id')->nullable()->after('empresa_id')->constrained('lojas')->cascadeOnDelete();
        });

        Schema::table('vendas', function (Blueprint $table): void {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('loja_id')->nullable()->after('empresa_id')->constrained('lojas')->cascadeOnDelete();
        });

        Schema::table('produtos', function (Blueprint $table): void {
            $table->unique(['empresa_id', 'loja_id', 'nome']);
        });

        $empresaId = (int) DB::table('empresas')->insertGetId([
            'nome' => 'Empresa Principal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $lojaId = (int) DB::table('lojas')->insertGetId([
            'empresa_id' => $empresaId,
            'nome' => 'Loja Matriz',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')
            ->whereNull('empresa_id')
            ->update(['empresa_id' => $empresaId]);

        $userIds = DB::table('users')->pluck('id');
        foreach ($userIds as $userId) {
            DB::table('loja_user')->insertOrIgnore([
                'loja_id' => $lojaId,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('produtos')
            ->whereNull('empresa_id')
            ->update([
                'empresa_id' => $empresaId,
                'loja_id' => $lojaId,
            ]);

        DB::table('vendas')
            ->whereNull('empresa_id')
            ->update([
                'empresa_id' => $empresaId,
                'loja_id' => $lojaId,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendas', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('loja_id');
            $table->dropConstrainedForeignId('empresa_id');
        });

        Schema::table('produtos', function (Blueprint $table): void {
            $table->dropUnique(['empresa_id', 'loja_id', 'nome']);
            $table->dropConstrainedForeignId('loja_id');
            $table->dropConstrainedForeignId('empresa_id');
            $table->unique('nome');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('empresa_id');
        });

        Schema::dropIfExists('loja_user');
        Schema::dropIfExists('lojas');
        Schema::dropIfExists('empresas');
    }
};
