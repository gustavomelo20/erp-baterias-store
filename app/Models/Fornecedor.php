<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['empresa_id', 'cnpj', 'nome', 'ie', 'logradouro', 'numero', 'bairro', 'municipio', 'uf', 'cep'])]
class Fornecedor extends Model
{
    protected $table = 'fornecedores';

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function skuDepara(): HasMany
    {
        return $this->hasMany(SkuDePara::class);
    }
}
