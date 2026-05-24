<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\Loja;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstoqueVendasTest extends TestCase
{
    use RefreshDatabase;

    public function test_registra_venda_e_debita_estoque(): void
    {
        [$user, $empresa, $loja] = $this->tenantContext();

        $produto = Produto::query()->create([
            'empresa_id' => $empresa->id,
            'loja_id' => $loja->id,
            'nome' => 'Caneta Azul',
            'quantidade' => 10,
            'preco_unitario' => 2.50,
            'preco_custo' => 1.20,
        ]);

        $response = $this
            ->actingAs($user)
            ->withSession(['loja_id' => $loja->id])
            ->post(route('vendas.store'), [
                'produto_id' => $produto->id,
                'quantidade' => 3,
                'data_venda' => now()->toDateString(),
                'desconto' => 1.50,
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('vendas', [
            'empresa_id' => $empresa->id,
            'loja_id' => $loja->id,
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
        [$user, $empresa, $loja] = $this->tenantContext();

        $produto = Produto::query()->create([
            'empresa_id' => $empresa->id,
            'loja_id' => $loja->id,
            'nome' => 'Caderno',
            'quantidade' => 2,
            'preco_unitario' => 30.00,
            'preco_custo' => 20.00,
        ]);

        $response = $this
            ->actingAs($user)
            ->withSession(['loja_id' => $loja->id])
            ->from(route('painel.index'))
            ->post(route('vendas.store'), [
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
        [$user, $empresa, $loja] = $this->tenantContext();

        $produto = Produto::query()->create([
            'empresa_id' => $empresa->id,
            'loja_id' => $loja->id,
            'nome' => 'Bateria 60Ah',
            'quantidade' => 3,
            'preco_unitario' => 100.00,
            'preco_custo' => 80.00,
        ]);

        $response = $this
            ->actingAs($user)
            ->withSession(['loja_id' => $loja->id])
            ->from(route('painel.index'))
            ->post(route('vendas.store'), [
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

    /**
     * @return array{0:User,1:Empresa,2:Loja}
     */
    private function tenantContext(): array
    {
        $empresa = Empresa::query()->create([
            'nome' => 'Empresa Teste',
        ]);

        $loja = Loja::query()->create([
            'empresa_id' => $empresa->id,
            'nome' => 'Loja Teste',
        ]);

        $user = User::factory()->create([
            'empresa_id' => $empresa->id,
        ]);

        $user->lojas()->attach($loja->id);

        return [$user, $empresa, $loja];
    }
}
