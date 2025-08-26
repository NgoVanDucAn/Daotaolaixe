@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="card-body">
        {{-- @php
            $baseQuery = request()->except('type', 'page');
        @endphp --}}
        {{-- Giả dạng tab để chọn loại lịch --}}
        {{-- <div class="mb-3">
            <ul class="nav nav-pills" id="calendarTypeTabs" role="tablist">
                @foreach($calendarTypes as $key => $label)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $key == request('type', 'study') ? 'active' : '' }}"
                            href="{{ request()->fullUrlWithQuery(array_merge($baseQuery, ['type' => $key, 'page' => 1])) }}" 
                            data-type="{{ $key }}"
                            role="tab" 
                            aria-controls="tab-{{ $key }}" 
                            aria-selected="{{ $key == request('type', 'study') ? 'true' : 'false' }}">
                            {{ ucwords($label) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div> --}}

        <div class="filter-options mb-3">
            <form id="instructorFilterForm" class="row" method="GET">

                <div class="col-12 col-md-3 mb-3">
                    <label class="form-label fw-bold">Tên</label>
                    {{-- <input type="text" name="user_name" placeholder="Nhập tên giáo viên" class="form-control mb-2" value="{{ request('user_name') }}"> --}}
                    <select name="teacher_id" id="teacher_id" class="form-select">
                        <option value="">-- Chọn giáo viên --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="col-6 col-md-3 mb-4 position-relative">
                    <label class="form-label fw-bold">Ngày bắt đầu</label>
                    <input 
                        type="text" 
                        placeholder="dd/mm/yyyy"
                        name="start_date" 
                        value="{{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '' }}"
                        class="form-control real-date" autocomplete="off" 
                    >
                </div>
                <div class="col-6 col-md-3 mb-4 position-relative">
                    <label class="form-label fw-bold">Ngày kết thúc</label>
                    <input 
                        type="text" 
                        placeholder="dd/mm/yyyy"
                        name="end_date" 
                        value="{{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : '' }}"
                        class="form-control real-date" autocomplete="off" 
                    >
                </div>

                <div class="col-12 col-md-3 mb-2">
                    <label for="">&nbsp;</label>
                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mb-1">
                            <b>Tìm Kiếm</b>
                        </button>
                        <div class="ms-2">
                            <a href="{{ route('instructor.index') }}" class="btn btn-outline-danger mb-1" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a
            href="{{ route('instructor.create') }}"
            type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Thêm Giáo Viên Mới</span>
        </a>
    </div>
@endsection

<div class="card">
    <div class="card-body">
        <a href="{{ route('instructor.create') }}"
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 40px; height: 40px; font-size: 30px; bottom: 3%; right: 60px; z-index: 99;">
                +
        </a>
        <div class="alert alert-primary d-flex align-items-center" role="alert">
            <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-20 me-1"></iconify-icon>
            <div class="lh-1"><strong>Tổng số giáo viên: </strong><strong>{{ $countUsers }}</strong></div>
        </div>
        <div class="table-responsive">
            <table class="table mt-3 table-bordered" style="width: max-content; min-width: 100%;">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle">STT</th>
                        <th rowspan="2" class="align-middle">Họ tên</th>
                        <th rowspan="2" class="align-middle">Giới tính</th>
                        <th rowspan="2" class="align-middle">Ngày sinh</th>
                        <th rowspan="2" class="align-middle">CMT/CCCD</th>
                        {{-- <th rowspan="2" class="align-middle">Email</th> --}}
                        <th rowspan="2" class="align-middle">Địa chỉ</th>
                        <th rowspan="2" class="align-middle">Hạng</th>
                        <th rowspan="2" class="align-middle">Biển số xe</th>
                        <th rowspan="2" class="align-middle">SĐT</th>
                        <th colspan="{{ $learningFeilds->count() + 1  }}">Số giờ đã dạy</th>
                        {{-- <th colspan="4">Số giờ đã dạy</th> --}}
                        {{-- <th colspan="3">Số giờ dạy</th>
                        <th colspan="3">Thành tiền</th> --}}
                        <th rowspan="2" class="align-middle">Trạng thái</th>
                        <th rowspan="2" class="align-middle">Ngày hoạt động</th>
                        <th rowspan="2" class="align-middle">Hành động</th>
                    </tr>
                    <tr>
                        <th>Tổng giờ</th>
                        {{-- @foreach ($learningFeilds as $learningFeild)
                        <th>{{ $learningFeild->name }}</th>
                        @endforeach --}}
                        <th>Kỹ năng</th>
                        <th>Sa hình</th>
                        <th>Chạy DAT</th>
                        {{-- <th>Đã duyệt</th>
                        <th>Chưa duyệt</th>
                        <th>Tổng tiền</th>
                        <th>Tiền giờ duyệt</th>
                        <th>Tiền giờ chưa duyệt</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @php
                        $key = ($users->currentPage() - 1) * $users->perPage();
                    @endphp
                    @foreach($users as $user)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td class="text-start">{{ $user->name }}</td>
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
                            {{-- <td>{{ $user->email }}</td> --}}
                            <td class="text-start" style="width: 200px;">{{ $user->address }}</td>

                            <td>
                                @if (!empty($user->selected_ranking_names))
                                    @foreach ($user->selected_ranking_names as $rankingName)
                                        <span class="badge bg-secondary">{{ $rankingName }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ $user->vehicle?->license_plate }}</td>
                            <td>{{ $user->phone }}</td>
                            
                            <td style="color: red" class="">{{ $user->total_hour }}</td>
                            @foreach ($learningFeilds as $field)
                                <td>
                                    {{ $user->teaching_hours_by_field[$field->id] ?? 0 }}
                                </td>
                            @endforeach
                            {{-- <td class="text-success">{{ $user->confirmed_hour }}</td>
                            <td class="text-danger">{{ $user->unconfirmed_hour }}</td>
                            <td class="text-warning">{{ $user->total_money }}</td>
                            <td class="text-success">{{ $user->confirmed_money }} K</td>
                            <td class="text-danger">{{ $user->unconfirmed_money }} K</td> --}}
                            <td>
                                @if (strtolower($user->status) === 'active')
                                    <span class="badge bg-success">Hoạt Động</span>
                                @else
                                    <span class="badge bg-danger">Dừng hoạt động</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at?->format('d-m-Y') }}</td>
                            <td>
                                {{-- <a href="{{ route('instructor.show', $user->id) }}" class="btn btn-sm btn-info ps-2 pe-2 pt-0 pb-0">
                                    <i class="mdi mdi-eye-outline fs-4"></i>
                                </a> --}}
                                <a href="{{ route('instructor.edit', $user->id) }}" class="btn btn-sm btn-warning ps-2 pe-2 pt-0 pb-0">
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
            
            $('#teacher_id').select2({
                placeholder: '-- Chọn giáo viên --',
                allowClear: true,
                width: '100%'
            });
            
        }); 
    </script>
    
@endsection
