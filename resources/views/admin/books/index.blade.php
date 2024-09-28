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

        table {
            font-size: 0.80rem;
            white-space: nowrap;
            display: block;
            overflow-x: auto;
        }

        .btn-dark {
            background-color: #000;
        }

        .btn-dark:hover {
            background-color: #343a40;
        }

        .break-word {
            max-width: 200px;
            word-wrap: break-word;
            white-space: normal;
        }

        @media (max-width: 768px) {
            table {
            font-size: 0.75rem;
            }
        }
    </style>

    <div class="container-fluid">
        <a href="{{ route('book.create') }}" class="btn btn-primary btn-sm">ADD</a>

        <div class="card mb-3 mt-3">
            <div class="card-header">
                <form action="{{ route('books.search') }}" method="POST" class="form-search">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2">
                          <input class="form-control" id="search-input" type="text" placeholder="Buscar..." name="filter" value="{{ $filters['filter'] ?? '' }}"/>
                        </div>
                        <div class="col-md-2 mb-2">
                          <select class="form-control" name="selected_filter">
                            <option value="name" {{ ($filters['selected_filter'] ?? null) == 'name' ? 'selected' : '' }}>Livro</option>
                            <option value="subject" {{ ($filters['selected_filter'] ?? null) == 'subject' ? 'selected' : '' }}>Assunto</option>
                            <option value="publisher" {{ ($filters['selected_filter'] ?? null) == 'publisher' ? 'selected' : '' }}>Editora</option>
                            <option value="isbn" {{ ($filters['selected_filter'] ?? null) == 'isbn' ? 'selected' : '' }}>ISBN</option>
                            <option value="author" {{ ($filters['selected_filter'] ?? null) == 'author' ? 'selected' : '' }}>Autor</option>
                          </select>
                        </div>
                        <div class="col-md-2 col-sm-12 mb-2">
                            <button type="submit" class="btn btn-dark btn-block">Buscar</button>
                        </div>
                      </div>
                    </div>
                </form>
            </div>
        </div>

        <table class="table" id="customers-table" style="overflow-x: auto;">
            <thead>
                <tr>
                    <th scope="col">ISBN</th>
                    <th scope="col">Titulo</th>
                    <th> Autor(es) </th>
                    <th> Preço de Capa </th>
                    <th> Preço com Desconto </th>
                    <th> Assunto </th>
                    <th> Editora </th>
                    <th> Link </th>
                    <th scope="col">#</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td>{{ $book->isbn }}</td>
                        <td class="break-word">{{ $book->name }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->price }}</td>
                        <td>{{ $book->price_discount }}</td>
                        <td>{{ $book->subject }}</td>
                        <td>{{ $book->publishers[0]->name }}</td>
                        <td><a href="{{ $book->link }}">Link</a></td>
                        <td style="width=10px;">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Ação
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                  <a class="dropdown-item" href="{{ route('book.edit', $book->id) }}">
                                    <i class="fas fa-edit text-primary"></i> Editar
                                  </a>
                                  <a class="dropdown-item" href="{{ route('book.show', $book->id) }}">
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

        <div class="pt-2">
            @if (isset($filters))
                {!! $books->appends($filters)->links('pagination::bootstrap-4') !!}
            @else
                {!! $books->links('pagination::bootstrap-4') !!}
            @endif
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<script>
    document.getElementById('search-input').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            this.form.submit();
        }
    });
</script>
@stop