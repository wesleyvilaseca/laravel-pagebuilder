@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <form action="{{ $action }}" method="post">
        @csrf
        @method('PUT')
        @include('admin.permissions._partials.form')
      </form>
</div>
@stop