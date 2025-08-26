@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('users.index') }}" class="row g-2 mb-3">
            <div class="col">
                <label class="form-label fw-bold">Tên người dùng</label>
                <input type="text" name="name" class="form-control" placeholder="Tên" value="{{ request('name') }}">
            </div>
            <div class="col">
                <label class="form-label fw-bold">Địa chỉ Email</label>
                <input type="text" name="email" class="form-control" placeholder="Email" value="{{ request('email') }}">
            </div>
            <div class="col">
                <label class="form-label fw-bold">Số điện thoại</label>
                <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" value="{{ request('phone') }}">
            </div>
            <div class="col">
                <label class="form-label fw-bold">Vai trò</label>
                <select name="role" class="form-select">
                    <option value="">-- Vai trò --</option>
                    <option value="admin" {{ request('role') == 'active' ? 'selected' : '' }}>Admin</option>
                    <option value="salesperson" {{ request('role') == 'active' ? 'selected' : '' }}>Nhân viên Sale</option>
                    <option value="instructor" {{ request('role') == 'inactive' ? 'selected' : '' }}>Giáo viên</option>
                    {{-- @foreach(Spatie\Permission\Models\Role::where('name', '!=', 'student')->get() as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach --}}
                </select>
            </div>
            <div class="col">
                <label class="form-label fw-bold">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="">-- Trạng thái --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>
            <div class="col">
                <label class="form-label fw-bold">Giới tính</label>
                <select name="gender" class="form-select">
                    <option value="">-- Giới tính --</option>
                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                    <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>
            <div class="col-12 col-lg-3 col-md-12 mb-2">
                <label for="">&nbsp;</label>
                <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mb-1">
                        <b>Tìm Kiếm</b>
                    </button>
                    <div class="ms-2">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-danger mb-1" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <a href="{{ route('users.create') }}"
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 40px; height: 40px; font-size: 30px; bottom: 3%; right: 60px; z-index: 99;">
                +
        </a>

        <div class="table-responsive">
            <table class="table mt-3 table-bordered" style="width: max-content; min-width: 100%;">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Giới tính</th>
                        <td>Ngày tham gia</td>
                        <th>Trạng thái</th>
                        <th>Vai trò</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $key = ($users->currentPage() - 1) * $users->perPage();
                    @endphp
                    @foreach($users as $user)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            @php
                                $genderValue = strtolower($user->gender);
                                $genderLabel = [
                                    'male' => 'Nam',
                                    'female' => 'Nữ',
                                    'other' => 'Khác',
                                ][$genderValue] ?? 'Không rõ';
                            @endphp
                            <td>{{ $genderLabel }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y'); }}
                            </td>
                            <td>
                                @if (in_array($user->status, ['active', 'inactive']))
                                    <form action="{{ route('users.status', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="badge border-0 bg-{{ $user->status == 'active' ? 'success' : 'danger' }}"
                                            style="cursor: pointer;"
                                            onclick="return confirm('Bạn có chắc muốn thay đổi trạng thái người dùng này?')" title="nhấn để chuyển trạng thái của người dùng">
                                            {{ $user->status == 'active' ? 'Hoạt động' : 'Không hoạt động' }} <i class="fa-solid fa-rotate"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-info">Không xác định</span>
                                @endif
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                    {{-- <span class="badge bg-info">{{ $role->name }}</span> --}}
                                    @php
                                        $roleValue = strtolower($role->name);
                                        $roleLabel = [
                                            'admin' => 'Admin',
                                            'instructor' => 'Giáo viên',
                                            'salesperson' => 'Nhân viên Sale',
                                        ][$roleValue] ?? 'Không rõ';
                                    @endphp
                                    <span class="badge bg-info">{{ $roleLabel }}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info m-1" style="padding: 2px 12px;"><i class="mdi mdi-eye-outline"></i></a>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning m-1"><i class="fa-solid fa-user-pen"></i></a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
