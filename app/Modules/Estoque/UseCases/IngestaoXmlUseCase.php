<?php

namespace App\Modules\Estoque\UseCases;

use App\Modules\Estoque\Services\IngestaoXmlService;
use Illuminate\Http\UploadedFile;

class IngestaoXmlUseCase
{
    public function __construct(
        private readonly IngestaoXmlService $service,
    ) {}

    public function parsear(UploadedFile $arquivo, int $empresaId, int $lojaId): array
    {
        return $this->service->parsear($arquivo, $empresaId, $lojaId);
    }

    public function processarPendente(array $pendente, int $empresaId, int $lojaId): array
    {
        return $this->service->processarPendente($pendente, $empresaId, $lojaId);
    }
}
