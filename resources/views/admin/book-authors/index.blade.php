@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <a href="{{ route('book.authors.available', $book->id) }}" class="btn btn-primary btn-sm">Adicionar autor(a)</a>

        <table class="table" id="customers-table">
            <thead>
                <tr>
                    <th scope="col">Autor(a)</th>
                    <th scope="col">#</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($authors as $author)
                    <tr>
                        <td>{{ $author->first_name }}</td>
                        <td style="width=10px;">
                            <div class="btn-group" role="group">
                                <form action="{{ route('book.authors.detach', [$book->id, $author->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Desvincular</button>
                                </form>
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