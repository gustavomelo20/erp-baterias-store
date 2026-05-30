<?php

namespace App\Modules\Venda\UseCases;

use App\Modules\Venda\DTOs\CheckoutVendaDTO;

class CheckoutVendaUseCase
{
    /**
     * Valida que os itens decodificados não estão vazios e retorna os dados
     * prontos para serem salvos na sessão pelo controller.
     *
     * @return array{items: array, desconto: float}|null  null quando items é inválido
     */
    public function execute(CheckoutVendaDTO $dto): ?array
    {
        if (!is_array($dto->items) || empty($dto->items)) {
            return null;
        }

        return [
            'items'   => $dto->items,
            'desconto' => $dto->desconto,
        ];
    }
}
