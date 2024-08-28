@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <a href="{{ route('author.create') }}" class="btn btn-primary btn-sm">ADD</a>

        <table class="table" id="customers-table">
            <thead>
                <tr>
                    <th scope="col">Autor</th>
                    <th scope="col">#</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($authors as $author)
                    <tr>
                        <td>{{ $author->first_name }}</td>
                        <td style="width=10px;">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Ação
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                  <a class="dropdown-item" href="{{ route('author.edit', $author->id) }}">
                                    <i class="fas fa-edit text-primary"></i> Editar
                                  </a>
                                  <a class="dropdown-item" href="{{ route('author.delete', $author->id) }}" onclick="return deleteSite('{{ $author->name }}');" >
                                    <i class="fas fa-trash text-danger"></i> Apagar
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
            if (!confirm(`Atenção! ao remover o autor, todos os seus relacionamento com os livros serão apagados. Tem certeza que deseja remover o autor ${title}?`))
                event.preventDefault();
        }
    </script>
@stop