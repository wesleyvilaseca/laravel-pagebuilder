<div class="card mb-3">
    <div class="row card-body">
        <div class="col-2 text-center">
            <img src="{{ @$attachmentFile->server_file ? asset('assets/images/file-check.webp') : asset('assets/images/no-file.png')}}" alt="{{ @$attachment->name ?? '' }}" style="max-width: 90px;" />
            @if (@$attachmentFile->original_file)
                <div class="mt-1">
                    <small>{{ Str::limit($attachmentFile->original_file, 10, '...') }}</small>
                </div>
            @endif
        </div>

        @if (!@$attachmentFile)
            <div class="col-9">
                <div class="input-group">
                    <label>Anexo:</labe>
                    <div class="custom-file">
                    <input type="file" class="custom-file-input" id="attachment" name="attachment"> 
                    <label class="custom-file-label" for="attachment">Selecione o arquivo</label>
                    </div>
                </div>
            </div>
        @endif
    </div>    
</div>

<div class="mb-3">
    <label for="name" class="form-label">Nome do anexo</label>
    <input type="text" class="form-control form-control-sm" id="name" name="name"
        value="{{ @$attachment->name ?? old('name') }}">
</div>


<div class="mb-3">
    <label for="description" class="form-label">Descrição do anexo</label>
    <input type="text" class="form-control form-control-sm" id="description" name="description"
        value="{{ @$attachment->description ?? old('description') }}">
</div>

<div class="mb-3">
    <label for="name" class="form-label">Ordem</label>
    <input type="number" class="form-control form-control-sm" id="order" name="order"
        value="{{ @$attachment->order ?? old('order') }}" min="0" step="1">
</div>

<div class="text-center">
    <button type="submit" class="btn btn-{{ @$show ? 'danger' : 'success' }} btn-sm" id="bookButtonSubmit">{{ @$show ? 'APAGAR ANEXO ' . @$attachment->name . ' ?' : 'Salvar'}}</button>
</div>