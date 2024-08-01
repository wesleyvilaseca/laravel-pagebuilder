@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{ $action }}" method="post">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nome da página</label>
                <input type="text" class="form-control form-control-sm" id="name" name="name"
                    value="{{ @$page ? $page->name : '' }}">
            </div>

            <div class="form-group">
                <label for="status">É a página principal?</label>
                <select class="form-control form-control-sm" id="homepage" name="homepage" required>
                    <option disabled selected>Selecione uma opção</option>
                    <option value="0" {{ (!@$page ? '' : $page->homepage == '0') ? 'selected' : '' }}>Não
                    </option>
                    <option value="1" {{ (!@$page ? '' : $page->homepage == '1') ? 'selected' : '' }}>Sim
                    </option>
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success btn-sm">Salvar</button>
            </div>

            <input type="hidden" name="layout" value="master">
        </form>
    </div>
@stop