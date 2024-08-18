@extends('layouts.admin')
@section('content')
<div class="container-fluid">
  <a href="{{ route('role.new') }}" class="btn btn-primary btn-sm">ADD</a>

    <table class="table">
        <thead>
          <tr>
            <th scope="col">Nome</th>
            <th scope="col">#</th>
          </tr>
        </thead>
        <tbody>
            @forelse ($list as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td style="width=10px;">
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Ação
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                          <a class="dropdown-item" href="{{ route('role.edit', $role->id) }}">
                            <i class="fas fa-edit text-primary"></i> Editar
                          </a>
                          <a class="dropdown-item" href="{{ route('role.permissions', $role->id) }}">
                            <i class="fas fa-newspaper text-info"></i> Permissões do modulo
                          </a>
                          <a class="dropdown-item" href="{{ route('role.remove', $role->id) }}" onclick="return deleteSite('{{ $role->name }}');">
                            <i class="fas fa-trash text-danger"></i> Remover
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

@section('js')
        <script>
            function deleteSite(title) {
                if (!confirm(`Tem certeza que deseja remover o modulo ${title}?`))
                    event.preventDefault();
            }
        </script>
    @stop