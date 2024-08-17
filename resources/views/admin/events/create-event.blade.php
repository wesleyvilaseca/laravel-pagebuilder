@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{ $action }}" method="post">
            @csrf
            @include('admin.events._partials.form')
        </form>
    </div>
@stop

