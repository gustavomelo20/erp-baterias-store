<?php

namespace App\Http\Controllers;

use App\Models\Loja;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmpresaUsuarioController extends Controller
{
    public function create(Request $request): View
    {
        /** @var User $usuarioAtual */
        $usuarioAtual = $request->user();

        $lojas = Loja::query()
            ->where('empresa_id', $usuarioAtual->empresa_id)
            ->orderBy('nome')
            ->get();

        return view('usuarios.create', [
            'lojas' => $lojas,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $usuarioAtual */
        $usuarioAtual = $request->user();

        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'lojas' => ['required', 'array', 'min:1'],
            'lojas.*' => [
                'integer',
                Rule::exists('lojas', 'id')->where(fn ($query) => $query->where('empresa_id', $usuarioAtual->empresa_id)),
            ],
        ]);

        $novoUsuario = User::query()->create([
            'empresa_id' => $usuarioAtual->empresa_id,
            'name' => $dados['name'],
            'email' => $dados['email'],
            'password' => Hash::make($dados['password']),
        ]);

        $novoUsuario->lojas()->sync($dados['lojas']);

        return redirect()->route('configuracoes.index')->with('success', 'Usuario criado e vinculado a empresa com sucesso.');
    }
}
