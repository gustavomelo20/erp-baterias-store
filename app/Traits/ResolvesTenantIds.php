<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ResolvesTenantIds
{
    /**
     * @return array{0: int, 1: int}
     */
    private function tenantIds(Request $request): array
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return [
            (int) $user->empresa_id,
            (int) $request->session()->get('loja_id'),
        ];
    }
}
