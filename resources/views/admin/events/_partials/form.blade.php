<style>
    select.readonly {
        background-color: #e9ecef;
        color: #6c757d;
        pointer-events: none;
        cursor: not-allowed;
    }
</style>

<div class="mb-3">
    <label for="name" class="form-label">Nome do evento</label>
    <input type="text" class="form-control form-control-sm" id="name" name="name"
        value="{{ @$event ? $event->name : '' }}">
</div>

<div class="mb-3">
    <label for="description" class="form-label">Descrição do evento</label>
    <input type="text" class="editor form-control form-control-sm" id="description" name="description"
        value="{{ @$event ? $event->description : '' }}">
</div>

<div class="form-group">
    <label for="status">Este evento é o principal?</label>
    <select class="form-control form-control-sm" id="principal" name="principal" {{ @$event->principal ? 'disabled' : ''}}>
        <option disabled selected>Selecione uma opção</option>
        <option value="0" {{ @$event->principal == 0 ? 'selected' : '' }}>Não
        </option>
        <option value="1" {{ @$event->principal == 1 ? 'selected' : '' }}>Sim
        </option>
    </select>
</div>

<div class="form-group">
    <label for="status">Selecione o status</label>
    <select class="form-control" id="status" name="status" required>
        <option disabled selected>Selecione uma opção</option>
        <option value="1" {{ @$event->status == 1 ? 'selected' : '' }}>Ativo</option>
        <option value="0" {{ @$event->status == 0 ? 'selected' : '' }}>Inativo</option>
    </select>
</div>

@if (!@$event && !empty($templates))
    <div class="form-group" onchange="selectTemplate()">
        <label for="select_template">Usar um template para criar o evento?</label>
        <select class="form-control" id="select_template" name="select_template">
            <option disabled selected>Selecione uma opção</option>
            <option value="1">Sim</option>
            <option value="0">Não</option>
        </select>
    </div>                
@endif

<div class="form-group" id="themeId"  style="{{ @$event || empty($templates)? 'display:block' : 'display:none' }}">
    <label for="theme_id">Selecione um tema para criar o site do evento</label>
    <select class="form-control" id="theme_id" name="theme_id">
        <option disabled selected>Selecione uma opção</option>
        @foreach ($themes as $theme)
                <option value="{{ $theme->id }}" {{ @$event->theme_id == $theme->id ? 'selected' : '' }}>{{ $theme->name }}</option>
            @endforeach
    </select>
</div>

@if (!@$event)
    <div class="form-group" id="templateId" style="display: none">
        <label for="template_id">Selecione um template para criar o site do evento</label>
        <select class="form-control" id="template_id" name="template_id">
            <option disabled selected>Selecione uma opção</option>
            @foreach ($templates as $template)
                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                @endforeach
        </select>
    </div>                
@endif

<hr>

<section>
    <div class="mb-2">
        <h5>Informações de endereço do evento</h5>
    </div>
    <div class="row">
        <div class="form-group mt-2 col-md-4">
            <label>CEP:</label>
            <input type="text" id="zip_code" name="zip_code"
                class="form-control form-control-sm" placeholder="CEP:" onblur="buscacep(value)"
                value="{{ @$event?->data?->address?->zip_code ?? old('zip_code') }}" />
        </div>
    
        <div class="form-group mt-2 col-md-5">
            <label>Cidade:</label>
            <input type="text" id="city" name="city"
                class="form-control form-control-sm" placeholder="Cidade:"
                value="{{ @$event?->data?->address?->city ?? old('city') }}" readonly  />
        </div>
    
        <div class="form-group mt-2 col-md-3">
            <label>UF:</label>
            <input type="text" id="state" name="state"
                class="form-control form-control-sm" placeholder="UF:"
                value="{{ @$event?->data?->address?->state ?? old('state') }}" readonly  />
        </div>
    
        <div class="form-group mt-2 col-md-4">
            <label>Bairro:</label>
            <input type="text" id="district" name="district"
                class="form-control form-control-sm" placeholder="Bairro:"
                value="{{ @$event?->data?->address?->district ?? old('district') }}" readonly />
        </div>
    
        <div class="form-group mt-2 col-md-5">
            <label>Endereço:</label>
            <input type="text" id="address" name="address"
                class="form-control form-control-sm" placeholder="Endereço:"
                value="{{ @$event?->data?->address?->address ?? old('address') }}" />
        </div>
    
        <div class="form-group mt-2 col-md-3">
            <label>Numero:</label>
            <input type="text" name="number" class="form-control form-control-sm"
                placeholder="Numero:" value="{{ @$event?->data?->address?->number ?? old('number') }}">
        </div>
    </div>
</section>
<hr>
<section>
    <div class="mb-2">
        <h5>Coordenadas de geolocalização do evento</h5>
    </div>

   <div class="row">
        <div class="form-group mt-2 col-md-4">
            <label>Latitude:</label>
            <input type="text" id="latitude" name="latitude"
                class="form-control form-control-sm" placeholder="41.40338:"
                value="{{ @$event?->data?->address?->latitude ?? old('latitude') }}" />
        </div>

        <div class="form-group mt-2 col-md-4">
            <label>Longitude:</label>
            <input type="text" id="longitude" name="longitude"
                class="form-control form-control-sm" placeholder="2.17403"
                value="{{ @$event?->data?->address?->longitude ?? old('longitude') }}" />
        </div>
   </div>

</section>

<div class="text-center">
    <button type="submit" class="btn btn-success btn-sm">Salvar</button>
</div>

<input type="hidden" name="layout" value="master">

@section('js')
    <script>
        const USE_TEMPLATE = 1;
        const NOT_USE_TEMPLATE = 0;

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

        function selectTemplate() {
            if($('#select_template').val() == USE_TEMPLATE) {
                $('#templateId').fadeIn();
                $('#themeId').fadeOut();
            }

            if($('#select_template').val() == NOT_USE_TEMPLATE) {
                $('#templateId').fadeOut();
                $('#themeId').fadeIn();
            }
        }

        var plugin_tiny = "";
        var plugin_filemanager = "";
        var external_filemanager_path_server = "";
        var templates = "";
        tinymce.init({
            content_css: 'https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css',
            relative_urls: false,
            remove_script_host: false,
            selector: ".editor",
            height: 300,
            plugins: [
                "code print preview importcss tinydrive searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern noneditable help charmap emoticons responsivefilemanager"
            ],
            toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect| template",
            toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview | code",
            image_advtab: false,
            external_filemanager_path: '',
            filemanager_title: "Responsive Filemanager",
            external_plugins: {
                "responsivefilemanager": '',
                "filemanager": ''
            },
            templates: ''
        });
    </script>
@stop