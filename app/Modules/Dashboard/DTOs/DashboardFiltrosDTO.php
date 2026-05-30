<?php

namespace App\Modules\Dashboard\DTOs;

class DashboardFiltrosDTO
{
    public function __construct(
        public readonly int    $empresaId,
        public readonly int    $lojaId,
        public readonly string $dataInicio,
        public readonly string $dataFim,
    ) {}

    public static function fromArray(array $dados, int $empresaId, int $lojaId): self
    {
        return new self(
            empresaId:  $empresaId,
            lojaId:     $lojaId,
            dataInicio: $dados['data_inicio'] ?? now()->toDateString(),
            dataFim:    $dados['data_fim']    ?? now()->toDateString(),
        );
    }
}
