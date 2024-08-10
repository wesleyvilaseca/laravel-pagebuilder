@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{ $action }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.books._partials.form')
        </form>
    </div>
@stop