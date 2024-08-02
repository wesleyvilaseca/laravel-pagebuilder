@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{ $action }}" method="post">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nome do evento</label>
                <input type="text" class="form-control form-control-sm" id="name" name="name"
                    value="{{ @$event ? $event->name : '' }}">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição do evento</label>
                <input type="text" class="form-control form-control-sm" id="description" name="description"
                    value="{{ @$event ? $event->description : '' }}">
            </div>

            <div class="form-group">
                <label for="status">Este evento é o principal?</label>
                <select class="form-control form-control-sm {{ @$event->principal ? 'readonly' : ''}}" id="principal" name="principal">
                    <option disabled selected>Selecione uma opção</option>
                    <option value="0" {{ @$event->principal == '0' ? 'selected' : '' }}>Não
                    </option>
                    <option value="1" {{ @$event->principal == '1' ? 'selected' : '' }}>Sim
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Selecione o status</label>
                <select class="form-control {{ @$event->principal ? 'readonly' : ''}}" id="status" name="status">
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

            <div class="text-center">
                <button type="submit" class="btn btn-success btn-sm">Salvar</button>
            </div>

            <input type="hidden" name="layout" value="master">
        </form>
    </div>

<style>
    select.readonly {
        background-color: #e9ecef;
        color: #6c757d;
        pointer-events: none;
        cursor: not-allowed;
    }
</style>
@stop

@section('js')
    <script>
        const USE_TEMPLATE = 1;
        const NOT_USE_TEMPLATE = 0;

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

    </script>
@stop