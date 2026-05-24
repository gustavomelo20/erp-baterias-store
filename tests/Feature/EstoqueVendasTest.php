<?php

namespace Tests\Feature;

use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstoqueVendasTest extends TestCase
{
    use RefreshDatabase;

    public function test_registra_venda_e_debita_estoque(): void
    {
        $produto = Produto::query()->create([
            'nome' => 'Caneta Azul',
            'quantidade' => 10,
            'preco_unitario' => 2.50,
        ]);

        $response = $this->post(route('vendas.store'), [
            'produto_id' => $produto->id,
            'quantidade' => 3,
            'data_venda' => now()->toDateString(),
            'desconto' => 1.50,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('vendas', [
            'produto_id' => $produto->id,
            'quantidade' => 3,
            'desconto' => 1.50,
            'total' => 6.00,
        ]);

        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'quantidade' => 7,
        ]);
    }

    public function test_nao_permite_venda_com_estoque_insuficiente(): void
    {
        $produto = Produto::query()->create([
            'nome' => 'Caderno',
            'quantidade' => 2,
            'preco_unitario' => 30.00,
        ]);

        $response = $this->from(route('painel.index'))->post(route('vendas.store'), [
            'produto_id' => $produto->id,
            'quantidade' => 5,
            'data_venda' => now()->toDateString(),
            'desconto' => 0,
        ]);

        $response->assertRedirect(route('painel.index'));
        $response->assertSessionHasErrors('quantidade');

        $this->assertDatabaseCount('vendas', 0);
        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'quantidade' => 2,
        ]);
    }

    public function test_nao_permite_desconto_maior_que_subtotal_da_venda(): void
    {
        $produto = Produto::query()->create([
            'nome' => 'Bateria 60Ah',
            'quantidade' => 3,
            'preco_unitario' => 100.00,
        ]);

        $response = $this->from(route('painel.index'))->post(route('vendas.store'), [
            'produto_id' => $produto->id,
            'quantidade' => 2,
            'data_venda' => now()->toDateString(),
            'desconto' => 250,
        ]);

        $response->assertRedirect(route('painel.index'));
        $response->assertSessionHasErrors('desconto');

        $this->assertDatabaseCount('vendas', 0);
        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'quantidade' => 3,
        ]);
    }
}
