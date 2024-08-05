{{-- <div class="text-center">
    <img src="{{ @$tenant->logo ? getFileLink($tenant->logo) : asset('images/no-image.png') }}"
        alt="{{ $tenant->name }}" style="max-width: 90px;" />
</div> --}}

<div class="mb-3">
    <label for="title" class="form-label">Nome da Editora</label>
    <input type="text" class="form-control form-control-sm" id="name" name="name"
        value="{{ @$editora ? $editora->name : '' }}">
</div>

<div class="form-group mt-2">
    <label>* Logo da editora:</label>
    <input type="file" name="logo" class="form-control form-control-sm" {{ @$editora ? '' : 'required' }}>
</div>

<div class="form-group">
    <label for="status">Selecione o status</label>
    <select class="form-control" id="status" name="status" required>
        <option disabled selected>Selecione uma opção</option>
        <option value="1" {{ @$editora->status == 1 ? 'selected' : '' }}>Ativo</option>
        <option value="0" {{ @$editora->status == 0 ? 'selected' : '' }}>Inativo</option>
    </select>
</div>

<div class="text-center">
    <button type="submit" class="btn btn-success btn-sm">Salvar</button>
</div>