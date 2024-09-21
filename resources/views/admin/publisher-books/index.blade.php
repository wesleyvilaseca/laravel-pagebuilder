@extends('layouts.admin')
@section('content')
    <style>
        .top-section {
            display: flex;
            justify-content: space-between;
        }

        /* Adicione estilos conforme necessário */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        img {
            max-width: 60px; /* Ajuste o tamanho da imagem conforme necessário */
            height: auto;
        }

        .custom-file-input ~ .custom-file-label::after {
            content: "Selecionar";
        }
    </style>

    <div class="mb-3 top-section">
        <div>
            <a href="{{ route('publisher.book.create', $publisher->url)  }}" class="btn btn-primary btn-sm">ADD</a>
            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#exampleModal">Cadastro de livros em lote</button>
        </div>

        @if (sizeof($books) > 0)
            <div class="trash-btn">
                <form action="{{ route('publisher.books.delete', $publisher->url) }}" method="POST" id="publisherbook">
                    @csrf
                    @method('DELETE')
                    <button id="delete-btn" class="btn btn-sm btn-danger" disabled onclick="return deleteBooks();">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        @endif
    </div>

    <div class="container-fluid">
        <table class="table" id="customers-table">
            <thead>
                <tr>
                    @if (sizeof($books) > 0)
                        <th scope="col" class="text-center">
                            <input id="select-all" type="checkbox">
                        </th>
                    @endif
                    <th scope="col">Livro</th>
                    <th scope="col">Autor</th>
                    <th scope="col">#</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="book-checkbox" name="books[]" value="{{ $book->id }}">
                        </td>
                        <td>{{ $book->name }}</td>
                        <td>{{ $book->author }}</td>
                        <td style="width=10px;">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Ação
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                  <a class="dropdown-item" href="{{ route('publisher.book.edit', [$publisher->url, $book->id]) }}">
                                    <i class="fas fa-edit text-primary"></i> Editar
                                  </a>
                                  <a class="dropdown-item" href="{{ route('publisher.book.show', [$publisher->url, $book->id]) }}">
                                    <i class="fas fa-eye text-info"></i> Visualisar
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Cadastro de livro em lote</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('publisher.books.batch', $publisher->url) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="input-group">
                        <label>Arquivo CSV</labe>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="csv_book_file" name="csv_book_file"> 
                            <label class="custom-file-label" for="csv_book_file">Selecione o arquivo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-sm btn-primary" id="submit-btn" disabled>Cadastrar livros</button>
                </div>
            </form>
          </div>
        </div>
      </div>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script>
        document.getElementById('csv_book_file').addEventListener('change', function(event) {
            const fileName = event.target.files[0] ? event.target.files[0].name : 'Selecione o arquivo';
            const label = event.target.nextElementSibling;
            label.textContent = fileName;

            document.getElementById('submit-btn').disabled = !event.target.files.length;
        });

        $('#exampleModal').on('hidden.bs.modal', function () {
            const fileInput = document.getElementById('csv_book_file');
            const label = fileInput.nextElementSibling;

            fileInput.value = '';
            label.textContent = 'Selecione o arquivo';
            document.getElementById('submit-btn').disabled = true;
        });

        $(document).ready(function() {
            $('#customers-table').DataTable({
                "pageLength": 100,
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 800, -1],
                    [10, 25, 50, 100, 500, 800,  "Todos"]
                ],
                paging: true,
                searching: true,
                ordering: false,
                info: false
            });
        });

        $('.book-checkbox').on('change', function() {
            toggleDeleteButton();
        });

        $('#select-all').on('click', function() {
            $('.book-checkbox').prop('checked', this.checked);
            toggleDeleteButton();
        });

        function toggleDeleteButton() {
            if ($('.book-checkbox:checked').length > 0) {
                $('#delete-btn').prop('disabled', false);
            } else {
                $('#delete-btn').prop('disabled', true);
            }
        }

        function deleteBooks() {
            if (!confirm(`Atenção! Ao confirmar, os livros e os seus relacionamento serão apagados. deseja prosseguir com a ação?`)) {
                event.preventDefault();
                return false;
            }

            const form = document.getElementById('publisherbook');
            form.querySelectorAll('input[name="books[]"]').forEach(input => input.remove());

            document.querySelectorAll('.book-checkbox:checked').forEach(function(checkbox) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'books[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });
            return true;
        }
    </script>
@stop