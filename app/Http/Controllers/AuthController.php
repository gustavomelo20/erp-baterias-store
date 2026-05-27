<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user || !$user->empresa_id) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Seu usuario nao possui empresa vinculada.',
                ])->onlyInput('email');
            }

            $lojaInicial = $user->lojas()
                ->where('empresa_id', $user->empresa_id)
                ->orderBy('nome')
                ->first();

            if (!$lojaInicial) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Seu usuario nao possui acesso a nenhuma loja.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            $request->session()->put('loja_id', $lojaInicial->id);

            return redirect()->intended(route('painel.index'));
        }

        return back()->withErrors([
            'email' => 'Email ou senha incorretos.',
        ])->onlyInput('email');
    }

    public function switchLoja(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $dados = $request->validate([
            'loja_id' => ['required', 'integer'],
            'senha_loja' => ['nullable', 'string'],
        ]);

        if ($user->troca_loja_senha && !Hash::check((string) ($dados['senha_loja'] ?? ''), $user->troca_loja_senha)) {
            return back()->withErrors([
                'senha_loja' => 'Senha de seguranca invalida para trocar de loja.',
            ]);
        }

        $loja = $user->lojas()
            ->where('empresa_id', $user->empresa_id)
            ->where('lojas.id', $dados['loja_id'])
            ->first();

        if (!$loja) {
            return back()->withErrors([
                'loja_id' => 'Loja invalida para este usuario.',
            ]);
        }

        $request->session()->put('loja_id', (int) $loja->id);

        return back()->with('success', 'Loja ativa alterada com sucesso.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
