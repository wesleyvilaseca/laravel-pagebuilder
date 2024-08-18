@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <a href="{{ $action }}" class="btn btn-primary btn-sm">ADD</a>

    <table class="table">
        <thead>
          <tr>
            <th scope="col">Nome</th>
            <th scope="col">#</th>
          </tr>
        </thead>
        <tbody>
            @forelse ($list as $permission)
            <tr>
                <td>{{ $permission->name }}</td>
                <td style="width=10px;">
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Ação
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                          <a class="dropdown-item" href="{{ route('permission.edit', $permission->id) }}">
                            <i class="fas fa-edit text-primary"></i> Editar
                          </a>
                          <a class="dropdown-item" href="{{ route('permission.remove', $permission->id) }}" onclick="return deleteSite('{{ $permission->name }}');">
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
                if (!confirm(`Tem certeza que deseja remover a permissão ${title}?`))
                    event.preventDefault();
            }
        </script>
    @stop