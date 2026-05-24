<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Loja;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class EmpresaCadastroController extends Controller
{
    public function create(): View
    {
        return view('auth.register_empresa');
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'empresa_nome' => ['required', 'string', 'max:255'],
            'loja_nome' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        [$user, $loja] = DB::transaction(function () use ($dados): array {
            $empresa = Empresa::query()->create([
                'nome' => $dados['empresa_nome'],
            ]);

            $loja = Loja::query()->create([
                'empresa_id' => $empresa->id,
                'nome' => $dados['loja_nome'],
            ]);

            $user = User::query()->create([
                'empresa_id' => $empresa->id,
                'name' => $dados['name'],
                'email' => $dados['email'],
                'password' => Hash::make($dados['password']),
            ]);

            $user->lojas()->attach($loja->id);

            return [$user, $loja];
        });

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('loja_id', $loja->id);

        return redirect()->route('painel.index')->with('success', 'Conta da empresa criada com sucesso.');
    }
}
