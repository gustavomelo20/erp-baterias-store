<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfiguracaoEmpresaController;
use App\Http\Controllers\EmpresaCadastroController;
use App\Http\Controllers\EmpresaUsuarioController;
use App\Http\Controllers\EstoqueVendaController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/cadastro-empresa', [EmpresaCadastroController::class, 'create'])->name('empresa.create');
Route::post('/cadastro-empresa', [EmpresaCadastroController::class, 'store'])->name('empresa.store');

Route::middleware(['auth', 'tenant.context'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/lojas/trocar', [AuthController::class, 'switchLoja'])->name('lojas.switch');
    Route::get('/', [EstoqueVendaController::class, 'index'])->name('welcome');
    Route::get('/painel', [EstoqueVendaController::class, 'dashboard'])->name('painel.index');
    Route::get('/dashboard', [EstoqueVendaController::class, 'dashboard'])->name('dashboard');
    Route::get('/estoque', [EstoqueVendaController::class, 'estoque'])->name('estoque.index');
    Route::get('/configuracoes', [ConfiguracaoEmpresaController::class, 'index'])->name('configuracoes.index');
    Route::put('/configuracoes/empresa', [ConfiguracaoEmpresaController::class, 'update'])->name('configuracoes.empresa.update');
    Route::put('/configuracoes/seguranca-loja', [ConfiguracaoEmpresaController::class, 'updateSenhaTrocaLoja'])->name('configuracoes.seguranca-loja.update');
    Route::get('/usuarios/novo', [EmpresaUsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [EmpresaUsuarioController::class, 'store'])->name('usuarios.store');
    Route::post('/produtos', [EstoqueVendaController::class, 'storeProduto'])->name('produtos.store');
    Route::put('/produtos/{produto}/preco-venda', [EstoqueVendaController::class, 'updatePrecoVenda'])->name('produtos.preco-venda.update');
    Route::post('/estoque/entrada', [EstoqueVendaController::class, 'storeEntradaEstoque'])->name('estoque.entrada');
    Route::post('/vendas', [EstoqueVendaController::class, 'storeVenda'])->name('vendas.store');
    Route::post('/vendas/checkout', [EstoqueVendaController::class, 'checkoutVenda'])->name('vendas.checkout');
    Route::get('/vendas/pagamento', [EstoqueVendaController::class, 'showPagamento'])->name('vendas.pagamento');
    Route::post('/vendas/confirmar', [EstoqueVendaController::class, 'confirmarVenda'])->name('vendas.confirmar');
    Route::get('/vendas/recibo', [EstoqueVendaController::class, 'showRecibo'])->name('vendas.recibo');
});
