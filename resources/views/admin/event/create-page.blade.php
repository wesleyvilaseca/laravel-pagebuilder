@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{ $action }}" method="post">
            @csrf
            @include('admin.event._partials.form')
        </form>
    </div>
@stop