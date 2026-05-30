<?php

namespace App\Modules\Estoque\Services;

use App\Models\Fornecedor;
use App\Models\Produto;
use App\Models\SkuDePara;
use App\Modules\Estoque\DTOs\EntradaEstoqueDTO;
use Illuminate\Http\UploadedFile;

class IngestaoXmlService
{
    public function __construct(
        private readonly EntradaEstoqueService $entradaEstoqueService,
    ) {}

    /**
     * Fase 1: faz o parse do XML, auto-cadastra/atualiza o fornecedor e
     * identifica quais itens já têm de-para e quais precisam ser resolvidos.
     */
    public function parsear(UploadedFile $arquivo, int $empresaId, int $lojaId): array
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($arquivo->get(), 'SimpleXMLElement', LIBXML_NONET);

        if ($xml === false) {
            throw new \InvalidArgumentException('Arquivo XML inválido ou corrompido.');
        }

        $nfe    = $xml->NFe ?? $xml;
        $infNFe = $nfe->infNFe ?? null;

        if ($infNFe === null) {
            throw new \InvalidArgumentException('Estrutura de NF-e não encontrada no XML.');
        }

        $ide  = $infNFe->ide;
        $emit = $infNFe->emit;

        $cnpjEmitente = preg_replace('/\D/', '', (string) ($emit->CNPJ ?? ''));
        $nomeEmitente = trim((string) ($emit->xNome ?? ''));

        $fornecedor     = null;
        $fornecedorNovo = false;

        if ($cnpjEmitente !== '') {
            $existia = Fornecedor::query()
                ->where('empresa_id', $empresaId)
                ->where('cnpj', $cnpjEmitente)
                ->exists();

            $fornecedor = Fornecedor::updateOrCreate(
                ['empresa_id' => $empresaId, 'cnpj' => $cnpjEmitente],
                [
                    'nome'       => $nomeEmitente ?: 'Fornecedor importado',
                    'ie'         => (string) ($emit->IE ?? '') ?: null,
                    'logradouro' => (string) ($emit->enderEmit->xLgr ?? '') ?: null,
                    'numero'     => (string) ($emit->enderEmit->nro ?? '') ?: null,
                    'bairro'     => (string) ($emit->enderEmit->xBairro ?? '') ?: null,
                    'municipio'  => (string) ($emit->enderEmit->xMun ?? '') ?: null,
                    'uf'         => (string) ($emit->enderEmit->UF ?? '') ?: null,
                    'cep'        => preg_replace('/\D/', '', (string) ($emit->enderEmit->CEP ?? '')) ?: null,
                ]
            );

            $fornecedorNovo = !$existia;
        }

        $itens = [];

        foreach ($infNFe->det as $det) {
            $prod          = $det->prod;
            $skuFornecedor = trim((string) ($prod->cProd ?? ''));
            $nomeXml       = trim((string) ($prod->xProd ?? ''));
            $quantidade    = (float) ($prod->qCom ?? 0);
            $custoUnitario = (float) ($prod->vUnCom ?? 0);

            if ($skuFornecedor === '' || $quantidade <= 0) {
                continue;
            }

            $produtoId = null;

            if ($fornecedor !== null) {
                $produtoId = SkuDePara::query()
                    ->where('empresa_id', $empresaId)
                    ->where('loja_id', $lojaId)
                    ->where('fornecedor_id', $fornecedor->id)
                    ->where('sku_fornecedor', $skuFornecedor)
                    ->value('produto_id');
            }

            $itens[] = [
                'sku_fornecedor' => $skuFornecedor,
                'nome_xml'       => $nomeXml,
                'quantidade'     => $quantidade,
                'custo_unitario' => $custoUnitario,
                'produto_id'     => $produtoId,
                'sku_sugerido'   => $this->sugerirSku($skuFornecedor),
                'nome_sugerido'  => $nomeXml,
            ];
        }

        return [
            'fornecedor_id'   => $fornecedor?->id,
            'fornecedor_nome' => $nomeEmitente ?: ($fornecedor?->nome ?? ''),
            'fornecedor_cnpj' => $cnpjEmitente,
            'fornecedor_novo' => $fornecedorNovo,
            'nfe'             => [
                'serie'   => (string) ($ide->serie ?? ''),
                'numero'  => (string) ($ide->nNF ?? ''),
                'emissao' => (string) ($ide->dhEmi ?? ''),
            ],
            'itens' => $itens,
        ];
    }

    /**
     * Fase 2: processa as entradas de estoque para todos os itens que já têm
     * produto_id definido (mapeados + resolvidos pelo usuário).
     */
    public function processarPendente(array $pendente, int $empresaId, int $lojaId): array
    {
        $importados  = [];
        $naoMapeados = [];

        $produtoIds = array_values(array_filter(array_column($pendente['itens'], 'produto_id')));
        $produtos   = $produtoIds
            ? Produto::query()->whereIn('id', $produtoIds)->get()->keyBy('id')
            : collect();

        foreach ($pendente['itens'] as $item) {
            if (!$item['produto_id']) {
                $naoMapeados[] = ['sku' => $item['sku_fornecedor'], 'nome' => $item['nome_xml']];
                continue;
            }

            $dto = new EntradaEstoqueDTO(
                produtoId:  (int)   $item['produto_id'],
                quantidade: (int)   round($item['quantidade']),
                precoCusto: (float) $item['custo_unitario'],
                empresaId:  $empresaId,
                lojaId:     $lojaId,
            );

            $this->entradaEstoqueService->registrar($dto);

            $importados[] = [
                'sku'        => $item['sku_fornecedor'],
                'nome'       => $item['nome_xml'],
                'quantidade' => $item['quantidade'],
                'custo'      => $item['custo_unitario'],
                'produto'    => $produtos->get($item['produto_id'])?->nome ?? '—',
            ];
        }

        return [
            'fornecedor'   => $pendente['fornecedor_cnpj']
                ? ['cnpj' => $pendente['fornecedor_cnpj'], 'nome' => $pendente['fornecedor_nome']]
                : null,
            'nfe'          => $pendente['nfe'],
            'importados'   => $importados,
            'nao_mapeados' => $naoMapeados,
        ];
    }

    private function sugerirSku(string $skuFornecedor): string
    {
        $base = strtoupper(preg_replace('/[^A-Z0-9]/i', '-', $skuFornecedor));
        $base = preg_replace('/-+/', '-', trim($base, '-'));
        return 'BAT-' . substr($base, 0, 15);
    }
}

