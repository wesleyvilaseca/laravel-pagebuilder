@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <a href="{{ route('user.new') }}" class="btn btn-primary btn-sm">ADD</a>

    <table class="table">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
            @forelse ($list as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>
                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="{{ route('user.remove', $user->id) }}" class="btn btn-sm btn-danger">Remove</a>
                </td>
              </tr>
            @empty
                nothing to list
            @endforelse
        </tbody>
      </table>
</div>
@stop