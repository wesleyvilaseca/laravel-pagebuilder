@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <a href="{{ route('event.attachment.create', $event->id) }}" class="btn btn-primary btn-sm">Adicionar anexo</a>

        <table class="table" id="customers-table">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Ordem</th>
                    <th scope="col">#</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($gallery as $banner)
                    <tr>
                        <td>{{ $banner->name }}</td>
                        <td>{{ $banner->order }}</td>

                        <td style="width=10px;">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Ação
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                  <a class="dropdown-item" href="{{ route('event.attachment.edit', [$event->id, $banner->id]) }}">
                                    <i class="fas fa-edit text-primary"></i> Editar
                                  </a>
                                  <a class="dropdown-item" href="{{ route('event.attachment.show', [$event->id, $banner->id]) }}">
                                    <i class="far fa-eye text-info"></i> Visualizar
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
    </script>
@stop