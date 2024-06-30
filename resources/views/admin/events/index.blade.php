@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <a href="#" class="btn btn-primary btn-sm">ADD</a>

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
                        <td>{{ env('APP_URL') . '/' . $event->name }}</td>
                        <td>{{ $event->url }}</td>
                        <td>
                            @switch(@$event->status)
                                @case(0)
                                    <span class="badge badge-secondary">Inativo</span>
                                @break

                                @case(1)
                                    <span class="badge badge-warning">Em manutenção</span>
                                @break

                                @case(2)
                                    <span class="badge badge-primary">Ativo</span>
                                @break

                                <span class="badge badge-primary">any</span>

                                @default
                            @endswitch
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning">Edit</a>

                            <a href="#"
                                onclick="return deleteSite('{{ $event->name }}');"
                                class="btn
                                btn-sm btn-danger">Remove</a>

                            <a href="{{ $event->site_url }}" class="btn btn-sm btn-success" target="_blanck"><i
                                    class="fas fa-eye"></i> Visualizar</a>
                        </td>

                        <td>
                            <a href="{{ route('event.pages', $event->url) }}" class="btn btn-sm btn-success">Gerenciar</a>
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
                if (!confirm(`Tem certeza que deseja remover o website ${title}?`))
                    event.preventDefault();
            }
        </script>
    @stop