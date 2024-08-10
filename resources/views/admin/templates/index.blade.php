@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <a href="{{ route('templates.new') }}" class="btn btn-primary btn-sm">ADD</a>

    <table class="table">
        <thead>
          <tr>
            <th scope="col">Nome</th>
            <th scope="col">#</th>
          </tr>
        </thead>
        <tbody>
            @forelse ($list as $template)
            <tr>
                <td>{{ $template->name }}</td>
                <td style="width=10px;">
                  <div class="btn-group" role="group">
                      <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Ação
                      </button>
                      <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" href="{{ route('templates.edit', $template->id) }}">
                          <i class="fas fa-edit text-primary"></i> Editar
                        </a>
                        <a class="dropdown-item" href="{{ route('templates.show', $template->id) }}" >
                          <i class="far fa-eye text-info"></i> Visualizar
                        </a>
                        <a class="dropdown-item" href="{{ route('template.pages', $template->url)}}" >
                          <i class="fas fa-newspaper text-success"></i> Páginas do template
                        </a>
                      </div>
                  </div>
              </td>
            </tr>
            @empty
                nothing to list
            @endforelse
        </tbody>
      </table>
</div>
@stop