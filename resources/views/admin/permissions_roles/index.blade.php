@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <form action="{{ $action }}" method="POST">
            @csrf
            <div align="right">
                <button type="submit" class="btn btn-primary btn-sm">Update Permissions</button>
            </div>
            @foreach ($permissions as $permission)
                <div class="form-group">

                    <input class="form-check-input" type="checkbox" value="{{ $permission->id }}" id="flexCheckDefault"
                        name="permission[]"
                        @foreach ($permissions_role as $haspermission) {{ $permission->id == $haspermission->permission_id ? 'checked' : '' }} @endforeach>
                    <label class="form-check-label" for="flexCheckDefault">
                        {{ $permission->name }}
                    </label>
                </div>
            @endforeach
        </form>
    </div>
@stop