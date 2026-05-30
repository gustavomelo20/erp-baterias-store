<?php

namespace App\Modules\Venda\UseCases;

use App\Models\Empresa;
use App\Models\Loja;

class ShowReciboUseCase
{
    public function execute(array $recibo, int $empresaId, int $lojaId): array
    {
        return [
            'recibo'  => $recibo,
            'empresa' => Empresa::find($empresaId),
            'loja'    => Loja::find($lojaId),
        ];
    }
}
