<?php

namespace App\Modules\EmpresaCadastro\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\EmpresaCadastro\DTOs\EmpresaCadastroDTO;
use App\Modules\EmpresaCadastro\Http\Requests\StoreEmpresaCadastroRequest;
use App\Modules\EmpresaCadastro\UseCases\CriarEmpresaUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmpresaCadastroController extends Controller
{
    public function __construct(
        private readonly CriarEmpresaUseCase $useCase,
    ) {}

    public function create(): View
    {
        return view('auth.register_empresa');
    }

    public function store(StoreEmpresaCadastroRequest $request): RedirectResponse
    {
        $dto = EmpresaCadastroDTO::fromArray($request->validated());

        [, $loja] = $this->useCase->execute($dto);

        $request->session()->regenerate();
        $request->session()->put('loja_id', $loja->id);

        return redirect()->route('painel.index')->with('success', 'Conta da empresa criada com sucesso.');
    }
}
