<?php

namespace App\Modules\ConfiguracaoEmpresa\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ConfiguracaoEmpresa\DTOs\UpdateEmpresaDTO;
use App\Modules\ConfiguracaoEmpresa\DTOs\UpdateSenhaTrocaLojaDTO;
use App\Modules\ConfiguracaoEmpresa\Http\Requests\UpdateEmpresaRequest;
use App\Modules\ConfiguracaoEmpresa\Http\Requests\UpdateSenhaTrocaLojaRequest;
use App\Modules\ConfiguracaoEmpresa\UseCases\AtualizarEmpresaUseCase;
use App\Modules\ConfiguracaoEmpresa\UseCases\AtualizarSenhaTrocaLojaUseCase;
use App\Modules\ConfiguracaoEmpresa\UseCases\BuscarEmpresaUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfiguracaoEmpresaController extends Controller
{
    public function __construct(
        private readonly BuscarEmpresaUseCase            $buscarEmpresa,
        private readonly AtualizarEmpresaUseCase         $atualizarEmpresa,
        private readonly AtualizarSenhaTrocaLojaUseCase  $atualizarSenha,
    ) {}

    public function index(Request $request): View
    {
        $empresaId = (int) $request->user()->empresa_id;

        return view('configuracoes', [
            'empresa' => $this->buscarEmpresa->execute($empresaId),
        ]);
    }

    public function update(UpdateEmpresaRequest $request): RedirectResponse
    {
        $empresaId = (int) $request->user()->empresa_id;

        $this->atualizarEmpresa->execute(
            $empresaId,
            UpdateEmpresaDTO::fromArray($request->validated()),
        );

        return back()->with('success', 'Dados da empresa atualizados para preparação de NF-e.');
    }

    public function updateSenhaTrocaLoja(UpdateSenhaTrocaLojaRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $definida = $this->atualizarSenha->execute(
            $user,
            UpdateSenhaTrocaLojaDTO::fromArray($request->validated()),
        );

        $mensagem = $definida
            ? 'Senha de segurança para troca de loja atualizada.'
            : 'Senha de segurança para troca de loja removida.';

        return back()->with('success', $mensagem);
    }
}
