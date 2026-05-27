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
        Schema::table('empresas', function (Blueprint $table): void {
            $table->string('razao_social')->nullable()->after('nome');
            $table->string('nome_fantasia')->nullable()->after('razao_social');
            $table->string('cnpj', 14)->nullable()->unique()->after('nome_fantasia');
            $table->string('inscricao_estadual', 30)->nullable()->after('cnpj');
            $table->string('inscricao_municipal', 30)->nullable()->after('inscricao_estadual');
            $table->string('regime_tributario', 20)->nullable()->after('inscricao_municipal');
            $table->string('cnae_principal', 20)->nullable()->after('regime_tributario');
            $table->string('email_fiscal')->nullable()->after('cnae_principal');
            $table->string('telefone', 20)->nullable()->after('email_fiscal');
            $table->string('cep', 8)->nullable()->after('telefone');
            $table->string('logradouro')->nullable()->after('cep');
            $table->string('numero', 20)->nullable()->after('logradouro');
            $table->string('complemento', 120)->nullable()->after('numero');
            $table->string('bairro', 120)->nullable()->after('complemento');
            $table->string('cidade', 120)->nullable()->after('bairro');
            $table->string('uf', 2)->nullable()->after('cidade');
            $table->string('codigo_municipio_ibge', 10)->nullable()->after('uf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table): void {
            $table->dropUnique('empresas_cnpj_unique');
            $table->dropColumn([
                'razao_social',
                'nome_fantasia',
                'cnpj',
                'inscricao_estadual',
                'inscricao_municipal',
                'regime_tributario',
                'cnae_principal',
                'email_fiscal',
                'telefone',
                'cep',
                'logradouro',
                'numero',
                'complemento',
                'bairro',
                'cidade',
                'uf',
                'codigo_municipio_ibge',
            ]);
        });
    }
};
