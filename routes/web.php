<?php

use App\Modules\Auth\Http\Controllers\AuthController;
use App\Modules\ConfiguracaoEmpresa\Http\Controllers\ConfiguracaoEmpresaController;
use App\Modules\Dashboard\Http\Controllers\DashboardController;
use App\Modules\EmpresaUsuario\Http\Controllers\EmpresaUsuarioController;
use App\Modules\EmpresaCadastro\Http\Controllers\EmpresaCadastroController;
use App\Modules\Estoque\Http\Controllers\EstoqueController;
use App\Modules\Fornecedor\Http\Controllers\FornecedorController;
use App\Modules\SkuDePara\Http\Controllers\SkuDeParaController;
use App\Modules\Venda\Http\Controllers\VendaController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/cadastro-empresa', [EmpresaCadastroController::class, 'create'])->name('empresa.create');
Route::post('/cadastro-empresa', [EmpresaCadastroController::class, 'store'])->name('empresa.store');

Route::middleware(['auth', 'tenant.context'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/lojas/trocar', [AuthController::class, 'switchLoja'])->name('lojas.switch');

    // Estoque
    Route::get('/', [EstoqueController::class, 'index'])->name('welcome');
    Route::get('/estoque', [EstoqueController::class, 'estoque'])->name('estoque.index');
    Route::post('/produtos', [EstoqueController::class, 'storeProduto'])->name('produtos.store');
    Route::put('/produtos/{produto}/preco-venda', [EstoqueController::class, 'updatePrecoVenda'])->name('produtos.preco-venda.update');
    Route::post('/estoque/entrada', [EstoqueController::class, 'storeEntradaEstoque'])->name('estoque.entrada');
    Route::post('/estoque/importar-xml', [EstoqueController::class, 'ingestaoXml'])->name('estoque.importar-xml');
    Route::get('/estoque/resolver-sku', [EstoqueController::class, 'resolverSku'])->name('estoque.resolver-sku');
    Route::post('/estoque/confirmar-resolucao', [EstoqueController::class, 'confirmarResolucao'])->name('estoque.confirmar-resolucao');

    // Fornecedores
    Route::get('/fornecedores', [FornecedorController::class, 'index'])->name('fornecedores.index');
    Route::post('/fornecedores', [FornecedorController::class, 'store'])->name('fornecedores.store');
    Route::put('/fornecedores/{fornecedor}', [FornecedorController::class, 'update'])->name('fornecedores.update');
    Route::delete('/fornecedores/{fornecedor}', [FornecedorController::class, 'destroy'])->name('fornecedores.destroy');

    // SKU De-Para
    Route::get('/sku-depara', [SkuDeParaController::class, 'index'])->name('sku-depara.index');
    Route::post('/sku-depara', [SkuDeParaController::class, 'store'])->name('sku-depara.store');
    Route::delete('/sku-depara/{sku_depara}', [SkuDeParaController::class, 'destroy'])->name('sku-depara.destroy');

    // Dashboard
    Route::get('/painel', [DashboardController::class, 'dashboard'])->name('painel.index');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Configurações & Usuários
    Route::get('/configuracoes', [ConfiguracaoEmpresaController::class, 'index'])->name('configuracoes.index');
    Route::put('/configuracoes/empresa', [ConfiguracaoEmpresaController::class, 'update'])->name('configuracoes.empresa.update');
    Route::put('/configuracoes/seguranca-loja', [ConfiguracaoEmpresaController::class, 'updateSenhaTrocaLoja'])->name('configuracoes.seguranca-loja.update');
    Route::get('/usuarios/novo', [EmpresaUsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [EmpresaUsuarioController::class, 'store'])->name('usuarios.store');

    // Vendas
    Route::post('/vendas', [VendaController::class, 'storeVenda'])->name('vendas.store');
    Route::post('/vendas/checkout', [VendaController::class, 'checkoutVenda'])->name('vendas.checkout');
    Route::get('/vendas/pagamento', [VendaController::class, 'showPagamento'])->name('vendas.pagamento');
    Route::post('/vendas/confirmar', [VendaController::class, 'confirmarVenda'])->name('vendas.confirmar');
    Route::get('/vendas/recibo', [VendaController::class, 'showRecibo'])->name('vendas.recibo');
    Route::get('/vendas/orcamento', [VendaController::class, 'showOrcamento'])->name('vendas.orcamento');
});
