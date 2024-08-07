
@php
    use Illuminate\Support\Str;
@endphp
<div class="mb-3">
    <label for="title" class="form-label">Nome da editora *</label>
    <input type="text" class="form-control form-control-sm" id="name" name="name"
        value="{{ @$publisher->name ?? '' }}">
</div>

<div class="form-group mb-3">
    <label for="description">Descrição da editora *</label>
    <textarea class="form-control" id="description" rows="3" name="description">
        {{ @$publisher->description ?? '' }}
    </textarea>
</div>

<div class="card mb-3">
    <div class="row card-body">
        <div class="col-2 text-center">
            <img src="{{ @$logo->server_file ?  asset('storage/' . $logo->server_file) : asset('assets/images/no-image.jpg')}}" alt="{{ @$publisher->name ?? ''  }}" style="max-width: 90px;" />
           @if (@$logo->server_file)
                <div class="mt-1">
                    <small>{{ Str::limit($logo->original_file, 10, '...') }}</small>
                </div>
           @endif
        </div>
        <div class="col-9">
            <div class="input-group">
                <label>* Logo da editora:</labe>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="logo" name="logo" {{ @$logo->server_file ? '' : 'required' }}> 
                  <label class="custom-file-label" for="logo">Selecione o arquivo</label>
                </div>
              </div>
        </div>
    </div>    
</div>

<div class="card mb-3">
    <div class="row card-body">
        <div class="col-2 text-center">
            <img src="{{ @$price_list->server_file ? asset('assets/images/file-check.webp') : asset('assets/images/no-file.png')}}" alt="{{ @$publisher->name ?? '' }}" style="max-width: 90px;" />
            @if (@$price_list->original_file)
                <div class="mt-1">
                    <small>{{ Str::limit($price_list->original_file, 10, '...') }}</small>
                </div>
            @endif
        </div>
        <div class="col-9">
            <div class="input-group">
                <label>Lista de preços da editora:</labe>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="price_list" name="price_list"> 
                  <label class="custom-file-label" for="price_list">Selecione o arquivo</label>
                </div>
              </div>
        </div>
    </div>    
</div>

<hr>

<section>
    <div class="mb-2">
        <h5>Informações de endereço</h5>
    </div>
    <div class="row">
        <div class="form-group mt-2 col-md-4">
            <label>CEP:</label>
            <input type="text" id="zip_code" name="zip_code"
                class="form-control form-control-sm" placeholder="CEP:" onblur="buscacep(value)"
                value="{{ @$publisher?->data?->address?->zip_code ?? old('zip_code') }}" />
        </div>
    
        <div class="form-group mt-2 col-md-5">
            <label>Cidade:</label>
            <input type="text" id="city" name="city"
                class="form-control form-control-sm" placeholder="Cidade:"
                value="{{ @$publisher?->data?->address?->city ?? old('city') }}" readonly  />
        </div>
    
        <div class="form-group mt-2 col-md-2">
            <label>UF:</label>
            <input type="text" id="state" name="state"
                class="form-control form-control-sm" placeholder="UF:"
                value="{{ @$publisher?->data?->address?->state ?? old('state') }}" readonly  />
        </div>
    
        <div class="form-group mt-2 col-md-4">
            <label>Bairro:</label>
            <input type="text" id="district" name="district"
                class="form-control form-control-sm" placeholder="Bairro:"
                value="{{ @$publisher?->data?->address?->district ?? old('district') }}" readonly />
        </div>
    
        <div class="form-group mt-2 col-md-5">
            <label>Endereço:</label>
            <input type="text" id="address" name="address"
                class="form-control form-control-sm" placeholder="Endereço:"
                value="{{ @$publisher?->data?->address?->address ?? old('address') }}" />
        </div>
    
        <div class="form-group mt-2 col-md-2">
            <label>Numero:</label>
            <input type="text" name="number" class="form-control form-control-sm"
                placeholder="Numero:" value="{{ @$publisher?->data?->address?->number ?? old('number') }}">
        </div>
    </div>
</section>

<hr>

<section>
    <div class="mb-2">
        <h5>Redes sociais</h5>
    </div>
    <div class="row">
        <div class="form-group mt-2 col-md-4">
            <label>Facebook:</label>
            <input type="url" name="facebook" class="form-control form-control-sm"
                placeholder="https://facebook.com/meu-facebook"
                value="{{ @$publisher?->data?->social?->facebook ?? old('facebook') }}">
        </div>
    
        <div class="form-group mt-2 col-md-4">
            <label>Instagram:</label>
            <input type="url" name="instagram" class="form-control form-control-sm"
                placeholder="https://instagram.com/meu-instagram"
                value="{{ @$publisher?->data?->social?->instagram ?? old('instagram') }}">
        </div>
    
        <div class="form-group mt-2 col-md-4">
            <label>Youtube:</label>
            <input type="url" name="youtube" class="form-control form-control-sm"
                placeholder="https://youtube.com/meu-youtube"
                value="{{ @$publisher?->data?->social?->youtube ?? old('youtube') }}">
        </div>
    </div>
</section>

<div class="mb-3">
    <label for="title" class="form-label">Site da editora *</label>
    <input type="url" class="form-control form-control-sm" id="site" name="site"
        value="{{ @$publisher->site ?? '' }}">
</div>

<div class="mb-3">
    <label for="title" class="form-label">Email da editora</label>
    <input type="email" class="form-control form-control-sm" id="email" name="email"
        value="{{ @$publisher->email ?? '' }}">
</div>

<div class="form-group">
    <label for="status">Selecione o status</label>
    <select class="form-control" id="status" name="status" required>
        <option disabled selected>Selecione uma opção</option>
        <option value="1" {{ @$publisher->status == 1 ? 'selected' : '' }}>Ativo</option>
        <option value="0" {{ @$publisher->status == 0 ? 'selected' : '' }}>Inativo</option>
    </select>
</div>

<div class="text-center">
    <button type="submit" class="btn btn-success btn-sm">Salvar</button>
</div>

<style>
    .custom-file-input ~ .custom-file-label::after {
        content: "Selecionar";
    }
</style>

@section('js')
    <script>
        jQuery(function($) {
            $('#zip_code').mask("00000-000");
        });

        function buscacep(cep) {
            if (cep.length == 9) {
                $.ajax({
                    url: `https://viacep.com.br/ws/${cep}/json/`,
                    type: 'GET',
                    dataType: 'json',
                    async: false,
                    data: null,
                    success: function(data) {
                        if (data.erro) {
                            alert('CEP não localizado!');
                        } else {
                            $("#address").val(data.logradouro);
                            $("#state").val(data.uf);
                            $("#city").val(data.localidade);
                            $("#district").val(data.bairro);
                        }
                    }
                });
            }
        }
    </script>
@stop