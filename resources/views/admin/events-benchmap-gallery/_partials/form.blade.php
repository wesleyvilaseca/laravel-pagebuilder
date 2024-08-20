<div class="card mb-3">
    <div class="row card-body">
        <div class="col-2 text-center">
            <img src="{{ @$image->server_file ?  asset('storage/' . $image->server_file) : asset('assets/images/no-image.jpg')}}" alt="{{ @$banner->name ?? ''  }}" style="max-width: 90px;" />
           @if (@$image->server_file)
                <div class="mt-1">
                    <small>{{ Str::limit($image->original_file, 10, '...') }}</small>
                </div>
           @endif
        </div>
        @if (!@$image)
            <div class="col-9">
                <div class="input-group">
                    <label>Foto do livro:</labe>
                    <div class="custom-file">
                    <input type="file" class="custom-file-input" id="image" name="image"> 
                    <label class="custom-file-label" for="image">Selecione o arquivo</label>
                    </div>
                </div>
            </div>
        @endif
    </div>    
</div>

<div class="mb-3">
    <label for="name" class="form-label">Nome do banner</label>
    <input type="text" class="form-control form-control-sm" id="name" name="name"
        value="{{ @$banner->name ?? old('name') }}">
</div>


<div class="mb-3">
    <label for="description" class="form-label">Descrição do banner</label>
    <input type="text" class="form-control form-control-sm" id="description" name="description"
        value="{{ @$banner->description ?? old('description') }}">
</div>

<div class="mb-3">
    <label for="name" class="form-label">Ordem</label>
    <input type="number" class="form-control form-control-sm" id="order" name="order"
        value="{{ @$banner->order ?? old('order') }}" min="0" step="1">
</div>

<div class="mb-3">
    <label for="link" class="form-label">Link</label>
    <input type="url" class="form-control form-control-sm" id="link" name="link"
        value="{{ @$banner->link ?? old('link') }}">
</div>

<div class="text-center">
    <button type="submit" class="btn btn-{{ @$show ? 'danger' : 'success' }} btn-sm" id="bookButtonSubmit">{{ @$show ? 'APAGAR MAPA DE BANCADA ' . @$banner->name . ' ?' : 'Salvar'}}</button>
</div>