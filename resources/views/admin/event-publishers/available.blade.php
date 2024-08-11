@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{ route('event.publishers.available', $event->id) }}" method="POST">
            @if (!$publishers->isEmpty())
                @csrf
                <div>
                    <button type="submit" class="btn btn-sm btn-success">Vincular editora(s) selecionada(s)</button>
                </div>
            @endif
            <table class="table" id="customers-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Editora</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($publishers as $publisher)
                    <tr>
                        <td><input type="checkbox" name="publishers[]" value="{{ $publisher->id }}"></td>
                        <td>
                            {{ $publisher->name }}
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