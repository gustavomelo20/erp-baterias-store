<?php

namespace Database\Seeders;

use App\Models\Loja;
use App\Models\Produto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with products.
     */
    public function run(): void
    {
        $loja = Loja::query()->firstOrFail();

        Produto::updateOrCreate([
            'empresa_id' => $loja->empresa_id,
            'loja_id' => $loja->id,
            'nome' => 'Luxor 60 D',
        ], [
            'preco_unitario' => 340.00,
            'preco_custo' => 280.00,
            'quantidade' => 15,
        ]);

        Produto::updateOrCreate([
            'empresa_id' => $loja->empresa_id,
            'loja_id' => $loja->id,
            'nome' => 'Bateria Moura 60D',
        ], [
            'preco_unitario' => 720.00,
            'preco_custo' => 580.00,
            'quantidade' => 8,
        ]);

        Produto::updateOrCreate([
            'empresa_id' => $loja->empresa_id,
            'loja_id' => $loja->id,
            'nome' => 'Bateria Maxion 75D',
        ], [
            'preco_unitario' => 850.00,
            'preco_custo' => 680.00,
            'quantidade' => 5,
        ]);

        Produto::updateOrCreate([
            'empresa_id' => $loja->empresa_id,
            'loja_id' => $loja->id,
            'nome' => 'Bateria Heliar 70D',
        ], [
            'preco_unitario' => 650.00,
            'preco_custo' => 520.00,
            'quantidade' => 12,
        ]);

        Produto::updateOrCreate([
            'empresa_id' => $loja->empresa_id,
            'loja_id' => $loja->id,
            'nome' => 'Bateria Accent 60D',
        ], [
            'preco_unitario' => 380.00,
            'preco_custo' => 300.00,
            'quantidade' => 20,
        ]);
    }
}
