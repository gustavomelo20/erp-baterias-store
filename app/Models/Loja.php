<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['empresa_id', 'nome'])]
class Loja extends Model
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
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'loja_user')->withTimestamps();
    }

    /**
     * @return HasMany<Produto, $this>
     */
    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class);
    }

    /**
     * @return HasMany<Venda, $this>
     */
    public function vendas(): HasMany
    {
        return $this->hasMany(Venda::class);
    }
}
