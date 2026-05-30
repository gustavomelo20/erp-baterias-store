<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\DTOs\LoginDTO;
use App\Modules\Auth\DTOs\SwitchLojaDTO;
use App\Modules\Auth\Http\Requests\LoginRequest;
use App\Modules\Auth\Http\Requests\SwitchLojaRequest;
use App\Modules\Auth\UseCases\LoginUseCase;
use App\Modules\Auth\UseCases\SwitchLojaUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private readonly LoginUseCase      $loginUseCase,
        private readonly SwitchLojaUseCase $switchLojaUseCase,
    ) {}

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $resultado = $this->loginUseCase->execute(
            LoginDTO::fromArray($request->validated())
        );

        if (!$resultado['success']) {
            return back()->withErrors(['email' => $resultado['error']])->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->put('loja_id', $resultado['loja']->id);

        return redirect()->intended(route('painel.index'));
    }

    public function switchLoja(SwitchLojaRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $resultado = $this->switchLojaUseCase->execute(
            $user,
            SwitchLojaDTO::fromArray($request->validated())
        );

        if (!$resultado['success']) {
            return back()->withErrors([$resultado['field'] => $resultado['error']]);
        }

        $request->session()->put('loja_id', (int) $resultado['loja']->id);

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
