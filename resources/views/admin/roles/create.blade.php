@extends('layouts.admin')

@section('content')
<div class="container">
    <form action="{{ route('roles.store1') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Role Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Assign Permissions</label>
            <div class="form-check">
                @foreach($permissions as $permission)
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="form-check-input">
                    <label class="form-check-label">{{ $permission->name }}</label><br>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Create Role</button>
    </form>
</div>
@endsection
