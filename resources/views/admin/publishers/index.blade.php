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
        <a href="{{ route('publisher.create') }}" class="btn btn-primary btn-sm">ADD</a>

        <table class="table" id="customers-table">
            <thead>
                <tr>
                    <th scope="col">Editora</th>
                    <th scope="col">Logo</th>
                    <th scope="col">Action</th>
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
                        <td>
                            <a href="{{ route('publisher.edit', $publisher->id) }}" class="btn btn-sm btn-info">
                              <i class="fas fa-edit"></i>
                            </a>

                            <a href="{{ route('publisher.delete', $publisher->id) }}"
                                onclick="return deleteSite('{{ $publisher->name }}');" 
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
            if (!confirm(`Atenção! ao remover a editora, todos os arquivos vinculados a ela e os seus relacionamento com os livros serão apagados. Tem certeza que deseja remover a editora ${title}?`))
                event.preventDefault();
        }
    </script>
@stop