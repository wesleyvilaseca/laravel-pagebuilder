@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <a href="{{ route('template.pages.create', $template->id) }}" class="btn btn-primary btn-sm">ADD</a>

        <table class="table" id="customers-table">
            <thead>
                <tr>
                    <th scope="col">Pagina</th>
                    <th scope="col">Action</th>
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
                        <td>
                            <a href="#" class="btn btn-sm btn-info">
                              <i class="fas fa-edit"></i>
                            </a>

                            <a href="{{ route('template.pagebuilder.build', $page->id) }}?template={{ $template->url }}&page={{ $page->route }}" class="btn btn-sm btn-warning">
                              <i class="fas fa-grip-vertical"></i>
                            </a>

                            <a href="#"
                                onclick="return deleteSite('{{ $page->name }}');" 
                                class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </a>
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