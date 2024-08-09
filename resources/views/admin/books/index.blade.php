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
        <a href="{{ route('book.create') }}" class="btn btn-primary btn-sm">ADD</a>

        <table class="table" id="customers-table">
            <thead>
                <tr>
                    <th scope="col">Livro</th>
                    <th scope="col">Ação</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td>{{ $book->name }}</td>
                        <td>
                            <a href="{{ route('book.edit', $book->id) }}" class="btn btn-sm btn-info">
                              <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('book.show', $book->id) }}" class="btn btn-sm btn-info">
                            <i class="far fa-eye"></i>
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
    </script>
@stop