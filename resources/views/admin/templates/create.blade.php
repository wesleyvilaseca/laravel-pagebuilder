@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ @$selectedTheme ? $selectedTheme->name : '' }}">
            </div>

            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Descrição do template</label>
                <input type="text" class="form-control" id="description" name="description" value="{{ @$selectedTheme ? $user->description : '' }}">
            </div>
            <div class="form-group">
              <label for="theme_id">Selecione o thema</label>
              <select class="form-control" id="theme_id" name="theme_id" required>
                <option disabled selected>Selecione uma opção</option>
                @foreach ($themes as $theme)
                        <option value="{{ $theme->id }}" {{ isset($selectedTheme) && $selectedTheme->id == $theme->id ? 'selected' : '' }}>{{ $theme->name }}</option>
                    @endforeach
              </select>
            </div>

            <div class="form-group">
                <label for="status_id">Selecione o status</label>
                <select class="form-control" id="status_id" name="status" required>
                    <option disabled selected>Selecione uma opção</option>
                    <option value="1" {{ isset($selectedTheme) && $selectedTheme->status == 1 ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ isset($selectedTheme) && $selectedTheme->status == 0 ? 'selected' : '' }}>Inativo</option>
                </select>
              </div>

            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
@stop
