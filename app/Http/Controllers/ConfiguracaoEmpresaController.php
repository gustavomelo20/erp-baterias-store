<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ConfiguracaoEmpresaController extends Controller
{
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $empresa = Empresa::query()->findOrFail($user->empresa_id);

        return view('configuracoes', [
            'empresa' => $empresa,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $empresa = Empresa::query()->findOrFail($user->empresa_id);

        $dados = $request->validate([
            'razao_social' => ['required', 'string', 'max:255'],
            'nome_fantasia' => ['nullable', 'string', 'max:255'],
            'cnpj' => [
                'required',
                'string',
                'max:18',
                Rule::unique('empresas', 'cnpj')->ignore($empresa->id),
            ],
            'inscricao_estadual' => ['nullable', 'string', 'max:30'],
            'inscricao_municipal' => ['nullable', 'string', 'max:30'],
            'regime_tributario' => ['required', 'in:simples,presumido,real'],
            'cnae_principal' => ['nullable', 'string', 'max:20'],
            'email_fiscal' => ['nullable', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'cep' => ['nullable', 'string', 'max:10'],
            'logradouro' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:20'],
            'complemento' => ['nullable', 'string', 'max:120'],
            'bairro' => ['nullable', 'string', 'max:120'],
            'cidade' => ['nullable', 'string', 'max:120'],
            'uf' => ['nullable', 'string', 'size:2'],
            'codigo_municipio_ibge' => ['nullable', 'string', 'max:10'],
        ]);

        $dados['cnpj'] = preg_replace('/\D+/', '', $dados['cnpj'] ?? '') ?: null;
        $dados['telefone'] = preg_replace('/\D+/', '', $dados['telefone'] ?? '') ?: null;
        $dados['cep'] = preg_replace('/\D+/', '', $dados['cep'] ?? '') ?: null;
        $dados['uf'] = isset($dados['uf']) ? strtoupper((string) $dados['uf']) : null;

        $empresa->update([
            'nome' => $dados['nome_fantasia'] ?: $dados['razao_social'],
            ...$dados,
        ]);

        return back()->with('success', 'Dados da empresa atualizados para preparacao de NF-e.');
    }

    public function updateSenhaTrocaLoja(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $dados = $request->validate([
            'senha_troca_loja' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $senha = $dados['senha_troca_loja'] ?? null;

        if (!$senha) {
            $user->troca_loja_senha = null;
            $user->save();

            return back()->with('success', 'Senha de seguranca para troca de loja removida.');
        }

        $user->troca_loja_senha = Hash::make($senha);
        $user->save();

        return back()->with('success', 'Senha de seguranca para troca de loja atualizada.');
    }
}
