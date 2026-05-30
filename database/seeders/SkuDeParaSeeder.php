<?php

namespace Database\Seeders;

use App\Models\Fornecedor;
use App\Models\Loja;
use App\Models\Produto;
use App\Models\SkuDePara;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkuDeParaSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $loja = Loja::query()->firstOrFail();
        $empresaId = $loja->empresa_id;
        $lojaId    = $loja->id;

        // -----------------------------------------------------------------
        // Fornecedor: Distribuidora Paulista de Baterias (CNPJ do XML teste)
        // -----------------------------------------------------------------
        $distribuidora = Fornecedor::updateOrCreate(
            ['empresa_id' => $empresaId, 'cnpj' => '12345678000199'],
            [
                'nome'      => 'Distribuidora Paulista de Baterias LTDA',
                'ie'        => '123456789000',
                'logradouro'=> 'Rua das Indústrias',
                'numero'    => '1000',
                'bairro'    => 'Distrito Industrial',
                'municipio' => 'São Paulo',
                'uf'        => 'SP',
                'cep'       => '01100000',
            ]
        );

        // -----------------------------------------------------------------
        // Fornecedor secundário: Heliar Distribuidora
        // -----------------------------------------------------------------
        $heliarDist = Fornecedor::updateOrCreate(
            ['empresa_id' => $empresaId, 'cnpj' => '98765432000100'],
            [
                'nome'      => 'Heliar Distribuidora Nacional LTDA',
                'ie'        => '987654321000',
                'logradouro'=> 'Av. das Baterias',
                'numero'    => '500',
                'bairro'    => 'Parque Industrial',
                'municipio' => 'Mogi das Cruzes',
                'uf'        => 'SP',
                'cep'       => '08710000',
            ]
        );

        // -----------------------------------------------------------------
        // Produtos (garante que existam na loja)
        // -----------------------------------------------------------------
        $moura60 = Produto::updateOrCreate(
            ['empresa_id' => $empresaId, 'loja_id' => $lojaId, 'nome' => 'Bateria Moura 60D'],
            ['sku' => 'BAT-MOURA-60D', 'preco_unitario' => 720.00, 'preco_custo' => 320.00, 'quantidade' => 8]
        );

        $heliar70 = Produto::updateOrCreate(
            ['empresa_id' => $empresaId, 'loja_id' => $lojaId, 'nome' => 'Bateria Heliar 70D'],
            ['sku' => 'BAT-HELIAR-70D', 'preco_unitario' => 650.00, 'preco_custo' => 450.00, 'quantidade' => 12]
        );

        $moura90 = Produto::updateOrCreate(
            ['empresa_id' => $empresaId, 'loja_id' => $lojaId, 'nome' => 'Bateria Moura 90Ah'],
            ['sku' => 'BAT-MOURA-90AH', 'preco_unitario' => 950.00, 'preco_custo' => 720.00, 'quantidade' => 4]
        );

        $heliar45 = Produto::updateOrCreate(
            ['empresa_id' => $empresaId, 'loja_id' => $lojaId, 'nome' => 'Bateria Heliar Free 45Ah'],
            ['sku' => 'BAT-HELIAR-45F', 'preco_unitario' => 380.00, 'preco_custo' => 210.00, 'quantidade' => 20]
        );

        // -----------------------------------------------------------------
        // De-Para: Distribuidora Paulista → produtos
        // cProd usados no XML de teste: MOURA60, HELIAR70
        // -----------------------------------------------------------------
        $mapeamentos = [
            // Distribuidora Paulista
            [$distribuidora->id, 'MOURA60',  $moura60->id],
            [$distribuidora->id, 'HELIAR70', $heliar70->id],
            [$distribuidora->id, 'MOURA90',  $moura90->id],
            [$distribuidora->id, 'HELIAR45', $heliar45->id],

            // Heliar Distribuidora Nacional (SKUs diferentes para os mesmos produtos)
            [$heliarDist->id, 'HEL-70AH',  $heliar70->id],
            [$heliarDist->id, 'HEL-45FREE', $heliar45->id],
        ];

        foreach ($mapeamentos as [$fornecedorId, $skuFornecedor, $produtoId]) {
            SkuDePara::updateOrCreate(
                [
                    'empresa_id'     => $empresaId,
                    'loja_id'        => $lojaId,
                    'fornecedor_id'  => $fornecedorId,
                    'sku_fornecedor' => $skuFornecedor,
                ],
                ['produto_id' => $produtoId]
            );
        }
    }
}
