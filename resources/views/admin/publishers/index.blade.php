@extends('layouts.admin')
@section('content')
    <style>
        /* Adicione estilos conforme necessário */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        img {
            max-width: 60px; /* Ajuste o tamanho da imagem conforme necessário */
            height: auto;
        }
    </style>

    <div class="container-fluid">
        <a href="{{ route('publisher.create') }}" class="btn btn-primary btn-sm mb-3">ADD</a>


        <div class="card mb-3">
            <div class="card-header">
                <form action="{{ route('publishers.search') }}" method="POST" class="form-search">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="filter" placeholder="Editora" class="form-control"
                                value="{{ $filters['filter'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-dark">Pesquisar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <table class="table" id="customers-table">
            <thead>
                <tr>
                    <th scope="col">Editora</th>
                    <th scope="col">Logo</th>
                    <th scope="col">#</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($publishers as $publisher)
                    <tr>
                        <td>{{ $publisher->name }}</td>
                        <td> 
                            @php
                                $logo = $publisher->uploads()->wherePivot('alias_category', 'publisher-logo')->first();    
                            @endphp
                            <img src="{{ asset('storage/' . $logo['server_file'])}}" alt="Descrição da Imagem">
                        </td>

                        <td style="width=10px;">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Ação
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                  <a class="dropdown-item" href="{{ route('publisher.edit', $publisher->id) }}">
                                    <i class="fas fa-edit text-primary"></i> Editar
                                  </a>
                                  <a class="dropdown-item" href="{{ route('publisher.delete', $publisher->id) }}" onclick="return deleteSite('{{ $publisher->name }}');" >
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

        <div class="pt-2">
            @if (isset($filters))
            {!! $publishers->appends($filters)->links('pagination::bootstrap-4') !!}
            @else
                {!! $publishers->links('pagination::bootstrap-4') !!}
            @endif
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script>
        $(document).ready(function() {
            $('#customers-table').DataTable({
                paging: false,
                searching: false,
                ordering: false,
                info: false
            });
        });

        function deleteSite(title) {
            if (!confirm(`Atenção! ao remover a editora, todos os arquivos vinculados a ela e os seus relacionamento com os livros serão apagados. Tem certeza que deseja remover a editora ${title}?`))
                event.preventDefault();
        }
    </script>
@stop