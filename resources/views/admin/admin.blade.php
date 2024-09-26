@extends('layouts.admin')
@section('content')
    <div class="top-section">
        <a href="{{ route('cache.clear') }}" class="btn btn-sm btn-success"><i class="fas fa-cog"></i> Limpar cache</a>
    </div>
@stop