@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{ $action }}" method="post" enctype="multipart/form-data">
            @csrf
            @include('admin.events-schedule-gallery._partials.form')
        </form>
    </div>
@stop