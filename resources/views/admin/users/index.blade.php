@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <a href="{{ route('user.new') }}" class="btn btn-primary btn-sm">ADD</a>

    <table class="table" id="customers-table">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">#</th>
          </tr>
        </thead>
        <tbody>
            @forelse ($list as $user)
            <tr>
                <td>{{ $user->first_name }}</td>
                <td style="width=10px;">
                  <div class="btn-group" role="group">
                      <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Ação
                      </button>
                      <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" href="{{ route('user.edit', $user->id) }}">
                          <i class="fas fa-edit text-primary"></i> Editar
                        </a>
                        <a class="dropdown-item" href="{{ route('user.remove', $user->id) }}" onclick="return deleteSite('{{ $user->name }}');">
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
  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js" type="text/javascript"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <script>
      $(document).ready(function() {
        $('#customers-table').DataTable();
      });

      function deleteSite(title) {
          if (!confirm(`Tem certeza que deseja remover o usuário ${title}?`))
              event.preventDefault();
      }
  </script>
@stop