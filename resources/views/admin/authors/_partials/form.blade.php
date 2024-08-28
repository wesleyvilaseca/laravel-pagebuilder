
@php
    use Illuminate\Support\Str;
@endphp
<div class="mb-3">
    <label for="title" class="form-label">Primeiro nome *</label>
    <input type="text" class="form-control form-control-sm" id="first_name" name="first_name"
        value="{{ @$author->first_name ?? '' }}">
</div>

<div class="mb-3">
    <label for="title" class="form-label">Sobre nome *</label>
    <input type="text" class="form-control form-control-sm" id="last_name" name="last_name"
        value="{{ @$author->last_name ?? '' }}">
</div>

<div class="mb-3">
    <label for="description" class="form-label">Descrição do evento</label>
    <input type="text" class="editor form-control form-control-sm" id="description" name="description"
        value="{{ @$author ? $author->description : '' }}">
</div>

<div class="form-group">
    <label for="status">Selecione o status</label>
    <select class="form-control" id="status" name="status" required>
        <option disabled selected>Selecione uma opção</option>
        <option value="1" {{ @$author->status == 1 ? 'selected' : '' }}>Ativo</option>
        <option value="0" {{ @$author->status == 0 ? 'selected' : '' }}>Inativo</option>
    </select>
</div>

<div class="text-center">
    <button type="submit" class="btn btn-success btn-sm">Salvar</button>
</div>

@section('js')
    <script>
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