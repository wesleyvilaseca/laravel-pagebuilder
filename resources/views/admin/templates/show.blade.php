@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <ul>
                <li>
                    <strong>Nome: </strong> {{ $template->name }}
                </li>
                <li>
                    <strong>Descrição: </strong> {{ $template->description }}
                </li>
            </ul>

            <form action="{{ route('templates.destroy', $template->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="text-center">
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                        DELETAR A TEMPLATE {{ $template->name }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop