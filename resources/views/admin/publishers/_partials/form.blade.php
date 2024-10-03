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
        </div>
        <div class="col-9">
            <div class="input-group">
                <label>* Logo da editora:</labe>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="logo" name="logo" {{ @$logo->server_file ? '' : 'required' }} accept=".jpg, .jpeg, .png" /> 
                  <label class="custom-file-label" for="logo">Selecione o arquivo</label>
                </div>
            </div>
        </div>
    </div>    
</div>

<div class="card mb-3">
    <div class="row card-body">
        <div class="col-2 text-center">
            <div class="price_list-container" style="position: relative; display: none;">
                <canvas
                    id="price_list-pdf-preview"
                    style="max-width: 90px;"
                ></canvas>
                <button class="close-preview" id="price_list-close-preview">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="price_list-empty-file" style="display: {{ @$price_list->server_file ? 'none' : 'block' }};">
                <img  src="{{ asset('assets/images/no-file.png') }}" style="max-width: 90px;" />
            </div>
        </div>
        <div class="col-9">
            <div class="input-group">
                <label>Lista de preços da editora:</labe>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="price_list" name="price_list" accept=".pdf"> 
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

    .close-preview {
    position: absolute; 
    top: -8px;
    right: -20px;
    background: rgba(255, 0, 0, 0.3);
    border: none;
    color: red;
    font-size: 16px;
    cursor: pointer;
    display: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s;
}

.close-preview:hover {
    background: rgba(255, 0, 0, 0.4);
}
</style>

@section('js')
    <script>
        const price_list = '{{ @$price_list->server_file ?  asset("storage/" . $price_list->server_file) : 0 }}';

        if (price_list != false) {
            renderPDF('price_list', price_list);
        }

        jQuery(function($) {
            $('#zip_code').mask("00000-000");
        });

       function deleteFile(inputFile) {
            console.log(inputFile);
        }

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

        document.addEventListener('DOMContentLoaded', function() {
            ['logo', 'price_list'].forEach(id => {
                document.getElementById(id).addEventListener('change', handleFileInputChange);
            });

            ['price_list-close-preview'].forEach(id => {
                document.getElementById(id).addEventListener('click', handleClosePreview);
            });
        })

    </script>
@stop