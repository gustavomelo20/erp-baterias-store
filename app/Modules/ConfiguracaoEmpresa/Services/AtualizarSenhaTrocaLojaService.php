<?php

namespace App\Modules\ConfiguracaoEmpresa\Services;

use App\Models\User;
use App\Modules\ConfiguracaoEmpresa\DTOs\UpdateSenhaTrocaLojaDTO;
use Illuminate\Support\Facades\Hash;

class AtualizarSenhaTrocaLojaService
{
    public function atualizar(User $user, UpdateSenhaTrocaLojaDTO $dto): bool
    {
        if (!$dto->senha) {
            $user->troca_loja_senha = null;
            $user->save();

            return false; // senha removida
        }

        $user->troca_loja_senha = Hash::make($dto->senha);
        $user->save();

        return true; // senha definida
    }
}
