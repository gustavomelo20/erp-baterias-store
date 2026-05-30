<div class="modal-body p-4">
    <div class="row g-3">
        <div class="col-12 col-md-6">
            <label class="bp-label"><i class="bi bi-upc me-1 text-primary opacity-75"></i>CNPJ</label>
            <input name="cnpj" type="text" value="{{ old('cnpj') }}" required
                placeholder="00.000.000/0000-00" class="form-control bp-control" maxlength="18">
        </div>
        <div class="col-12 col-md-6">
            <label class="bp-label"><i class="bi bi-building me-1 text-primary opacity-75"></i>Razão Social</label>
            <input name="nome" type="text" value="{{ old('nome') }}" required
                placeholder="Nome da empresa" class="form-control bp-control" maxlength="255">
        </div>
        <div class="col-12 col-md-4">
            <label class="bp-label"><i class="bi bi-card-text me-1 text-primary opacity-75"></i>Inscrição Estadual</label>
            <input name="ie" type="text" value="{{ old('ie') }}"
                placeholder="IE" class="form-control bp-control" maxlength="30">
        </div>
        <div class="col-12 col-md-6">
            <label class="bp-label"><i class="bi bi-signpost me-1 text-primary opacity-75"></i>Logradouro</label>
            <input name="logradouro" type="text" value="{{ old('logradouro') }}"
                placeholder="Rua / Av." class="form-control bp-control" maxlength="255">
        </div>
        <div class="col-12 col-md-2">
            <label class="bp-label">Número</label>
            <input name="numero" type="text" value="{{ old('numero') }}"
                placeholder="Nro" class="form-control bp-control" maxlength="20">
        </div>
        <div class="col-12 col-md-4">
            <label class="bp-label">Bairro</label>
            <input name="bairro" type="text" value="{{ old('bairro') }}"
                placeholder="Bairro" class="form-control bp-control" maxlength="100">
        </div>
        <div class="col-12 col-md-4">
            <label class="bp-label"><i class="bi bi-geo-alt me-1 text-primary opacity-75"></i>Município</label>
            <input name="municipio" type="text" value="{{ old('municipio') }}"
                placeholder="Cidade" class="form-control bp-control" maxlength="100">
        </div>
        <div class="col-6 col-md-2">
            <label class="bp-label">UF</label>
            <input name="uf" type="text" value="{{ old('uf') }}"
                placeholder="SP" class="form-control bp-control" maxlength="2" style="text-transform:uppercase;">
        </div>
        <div class="col-6 col-md-2">
            <label class="bp-label">CEP</label>
            <input name="cep" type="text" value="{{ old('cep') }}"
                placeholder="00000-000" class="form-control bp-control" maxlength="9">
        </div>
    </div>
</div>
