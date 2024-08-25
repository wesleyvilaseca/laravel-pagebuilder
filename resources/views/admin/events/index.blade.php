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
                    <th scope="col">#</th>
                    {{-- <th>#</th> --}}
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
                        <td style="width=10px;">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Ação
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                  <a class="dropdown-item" href="{{ route('event.edit', $event->id)}}">
                                    <i class="fas fa-edit text-primary"></i> Editar
                                  </a>
                                  <a class="dropdown-item" href="{{ route('event.publishers', $event->id)}}">
                                    <i class="fas fa-book-reader text-dark"></i> Editoras do evento
                                  </a>
                                  <a class="dropdown-item" href="{{ route('event.banner.gallery', $event->id) }}">
                                    <i class="fas fa-images text-warning"></i> Banners do evento
                                  </a>
                                  <a class="dropdown-item" href="{{ route('event.benchmap.gallery', $event->id) }}">
                                    <i class="fas fa-images text-warning"></i> Mapa de bancadas do evento
                                  </a>
                                  <a class="dropdown-item" href="{{ route('event.attachments', $event->id) }}">
                                    <i class="fas fa-paperclip text-warning"></i> Anexos
                                  </a>
                                  <a class="dropdown-item" href="{{ route('event.pages', $event->url) }}">
                                    <i class="fas fa-newspaper text-info"></i> Páginas do evento
                                  </a>
                                  <a class="dropdown-item" href="{{ route('event.delete', $event->id) }}" onclick="return deleteSite('{{ $event->name }}');">
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
                if (!confirm(`Ao remover o evento, todas as páginas serão apagadas. Tem certeza que deseja remover o evento ${title}?`))
                    event.preventDefault();
            }
        </script>
    @stop