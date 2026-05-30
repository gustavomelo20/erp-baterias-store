<?php

namespace App\Modules\EmpresaCadastro\Services;

use App\Models\Empresa;
use App\Models\Loja;
use App\Models\User;
use App\Modules\EmpresaCadastro\DTOs\EmpresaCadastroDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmpresaCadastroService
{
    /**
     * Cria empresa, loja e usuário administrador em uma única transação.
     *
     * @return array{0: User, 1: Loja}
     */
    public function criar(EmpresaCadastroDTO $dto): array
    {
        return DB::transaction(function () use ($dto): array {
            $empresa = Empresa::query()->create([
                'nome' => $dto->empresaNome,
            ]);

            $loja = Loja::query()->create([
                'empresa_id' => $empresa->id,
                'nome'       => $dto->lojaNome,
            ]);

            $user = User::query()->create([
                'empresa_id' => $empresa->id,
                'name'       => $dto->name,
                'email'      => $dto->email,
                'password'   => Hash::make($dto->password),
            ]);

            $user->lojas()->attach($loja->id);

            return [$user, $loja];
        });
    }
}
