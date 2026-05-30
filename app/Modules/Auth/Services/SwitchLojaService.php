<?php

namespace App\Modules\Auth\Services;

use App\Models\Loja;
use App\Models\User;
use App\Modules\Auth\DTOs\SwitchLojaDTO;
use Illuminate\Support\Facades\Hash;

class SwitchLojaService
{
    /**
     * Valida senha de segurança e retorna a loja se autorizado.
     *
     * @return array{success: true, loja: Loja}
     *       | array{success: false, field: string, error: string}
     */
    public function trocar(User $user, SwitchLojaDTO $dto): array
    {
        if ($user->troca_loja_senha && !Hash::check((string) ($dto->senhaLoja ?? ''), $user->troca_loja_senha)) {
            return [
                'success' => false,
                'field'   => 'senha_loja',
                'error'   => 'Senha de segurança inválida para trocar de loja.',
            ];
        }

        $loja = $user->lojas()
            ->where('empresa_id', $user->empresa_id)
            ->where('lojas.id', $dto->lojaId)
            ->first();

        if (!$loja) {
            return [
                'success' => false,
                'field'   => 'loja_id',
                'error'   => 'Loja inválida para este usuário.',
            ];
        }

        return ['success' => true, 'loja' => $loja];
    }
}
