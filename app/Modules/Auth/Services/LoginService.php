<?php

namespace App\Modules\Auth\Services;

use App\Models\Loja;
use App\Models\User;
use App\Modules\Auth\DTOs\LoginDTO;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    /**
     * Tenta autenticar o usuário.
     *
     * @return array{success: true, loja: Loja}
     *       | array{success: false, error: string}
     */
    public function tentarLogin(LoginDTO $dto): array
    {
        if (!Auth::attempt(['email' => $dto->email, 'password' => $dto->password])) {
            return ['success' => false, 'error' => 'Email ou senha incorretos.'];
        }

        /** @var User $user */
        $user = Auth::user();

        if (!$user || !$user->empresa_id) {
            Auth::logout();
            return ['success' => false, 'error' => 'Seu usuário não possui empresa vinculada.'];
        }

        $loja = $user->lojas()
            ->where('empresa_id', $user->empresa_id)
            ->orderBy('nome')
            ->first();

        if (!$loja) {
            Auth::logout();
            return ['success' => false, 'error' => 'Seu usuário não possui acesso a nenhuma loja.'];
        }

        return ['success' => true, 'loja' => $loja];
    }
}
