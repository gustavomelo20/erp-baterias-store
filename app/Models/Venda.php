<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['empresa_id', 'loja_id', 'produto_id', 'quantidade', 'preco_unitario', 'desconto', 'total', 'data_venda'])]
class Venda extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo<Empresa, $this>
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * @return BelongsTo<Loja, $this>
     */
    public function loja(): BelongsTo
    {
        return $this->belongsTo(Loja::class);
    }

    /**
     * @return BelongsTo<Produto, $this>
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}
