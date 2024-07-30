@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <a href="{{ route('templates.new') }}" class="btn btn-primary btn-sm">ADD</a>

    <table class="table">
        <thead>
          <tr>
            <th scope="col">Nome</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
            @forelse ($list as $template)
            <tr>
                <td>{{ $template->name }}</td>
                <td>
                    <a href="{{ route('templates.edit', $template->id) }}" class="btn btn-sm btn-warning">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('templates.show', $template->id) }}" class="btn btn-sm btn-info">
                      <i class="far fa-eye"></i>
                    </a>

                    <a href="#" class="btn btn-sm btn-success">
                      <i class="fas fa-newspaper"></i>
                    </a>
                </td>
              </tr>
            @empty
                nothing to list
            @endforelse
        </tbody>
      </table>
</div>
@stop