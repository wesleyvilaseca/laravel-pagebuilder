@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <a href="{{ route('pages.create', $event->id) }}" class="btn btn-primary btn-sm">ADD</a>

        <table class="table" id="customers-table">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Action</th>
                    <th>#</th>
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
                            <a href="{{ route('pages.edit', [$event->id, $page->id]) }}"
                                class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('pages.delete', [$event->id, $page->id]) }}"
                                onclick="return deleteSite('{{ $page->name }}');" 
                                class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>

                        <td>
                            <a href="{{ route('pagebuilder.build', $page->id) }}?event={{ $event->url }}&page={{ $page->route }}"
                                class="btn btn-sm btn-success">
                                <i class="fas fa-newspaper"></i>
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