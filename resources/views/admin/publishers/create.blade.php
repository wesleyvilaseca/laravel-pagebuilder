@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form-publisher-component />
    </div>
@stop

@section('js')
    <script>
        jQuery(function($) {
            $('#zip_code').mask("00000-000");
        });
    </script>
@stop