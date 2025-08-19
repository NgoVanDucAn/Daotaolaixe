@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-body">
        <a href="{{ route('roles.create1') }}" 
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 1%; z-index: 99;">
                +
        </a>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Vai trò</th>
                        <th>Quyền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach($role->permissions as $permission)
                                    <span class="badge bg-success">{{ $permission->name }}</span>
                                @endforeach
                            </td>
                            <td style="width: 150px;">
                                <a href="{{ route('roles.edit1', $role->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <form action="{{ route('roles.destroy1', $role->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onsubmit="return confirm('Bạn có chắc chắn xoá vai trò này?')">Xoá</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
