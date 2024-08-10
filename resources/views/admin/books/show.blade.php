@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{ $action }}" method="POST" id="booksForm">
            @csrf
            @method('DELETE')
            @include('admin.books._partials.form')
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('booksForm');
            var elements = form.elements;
            
            for (var i = 0; i < elements.length; i++) {
                if (elements[i].id == 'bookButtonSubmit' || elements[i].name == '_token' || elements[i].name == '_method') {
                    continue;
                }
    
                elements[i].disabled = true;
            }
        });
    </script>
@stop