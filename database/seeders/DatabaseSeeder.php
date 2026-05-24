<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Loja;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $empresa = Empresa::query()->firstOrCreate([
            'nome' => 'Empresa Principal',
        ]);

        $loja = Loja::query()->firstOrCreate([
            'empresa_id' => $empresa->id,
            'nome' => 'Loja Matriz',
        ]);

        $user = User::query()->firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'empresa_id' => $empresa->id,
            'name' => 'Administrador',
            'password' => bcrypt('password'),
        ]);

        if ((int) $user->empresa_id !== (int) $empresa->id) {
            $user->update([
                'empresa_id' => $empresa->id,
            ]);
        }

        $user->lojas()->syncWithoutDetaching([$loja->id]);

        $this->call(ProdutoSeeder::class);
    }
}
