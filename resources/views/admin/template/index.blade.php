@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <a href="{{ route('template.pages.create', $template->id) }}" class="btn btn-primary btn-sm">ADD</a>

        <table class="table" id="customers-table">
            <thead>
                <tr>
                    <th scope="col">Pagina</th>
                    <th scope="col">#</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pages as $page)
                    <tr>
                        <td>{{ $page->name }}
                            @if ($page->homepage == 1)
                                <i class="fas fa-star" style="color: #FFD700"></i>
                            @endif
                        </td>
                        <td style="width=10px;">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Ação
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                  <a class="dropdown-item" href="{{ route('template.pages.edit', [$template->id, $page->id])}}">
                                    <i class="fas fa-edit text-primary"></i> Editar
                                  </a>
                                  <a class="dropdown-item" href="{{ route('template.pages.destroy', [$template->id, $page->id]) }}" onclick="return deleteSite('{{ $page->name }}');" >
                                    <i class="fas fa-trash text-danger"></i> Apagar
                                  </a>
                                  <a class="dropdown-item" href="{{ route('template.pagebuilder.build', $page->id) }}?template={{ $template->url }}&page={{ $page->route }}" >
                                    <i class="fas fa-newspaper text-success"></i> Editar interface da Página
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
            if (!confirm(`Tem certeza que deseja remover a página ${title}?`))
                event.preventDefault();
        }
    </script>
@stop