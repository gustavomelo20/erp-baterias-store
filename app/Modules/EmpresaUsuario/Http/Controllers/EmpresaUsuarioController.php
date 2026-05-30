<?php

namespace App\Modules\EmpresaUsuario\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\EmpresaUsuario\DTOs\CriarUsuarioDTO;
use App\Modules\EmpresaUsuario\Http\Requests\StoreUsuarioRequest;
use App\Modules\EmpresaUsuario\UseCases\CriarUsuarioUseCase;
use App\Modules\EmpresaUsuario\UseCases\ListarLojasUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmpresaUsuarioController extends Controller
{
    public function __construct(
        private readonly ListarLojasUseCase $listarLojas,
        private readonly CriarUsuarioUseCase $criarUsuario,
    ) {}

    public function create(Request $request): View
    {
        $empresaId = (int) $request->user()->empresa_id;

        return view('usuarios.create', [
            'lojas' => $this->listarLojas->execute($empresaId),
        ]);
    }

    public function store(StoreUsuarioRequest $request): RedirectResponse
    {
        $empresaId = (int) $request->user()->empresa_id;

        $this->criarUsuario->execute(
            CriarUsuarioDTO::fromArray($request->validated(), $empresaId)
        );

        return redirect()->route('configuracoes.index')->with('success', 'Usuário criado e vinculado à empresa com sucesso.');
    }
}
