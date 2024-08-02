@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <a href="{{ route('event.create') }}" class="btn btn-primary btn-sm">ADD</a>

        <table class="table" id="customers-table">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Url</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr>
                        <td>
                            {{ $event->name }}
                            @if ($event->principal == 1)
                                <i class="fas fa-star" style="color: #FFD700"></i>
                            @endif

                        </td>
                        <td>{{env('APP_URL') . '/' . $event->url }}</td>
                        <td>
                            @switch(@$event->status)
                                @case(0)
                                    <span class="badge badge-secondary">Inativo</span>
                                @break

                                @case(1)
                                    <span class="badge badge-warning">Evento ativo</span>
                                @break

                                @case(2)
                                    <span class="badge badge-primary">Ativo</span>
                                @break

                                <span class="badge badge-primary">any</span>

                                @default
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('event.edit', $event->id)}}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>

                            <a href="{{ route('event.delete', $event->id)}} "
                                onclick="return deleteSite('{{ $event->name }}');"
                                class="btn
                                btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </a>

                            <a href="{{ $event->site_url }}" class="btn btn-sm btn-success" target="_blanck">
                                <i class="far fa-eye"></i>
                            </a>
                        </td>

                        <td>
                            <a href="{{ route('event.pages', $event->url) }}" class="btn btn-sm btn-success">
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
                if (!confirm(`Ao remover o evento, todas as páginas serão apagadas. Tem certeza que deseja remover o evento ${title}?`))
                    event.preventDefault();
            }
        </script>
    @stop