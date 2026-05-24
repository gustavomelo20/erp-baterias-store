<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nome'])]
class Empresa extends Model
{
    use HasFactory;

    /**
     * @return HasMany<Loja, $this>
     */
    public function lojas(): HasMany
    {
        return $this->hasMany(Loja::class);
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
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
