<?php

namespace App\Modules\EmpresaUsuario\Services;

use App\Models\User;
use App\Modules\EmpresaUsuario\DTOs\CriarUsuarioDTO;
use Illuminate\Support\Facades\Hash;

class CriarUsuarioService
{
    public function criar(CriarUsuarioDTO $dto): User
    {
        $usuario = User::query()->create([
            'empresa_id' => $dto->empresaId,
            'name'       => $dto->name,
            'email'      => $dto->email,
            'password'   => Hash::make($dto->password),
        ]);

        $usuario->lojas()->sync($dto->lojas);

        return $usuario;
    }
}
