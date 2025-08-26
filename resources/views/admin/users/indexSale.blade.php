@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('sale.index') }}" class="row">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-2 mt-2">
                <label class="form-label fw-bold">Tên</label>
                <select name="sale_id" id="sale_id" class="form-select">
                    <option value=""></option>
                    @foreach ($saleAlls as $user)
                        <option value="{{ $user->id }}" {{ request('sale_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                {{-- <input type="text" name="name" class="form-control" placeholder="Nhập tên" value="{{ request('name') }}"> --}}
            </div>
            
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-2 mt-2 position-relative">
                <label class="form-label fw-bold">Ngày bắt đầu ký hợp đồng</label>
                <input type="text" placeholder="dd/mm/yyyy"name="start_date" value="{{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '' }}" class="form-control real-date" autocomplete="off">
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-2 mt-2 position-relative">
                <label class="form-label fw-bold">Ngày kết thúc ký hợp đồng</label>
                <input type="text" placeholder="dd/mm/yyyy"name="end_date" value="{{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : '' }}" class="form-control real-date" autocomplete="off">
            </div>
            
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-2 mt-2 mb-2 d-flex align-items-end justify-content-center">
                <button type="submit" class="btn btn-primary mb-1"><b>Tìm Kiếm</b></button>
                <div class="ms-2">
                    <a href="{{ route('sale.index') }}" class="btn btn-outline-danger mb-1" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
                </div>
            </div>
        </form>
    </div>
</div>

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a
            href="{{ route('sale.create') }}"
            type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Tạo Sale</span>
        </a>
    </div>
@endsection

<div class="card">
    <div class="card-body">
        <a href="{{ route('sale.create') }}"
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 40px; height: 40px; font-size: 30px; bottom: 3%; right: 60px; z-index: 99;">
                +
        </a>
        <div class="alert alert-primary d-flex align-items-center" role="alert">
            <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-20 me-1"></iconify-icon>
            <div class="lh-1">
                <strong>Tổng số hợp đồng đã ký: </strong>
                <strong class="text-danger fw-bold">{{ $totalContractSigned }}</strong>
                <strong class="ms-4">Tổng lead: </strong>
                <strong class="text-danger fw-bold">{{ $countLead }}</strong>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table mt-3 table-bordered" style="width: max-content; min-width: 100%;">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Họ tên</th>
                        <th>Giới tính</th>
                        <th>Ngày sinh</th>
                        <th>CMT/CCCD</th>
                        <th>Địa chỉ</th>
                        <th>SĐT</th>
                        <th>Số hợp đồng đã ký</th>
                        <th>Tổng lead</th>
                        {{-- <th>Số lead chăm sóc thành công</th> --}}
                        <th>Trạng thái</th>
                        <th>Ngày hoạt động</th>
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
                            @php
                                $genderValue = strtolower($user->gender);
                                $genderLabel = [
                                    'male' => 'Nam',
                                    'female' => 'Nữ',
                                    'other' => 'Khác',
                                ][$genderValue] ?? 'Không rõ';
                            @endphp
                            <td>{{ $genderLabel }}</td>
                            <td>{{ $user->dob?->format('d-m-Y') }}</td>
                            <td>{{ $user->identity_card }}</td>
                            <td class="text-start">{{ $user->address }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->total_courses_through_students }}</td>
                            {{-- <td>{{ $user->total_students }}</td> --}}
                            {{-- <td>{{ $user->successful_students }}</td> --}}
                            <td>{{ $user->in_lead }}</td>
                            <td>
                                <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $user->status == 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </td>
                            {{-- <td>
                                @foreach($user->roles as $role)
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
                            </td> --}}
                            <td>{{ $user->created_at->format('d-m-Y') }}</td>
                            <td>
                                <a href="{{ route('sale.show', $user->id) }}" class="btn btn-sm btn-info ps-2 pe-2 pt-0 pb-0">
                                    <i class="mdi mdi-eye-outline fs-4"></i>
                                </a>
                                <a href="{{ route('sale.edit', $user->id) }}" class="btn btn-sm btn-warning ps-2 pe-2 pt-0 pb-0">
                                    <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger ps-2 pe-2 pt-0 pb-0" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                        <i class="mdi mdi-trash-can-outline fs-4"></i>
                                    </button>
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
@section('js')
<script>
     $(document).ready(function() {
        $('#sale_id').select2({
            placeholder: 'Chọn Sale',
            width: '100%',
            allowClear: true
        });
    });
</script>
    
@endsection
