@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Name</label>
                <input type="text" class="form-control" id="title" name="name" value="{{ @$user ? $user->name : '' }}">
            </div>

            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Email</label>
                <input type="text" class="form-control" id="title" name="email" value="{{ @$user ? $user->email : '' }}">
            </div>
            <div class="form-group">
              <label for="exampleFormControlSelect1">Selecione o perfil</label>
              <select class="form-control" id="exampleFormControlSelect1" name="role_id" required>
                <option disabled selected>Select an option</option>
                @foreach ($roles_list as $role)
                        <option value="{{ $role->id }}" {{ $role_user == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
              </select>
            </div>

            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Password</label>
                <input type="password" class="form-control" id="title" name="password">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@stop
