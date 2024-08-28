@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{ route('book.authors.available', $book->id) }}" method="POST">
            @if (!$authors->isEmpty())
                @csrf
                <div>
                    <button type="submit" class="btn btn-sm btn-success">Vincular autor(es|as)</button>
                </div>
            @endif
            <table class="table" id="customers-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Autor(a)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($authors as $author)
                    <tr>
                        <td><input type="checkbox" name="authors[]" value="{{ $author->id }}"></td>
                        <td>
                            {{ $author->first_name }}
                        </td>
                    </tr>
                    @empty
                        nothing to list
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script>
        $(document).ready(function() {
            $('#customers-table').DataTable({
                "paging": false,
                "info": false,
                "searching": true
            });
        });
    </script>
@stop