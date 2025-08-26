@extends('layouts.admin')

@section('content')

<style>
    .table-container {
        overflow-x: auto;
        width: 100%;
        max-height: 70vh;
    }
</style>

<div class="card">
    <div class="card-body">
        @php
            $baseQuery = request()->except('type', 'page');
        @endphp
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
            <form id="calendarFilterForm" class="row" method="GET">
                <div class="col-6 col-md-2 mb-2">
                    <label for="stadium_id" class="form-label">Sân tập</label>
                    <select name="stadium_id" id="stadium_id" class="form-select select2">
                        <option value="">Chọn sân</option>
                        @foreach ($stadiums as $stadium)
                            <option value="{{ $stadium->id }}" {{ request('stadium_id') == $stadium->id ? 'selected' : '' }}>
                                {{ $stadium->location }}
                            </option>
                        @endforeach
                    </select>
                    @error('stadium_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- <div class="col-12 col-md-2 mb-2">
                    <label class="form-label fw-bold">Mã học viên</label>
                    <input type="text" name="student_code" placeholder="Mã học viên" class="form-control" value="{{ request('student_code') }}">
                </div> --}}

                <div class="col-12 col-md-3 mb-2">
                    <label class="form-label fw-bold">Tên học viên</label>
                    <input type="text" name="student_name" placeholder="Tên học viên" class="form-control mb-2" value="{{ request('student_name') }}">
                </div>

                <div class="col-12 col-md-3 mb-2">
                    <label class="form-label fw-bold">Tên giáo viên</label>
                    <input type="text" name="user_name" placeholder="Tên giáo viên, quản lý,..." class="form-control mb-2" value="{{ request('user_name') }}">
                </div>

                {{-- <div class="col-12 col-md-3 mb-2">
                    <label class="form-label fw-bold">Khóa học</label>
                    <select name="course_id" class="form-control">
                        <option value="">Chọn khóa học</option>
                        @foreach($courseAlls as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->code }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}

                <div class="col-6 col-md-2 mb-2 position-relative">
                    <label class="form-label fw-bold">Ngày bắt đầu</label>
                    <input 
                        type="text" 
                        placeholder="dd/mm/yyyy"
                        name="start_date" 
                        value="{{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '' }}"
                        class="form-control real-date" autocomplete="off" 
                    >
                </div>
                <div class="col-6 col-md-2 mb-2 position-relative">
                    <label class="form-label fw-bold">Ngày kết thúc</label>
                    <input 
                        type="text" 
                        placeholder="dd/mm/yyyy"
                        name="end_date" 
                        value="{{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : '' }}"
                        class="form-control real-date" autocomplete="off" 
                    >
                </div>

                {{-- <div class="col-12 col-md-2 mb-2">
                    <label class="form-label fw-bold">Khoảng thời gian</label>
                    <select name="time_filter" class="form-select mb-2">
                        <option value="">Chọn khoảng thời gian</option>
                        <option value="90" {{ request('time_filter') == '90' ? 'selected' : '' }}>90 ngày trước</option>
                        <option value="30" {{ request('time_filter') == '30' ? 'selected' : '' }}>30 ngày trước</option>
                        <option value="7" {{ request('time_filter') == '7' ? 'selected' : '' }}>7 ngày trước</option>
                        <option value="71" {{ request('time_filter') == '71' ? 'selected' : '' }}>7 ngày sắp tới</option>
                        <option value="301" {{ request('time_filter') == '301' ? 'selected' : '' }}>30 ngày sắp tới</option>
                        <option value="901" {{ request('time_filter') == '901' ? 'selected' : '' }}>90 ngày sắp tới</option>
                    </select>
                </div> --}}

                {{-- <div class="col-12 col-md-2 mb-2" id="examFilter" style="{{ request('type') === 'exam' ? '' : 'display: none;' }}">
                    <label class="form-label fw-bold">Loại kỳ thi</label>
                    <select name="level_filter" class="form-select mb-2" {{ request('type') === 'exam' ? '' : 'disabled' }}>
                        <option value="">Chọn loại kỳ thi</option>
                        <option value="1" {{ request('level_filter') == '1' ? 'selected' : '' }}>Thi tốt nghiệp</option>
                        <option value="2" {{ request('level_filter') == '2' ? 'selected' : '' }}>Thi sát hạch</option>
                    </select>
                </div> --}}
                
                {{-- <div class="col-12 col-md-3 mb-2">
                    <button type="submit" class="btn btn-primary mb-1">Lọc</button>
                    <a href="{{ $removeFilter }}" class="btn btn-secondary mb-1" id="clearFilters">Bỏ Lọc</a>
                </div> --}}
                <div class="col-12 col-md-3 mb-2">
                    <label for="">&nbsp;</label>
                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mb-1">
                            <b>Tìm Kiếm</b>
                        </button>
                        <div class="ms-2">
                            <a href="{{ $removeFilter }}" class="btn btn-outline-danger mb-1" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route($back),
            'title' => "Quay về trang $description"
        ])
    </div>
@endsection
@php
    $firstDate = collect($paginatedCalendars->items())->keys()->first();
@endphp
@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a
            {{-- href="{{ route('students.create') }}" --}}
            type="button" 
            class="btn"
            data-date="{{ $firstDate }}"
            data-bs-toggle="modal"
            data-bs-target="#addActivitieModal"
            data-calendar-type="{{ $typeAccept }}"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Tạo học viên</span>
        </a>
    </div>
@endsection

<div class="card" id="card">
    <div class="card-body">
        <a href="{{ route('calendars.create') }}" 
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 40px; height: 40px; font-size: 30px; bottom: 3%; right: 60px; z-index: 99;">
                +
        </a>
        {{-- Phân trang --}}
        <div class="mt-3">
            {{ $paginatedCalendars->links('pagination::bootstrap-5') }}
        </div>
        <div class="table-container" id="bottom-scroll top-scroll">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th style="white-space: normal;">Ngày</th>
                        <th >Thời gian</th>
                        <th>Học viên</th>
                        <th>Ngày sinh</th>
                        @if ($study == 'th')
                            <th>Số điện thoại</th>
                        @endif
                        <th>Môn học</th>
                        @if ($study == 'th')
                            <th>Giáo viên</th>
                        @endif
                        @if ($study == 'lt')
                            <th>Trạng thái</th>
                        @endif
                        @if ($study == 'th')
                            <th>Điểm đón</th>
                        @endif
                        <th>Sân học</th>
                        <th>Khoá học</th>
                        @if ($study == 'th')
                            <th>Tự động</th>
                            <th>Ban đêm</th>
                            <th>Duyệt Km</th>
                            <th>Km</th>
                            <th>Giờ</th>
                        @endif
                        
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php \Carbon\Carbon::setLocale('vi'); $counter = 1; @endphp
                
                    @foreach($paginatedCalendars as $date => $group)
                    
                        @php
                            $calendars = $group['calendars_by_stadium'];
                            $totalStudentCountForDate = 0;
                
                            foreach ($calendars as $calendar) {
                                $count = $calendar['student_count'];
                                $totalStudentCountForDate += $calendar['student_count'];
                            }
                
                            $printedDateCell = false;
                        @endphp
                        @foreach ($calendars as $calendarGroup)
                            @foreach ($calendarGroup['calendars'] as $calendar)
                                @foreach ($calendar->courseStudents as $students)
                                {{-- @dd($students) --}}
                        
                                    <tr>
                                        {{-- Cột STT --}}
                                        <td>{{ $counter++ }}</td>

                
                                        {{-- Cột Ngày --}}
                                        @if (!$printedDateCell)
                                            <td rowspan="{{ $totalStudentCountForDate }}" style="min-width: 120px;">
                                                <span>
                                                    {{ ucfirst(\Carbon\Carbon::parse($date)->translatedFormat('l')) }} <br>
                                                    {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
                                                </span>
                                            </td>
                                            @php $printedDateCell = true; @endphp
                                        @endif
                
                                        {{-- Cột thời gian --}}
                                            <td>
                                                {{ \Carbon\Carbon::parse($calendar['date_start'])->format('G\hi') }} - {{ \Carbon\Carbon::parse($calendar['date_end'])->format('G\hi') }}
                                            </td>

                
                                        {{-- Học viên --}}
                                        {{-- @dd($students)+ --}}
                                        <script>
                                            console.log(@json($students));
                                            
                                        </script>
                                        <td class="text-nowrap text-start">
                                            {{ $students->student->name }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($students->student->dob)->format('d-m-Y') }}</td>
                                        @if ($study == 'th')
                                            <td>{{ $students->student->phone }}</td>    
                                        @endif
                                        {{-- Lĩnh vực, người dùng, sân, khóa học --}}
                                        
                                            <td><span class="badge bg-secondary">{{ $calendar['learningField']['name'] ?? '--' }}</span></td>
                                            <td class="text-start" style="min-width: 120px;">
                                                @foreach ($teachers as $teacher)
                                                    @if ($teacher->id == $students->teacher_id)
                                                        {{ $teacher->name }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            @if ($study == 'th')
                                                <td>0 new</td>
                                            @endif
                                            <td style="min-width: 120px;">
                                                <a href="{{ route('stadiums.index') }}">
                                                    {{ $calendar['stadium']['location'] ?? '--' }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $students?->course?->code }}
                                            </td>
                                        
                
                                        {{-- Thực hành --}}
                                        @if ($study == 'th')
                                            <td>
                                                <i style="font-size: 24px; color: green" class="mdi mdi-check-circle-outline"></i>lưu
                                            </td>
                                            <td>
                                                <i style="font-size: 24px; color: red" class="mdi mdi-close-circle-outline"></i>lưu
                                            </td>
                                            {{-- <td>{{ $students['pivot']['auto_hours'] ?? '--' }}</td>
                                            <td>{{ $students['pivot']['night_hours'] ?? '--' }}</td> --}}
                                        @endif
                
                                        {{-- Trạng thái --}}
                                        @if ($study == 'th')
                                            <td>
                                                @if ($calendar['status'] == 3)
                                                    <form action="{{ route('calendar.approve', $calendar['id']) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="btn btn-primary">Duyệt ngay</button>
                                                    </form>
                                                @elseif ($calendar['status'] == 10)
                                                    <span class="badge bg-success">
                                                        Đã duyệt
                                                    </span>
                                                @elseif ($calendar['status'] == 4)
                                                    <span class="badge bg-danger">
                                                        Đã hủy
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        Chưa có kết quả
                                                    </span>
                                                @endif
                                            </td>
                                        @endif
                
                                        {{-- Thực hành tiếp --}}
                                        @if ($study == 'th')
                                            <td>{{ $students['pivot']['km'] ?? '--' }}</td>
                                            <td>{{ $students['pivot']['hours'] ?? '--' }}</td>
                                        @endif
                                        {{-- Hành động --}}
                                        <td>
                                            <div class="d-flex gap-2 align-items-center">
                                                <a href="#"
                                                    class="btn btn-sm btn-warning editExamBtn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editActivitieModal"
                                                    data-calendar-id="{{ $calendar->id }}"
                                                    data-date-start="{{ $calendar->date_start }}"
                                                    data-date-end="{{ $calendar->date_end }}"
                                                    {{-- data-time="{{ $calendar->time }}" --}}
                                                    data-date="{{ $firstDate }}"
                                                    {{-- data-lanthi="{{ $calendar->calendarStudents[0]?->examFields[0]?->attempt_number ?? '' }}"--}}
                                                    data-course-id="{{ $calendar->courses[0]['id'] ?? '' }}"
                                                    data-course-type="{{ $calendar->exam_course_type }}"
                                                    data-status="{{ $calendar->status }}"
                                                    data-description="{{ $calendar->description }}"
                                                    data-stadium="{{ $calendar->stadium?->id }}" 
                                                    data-students="{{ $calendar->students }}"
                                                >
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                @if (!in_array($calendar['status'], [4, 10]) && now()->lt(\Carbon\Carbon::parse($calendar['date_start'])))
                                                    <a href="{{ route('calendars.edit', $calendar['id']) }}" class="btn btn-primary btn-sm">
                                                        <i class="fa-solid fa-user-pen" title="Chỉnh sửa"></i>
                                                    </a>
                                                @endif
                                                <script>
                                                    console.log(@json($students));
                                                    console.log(@json($students->id));
                                                    
                                                </script>
                                                <button class="btn btn-sm btn-info openResultModalBtn" 
                                                    data-calendar-id="{{ $calendar['id'] }}" 
                                                    data-student-id="{{ $students->id }}" 
                                                    data-course-id="{{ $students->course_id }}" 
                                                    data-type="{{ $calendar['type'] }}">
                                                    <i class="fa-solid fa-file-pen" title="Nhập kết quả"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                @endforeach
                            @endforeach
                        @endforeach
                    
                    @endforeach
                </tbody>
                
            </table>
        </div>
    </div>
</div>

{{-- edit --}}
<div class="modal fade" id="editActivitieModal" tabindex="-1" aria-labelledby="editActivitieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editActivitieModalLabel">Chỉnh Sửa Lịch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditActivitieModal" action="{{ route('calendars.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" id="type" value="">
                    <div class="row">
                        <div class="col-12 ">
                            <div class="row">
                                <div id="edit-study-fields" class="mb-4">
                                    <div class="row g-3">
                                        <div class="col-12 col-lg-9">
                                            <div class="row g-3">
                                                <div class="form-group col-12 mb-2">
                                                    <label for="name" class="form-label">Tên sự kiện</label>
                                                    <input type="text" class="form-control" id="edit-name" value="{{ old('name') }}">
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="form-group col-12 col-md-4">
                                                    <label for="date_start" class="form-label">Buổi học</label>
                                                    <select name="time_learn" id="edit_time_learn" class="form-control @error('time_learn') is-invalid @enderror">
                                                        <option value="">-- Chọn buổi học --</option>
                                                        @foreach(range(1,100) as $i)
                                                            <option value="{{ $i }}" {{ old('time_learn') }}>Buổi {{ $i }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('time_learn')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6 col-md-4" id="date-start-field">
                                                    <label for="date_start" class="form-label">Thời Gian Bắt Đầu</label>
                                                    <input 
                                                        type="text" 
                                                        placeholder="dd/mm/yyyy HH:mm" 
                                                        name="date_start" id="edit_date_start" 
                                                        class="form-control datetime-local @error('date_start') is-invalid @enderror"
                                                        value="{{ old('date_start') ? \Carbon\Carbon::parse(old('date_start'))->format('d/m/Y H:i') : '' }}"
                                                    />
                                                    @error('date_start')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                            
                                                <div class="form-group col-6 col-md-4" id="date-end-field">
                                                    <label for="date_end" class="form-label">Thời Gian Kết Thúc</label>
                                                    <input type="text" placeholder="dd/mm/yyyy HH:mm" name="date_end" id="edit_date_end" class="form-control datetime-local @error('date_end') is-invalid @enderror"/>
                                                    @error('date_end')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="form-group col-12 col-md-12">
                                                    <label for="vehicle_type" class="form-label">Hình thức học</label>
                                                    <select name="vehicle_type" id="edit_vehicle_type_learn" class="form-control">
                                                        <option value="">-- Chọn --</option>
                                                        <option value="1">Học xe máy</option>
                                                        <option value="2">Học ô tô</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <div class="form-group col-9 col-md-12">
                                                    <label for="stadium_id" class="form-label mt-2">Sân tập</label>
                                                    <select name="stadium_id" id="edit_select_stadium_id" class="form-select">
                                                        <option value="">Chọn sân</option>
                                                        @foreach ($stadiums as $stadium)
                                                            <option value="{{ $stadium->id }}" {{ old('stadium_id') == $stadium->id ? 'selected' : '' }}>
                                                                {{ $stadium->location }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="form-group col-9 col-md-12">
                                                    <label for="status" class="form-label mt-2">Trạng thái</label>
                                                    <select name="status" id="edit_status_id_learn" class="form-select">
                                                        <option value="1">Đang chờ</option>
                                                        <option value="2">Đang học</option>
                                                        <option value="10">Hoàn thành</option>
                                                        <option value="3">Thiếu giáo viên</option>
                                                        <option value="4">Hủy ca</option>
                                                        <option value="5">Hoãn</option>
                                                        <option value="6">Bỏ ca lại</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3 col-12 col-md-12 learning_id">
                                                <label for="learning_id" class="form-label">Môn học
                                                    <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Cần chọn khóa học trước" style="cursor: pointer;">&#x3f;</span>
                                                </label>
                                                <div id="edit_learning_id" class="mt-2">
                                                    <p class="text-muted">-- Vui lòng chọn hình thức học trước --</p>
                                                </div>
                                                @error('learning_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- <div class="row g-3">
                                                <div class="form-group col-12 col-md-6" id="edit-date-start-field">
                                                    <label for="date_start" class="form-label">Ngày Bắt Đầu</label>
                                                    <input type="text" placeholder="dd/mm/yyyy HH:mm" name="date_start" id="edit_date_start" class="form-control datetime-local @error('date_start') is-invalid @enderror"/>
                                                    @error('date_start')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                            
                                                <div class="form-group col-12 col-md-6" id="edit-date-end-field">
                                                    <label for="date_end" class="form-label">Ngày Kết Thúc</label>
                                                    <input type="text" placeholder="dd/mm/yyyy HH:mm" name="date_end" id="edit_date_end" class="form-control datetime-local @error('date_end') is-invalid @enderror"/>
                                                    @error('date_end')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                            
                                            <div class="row g-3">
                                                <div class="form-group col-12 col-md-12">
                                                    <label for="learn_course_id" class="form-label">Khóa học</label>
                                                    <select name="learn_course_id" id="edit_learn_course_id" class="form-control">
                                                        <option value="">-- Chọn --</option>
                                                    </select>
                                                </div>
                                            
                                            </div>
                                            <div class="form-group mb-3 col-12 col-md-12">
                                                <label for="learning_id" class="form-label">Môn học
                                                    <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Cần chọn khóa học trước" style="cursor: pointer;">&#x3f;</span>
                                                </label>
                                                <div id="edit_learning_id" class="form-control">
                                                    <p class="text-muted">-- Vui lòng chọn khóa học trước --</p>
                                                </div>
                                                @error('learning_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-3 col-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="km">Km</label>
                                                        <input type="number" class="form-control" value="" min="0" name="km">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="hour">Số giờ chạy được</label>
                                                        <input type="time" class="form-control" value="" min="0" name="hour">
                                                    </div>
                                                </div>
                                                <div class="row mt-4">
                                                    <div class="col-md-6 d-flex align-item-center">
                                                        <input type="checkbox" class="form-check-input" id="learn_auto" name="learn_auto">
                                                        <span class="ms-2">Học tự động</span>
                                                        
                                                    </div>
                                                    <div class="col-md-6 d-flex align-item-center">
                                                        <input type="checkbox" class="form-check-input" id="learn_night" name="learn_night">
                                                        <span class="ms-2">Học ban đêm</span> 
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row g-3">
                                                <div class="form-group col-9 col-md-12">
                                                    <label for="pick_ip_point" class="form-label mt-2">Điểm đón</label>
                                                    <textarea name="pick_ip_point" id="edit_pick_ip_point" class="form-control"></textarea>
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <div class="form-group col-9 col-md-12">
                                                    <label for="stadium_id" class="form-label mt-2">Sân tập</label>
                                                    <select name="stadium_id" id="edit_select_stadium_id" class="form-select">
                                                        <option value="">Chọn sân</option>
                                                        @foreach ($stadiums as $stadium)
                                                            <option value="{{ $stadium->id }}" {{ old('stadium_id') == $stadium->id ? 'selected' : '' }}>
                                                                {{ $stadium->location }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="form-group col-9 col-md-12">
                                                    <label for="status" class="form-label mt-2">Trạng thái</label>
                                                    <select name="status" id="edit_status_id" class="form-select">
                                                        <option value="">Chọn trạng thái</option>
                                                        <option value="8">Đang chờ</option>
                                                        <option value="9">Đang học</option>
                                                        <option value="10">Hoàn thành</option>
                                                        <option value="11">Thiếu giáo viên</option>
                                                        <option value="12">Hủy ca</option>
                                                        <option value="13">Hoãn</option>
                                                        <option value="14">Bỏ ca lại</option>
                                                    </select>
                                                </div>
                                            </div> --}}
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="learn_teacher_id" class="form-label">Giáo viên</label>
                                                <select name="learn_teacher_id" id="edit_learn_teacher_id" class="form-control @error('learn_teacher_id') is-invalid @enderror">
                                                    <option value="">-- Vui lòng chọn thời gian trước --</option>
                                                </select>
                                                @error('learn_teacher_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="learn_student_id" class="form-label">Học viên
                                                    <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Cần chọn thời gian và khóa học trước" style="cursor: pointer;">&#x3f;</span>
                                                </label>
                                                <select name="learn_student_id[]" id="edit_learn_student_id_select" class="w-full form-control @error('learn_student_id') is-invalid @enderror" multiple>
                                                    <option value="">-- Vui lòng chọn khóa học, thời gian của lịch trước --</option>
                                                </select>
                                                @error('learn_student_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div id="alert-message" class="alert alert-danger d-none"></div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="vehicle_select" class="form-label">Xe Học</label>
                                                <select name="vehicle_select" id="edit_vehicle_select" class="form-select">
                                                    <option value="">-- Chọn --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                            <i class="mdi mdi-close me-2"></i> <span>Hủy</span>
                        </button>
                        <button type="submit" class="btn btn-primary" form="formEditActivitieModal">
                            <i class="mdi mdi-content-save-outline me-2"></i> <span>Lưu</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Modal Update Result Calendar -->
    <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nhập kết quả của lịch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <form id="resultForm">
                        <div id="studentsResultFields">
                            <!-- sẽ được render bằng JS -->
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Lưu kết quả</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.calendars.add-calendar-modal')
@endsection

@section('js')
{{-- add --}}
<script src="{{ asset('assets/js/add-calendar-modal.js') }}"></script>

{{-- edit --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
    // const startTimeInput = document.getElementById('edit_date_start');
    // const endTimeInput = document.getElementById('edit_date_end');
    // const learnCourseSelect = document.getElementById('edit_vehicle_type_learn');;
    // const dateStartField = document.getElementById('edit-date-start-field');
    // const dateEndField = document.getElementById('edit-date-end-field');
    // const typeInput = document.getElementById('type');

    // Reset modal khi đóng
    const startTimeInput = document.getElementById('edit_date_start');
    const endTimeInput = document.getElementById('edit_date_end');
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    const vehicleTypeSelect = document.getElementById('edit_vehicle_type_learn')
    const dateStartField = document.getElementById('edit-date-start-field');
    const dateEndField = document.getElementById('edit-date-end-field');
    // const examDateTimeField = document.getElementById('edit-exam-date-time');
    const typeInput = document.getElementById('type');
    const examCourseTypeInput = document.getElementById('edit_exam_course_type');

    // Reset modal khi đóng
    $('#addActivitieModal').on('hidden.bs.modal', function () {
        var modalForm = $(this).find('form');
        modalForm[0].reset();
        $('#edit_learn_course_id').val('').trigger('change');
        $('#edit_exam_course_id').val('').trigger('change');
        $('#edit_vehicle_select').val('').trigger('change');
        $('#edit_learn_student_id_select').val('').trigger('change');
        $('#edit_exam_student_id_select').val('').trigger('change');
        $('#edit_learn_teacher_id').val('').trigger('change');
        $('#edit_exam_teacher_id').val('').trigger('change');
        $('#edit_learn_student_id_select').empty();
        $('#edit_exam_student_id_select').empty();
        $('#edit_learn_course_id').empty().append(new Option('-- Chọn khóa học --', ''));
        $('#edit_exam_course_id').empty().append(new Option('-- Chọn khóa học --', ''));
        $('#edit_learn_teacher_id').empty().append(new Option('Chọn giáo viên', ''));
        $('#edit_exam_teacher_id').empty().append(new Option('Chọn giáo viên', ''));
        $('#edit_student-inputs').empty();
        $('#edit_alert-message').addClass('d-none');
        typeInput.value = '';
        if (examCourseTypeInput) examCourseTypeInput.value = '';
    });

    // Khởi tạo modal khi mở
    $('#addActivitieModal').on('show.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget);
        var calendarType = button.data('calendar-type');
        var examCourseType = button.data('exam-course-type');
        examCourseTypeInput.value = examCourseType;
        $('#status_id').prop('disabled', true);
        $('#vehicle_type_exam').prop('disabled', true);
        // Cập nhật tiêu đề modal
        
        var modalTitle = 'Thêm Mới Lịch';
        switch (calendarType) {
            case 'study_practice':
                modalTitle = 'Thêm Lịch Học Thực Hành';
                const itemShow = document.querySelectorAll('.form-calendar-learn-instructor');
                itemShow.forEach(e => {
                    e.style.display = 'block';
                });
                
                break;
            case 'study_theory':
                modalTitle = 'Thêm Lịch Học Lý Thuyết';
                const itemHidden = document.querySelectorAll('.form-calendar-learn-instructor');
                itemHidden.forEach(e => {
                    e.style.display = 'none';
                });
                break;
            case 'exam_practice':
                modalTitle = 'Thêm Lịch Thi Thực Hành';
                break;
            case 'exam_theory':
                modalTitle = 'Thêm Lịch Thi Lý Thuyết';
                break;
            case 'exam_graduation':
                modalTitle = 'Thêm Lịch Thi Tốt Nghiệp';
                break;
            case 'exam_certification':
                modalTitle = 'Thêm Lịch Thi Sát Hạch';
                break;
            default:
                modalTitle = 'Thêm Mới Lịch';
                break;
        }
        $('#addActivitieModalLabel').text(modalTitle);

        // Cập nhật type và giao diện form
        typeInput.value = 'study';
        updateForm('study', calendarType);
    });

    // Hàm cập nhật giao diện form
    function updateForm(selectedType, calendarType) {
        document.querySelectorAll('#study-fields, #exam-fields').forEach(function(field) {
            field.style.display = 'none';
        });

        startTimeInput.value = '';
        endTimeInput.value = '';
        if (dateInput) dateInput.value = '';
        if (timeInput) timeInput.value = '';

        dateStartField.style.display = 'block';
        dateEndField.style.display = 'block';
        examDateTimeField.style.display = 'none';
        startTimeInput.disabled = false;
        endTimeInput.disabled = false;
        if (dateInput) dateInput.disabled = true;
        if (timeInput) timeInput.disabled = true;
        document.getElementById('study-fields').style.display = 'block';

        $('#learn_student_id_select').select2({
            dropdownParent: $('#addActivitieModal'),
            placeholder: "Chọn học viên",
            allowClear: true,
            multiple: true,
            width: '100%'
          }).on('change', function () {
            const selectedOptions = $(this).val() || [];
            const $container = $('.learn-container');

            $container.find('.student-input-group').each(function () {
              const sid = String($(this).data('student-id'));
              if (!selectedOptions.includes(sid)) {
                $(this).remove();
              }
            });
          
            selectedOptions.forEach(function (studentId) {
              studentId = String(studentId);

                
              if ($container.find(`.student-input-group[data-student-id="${studentId}"]`).length === 0) {
                const groupHtml = `
                  <div class="student-input-group border p-4 rounded bg-gray-50 mt-3" data-student-id="${studentId}">
                    <div class="row">
                      <div class="col-md-6">
                        <label for="km_${studentId}">Km</label>
                        <input type="number" id="km_${studentId}" class="form-control" min="0" name="km[${studentId}]" value="">
                      </div>
                      <div class="col-md-6">
                        <label for="hour_${studentId}">Số giờ chạy được</label>
                        <input type="text" id="hour_${studentId}" placeholder="HH:mm" class="form-control time-input" name="hour[${studentId}]" value="">
                      </div>
                    </div>
          
                    <div class="row mt-4">
                      <div class="col-md-6 d-flex align-items-center">
                        <input type="checkbox" class="form-check-input" id="learn_auto_${studentId}" name="learn_auto[${studentId}]">
                        <label class="ms-2 mb-0" for="learn_auto_${studentId}">Học tự động</label>
                      </div>
          
                      <div class="col-md-6 d-flex align-items-center">
                        <input type="checkbox" class="form-check-input" id="learn_night_${studentId}" name="learn_night[${studentId}]">
                        <label class="ms-2 mb-0" for="learn_night_${studentId}">Học ban đêm</label>
                      </div>
                    </div>
                  </div>
                `;
                $container.append(groupHtml);
                flatpickr(`#hour_${studentId}`, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true
                });
              }
            });
          });

        $('#vehicle_type_learn').select2({
            dropdownParent: $('#addActivitieModal'),
            placeholder: "Chọn loại hình thức học",
            allowClear: true,
            width: '100%'
        }).on('change', function () {
            const vehicleType = $(this).val();
            fetchAndUpdate(vehicleType, 'study', calendarType);
        });
    }

    // Hàm lấy dữ liệu môn học/môn thi, học viên
    function fetchAndUpdate(vehicleType, type, calendarType) {
        if (!vehicleType) {
            const subjectContainer = document.getElementById('learning_id');
            subjectContainer.innerHTML = '<p class="text-muted">-- Vui lòng hình thức học trước --</p>';
    
            const studentSelect = document.getElementById('learn_student_id_select');
            studentSelect.innerHTML = '';
            $(studentSelect).select2({
                dropdownParent: $('#addActivitieModal'),
                placeholder: "Chọn học viên",
                allowClear: true,
                multiple: true,
                width: '100%'
            });
            return;
        }
        const itemHidden = document.querySelectorAll('.form-calendar-learn-instructor');
        if(vehicleType == 1){
            itemHidden.forEach(e => {
                e.style.display = 'none';
            });
        } else {
            itemHidden.forEach(e => {
                e.style.display = 'block';
            });
        }
        let url = `/calendars/infor?vehicle_type=${vehicleType}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const subjectContainer = document.getElementById('learning_id');
                subjectContainer.innerHTML = '';
    
                let subjects = data.learning_fields;
                console.log(calendarType);
                
                if (calendarType === 'study_theory' || 
                    (calendarType === 'study_practice' && vehicleType === '1')) {
                    $('.vehicle_select_container').hide();
                    $('#edit_vehicle_select').prop('disabled', true);
                } else {
                    $('.vehicle_select_container').show();
                    $('#edit_vehicle_select').prop('disabled', false);
                }
                
                if (calendarType == 'study_theory' || calendarType == 'study_practice') {
                    if (vehicleType == 1) {
                        $('.learning_id').hide();
                        
                        subjects = [];
                    } else if (vehicleType == 2) {
                        $('.learning_id').show();
                        // Lọc môn học dựa theo loại kỳ thi
                        const isPractice = calendarType == 'study_practice';
                        subjects = subjects.filter(subject => subject.is_practical == (isPractice ? 1 : 0));
                    }
                }
                
                // Nếu còn môn thi
                // if (subjects.length > 0) {
                //     const selectAllDiv = document.createElement('div');
                //     selectAllDiv.classList.add('mb-2');
                //     selectAllDiv.innerHTML = `
                //         <label>
                //             <input type="checkbox" id="select_all_exams" /> Chọn tất cả
                //         </label>
                //     `;
                //     subjectContainer.appendChild(selectAllDiv);
                // }
    
                const rowWrapper = document.createElement('div');
                rowWrapper.classList.add('row');
                
                subjects.forEach(subject => {
                    const col = document.createElement('div');
                    col.classList.add('form-check', 'col-md-2', 'mb-2');
                    col.style.margin = '0 .63rem';
                    col.innerHTML = `
                        <input type="radio" name="learning_id" value="${subject.id}" class="form-check-input" id="edit_learning_${subject.id}">
                        <label for="learning_${subject.id}" class="form-check-label">${subject.name}</label>
                    `;
                    rowWrapper.appendChild(col);
                });
    
                subjectContainer.appendChild(rowWrapper);
    
                // Danh sách học viên
                const studentSelect = document.getElementById('learn_student_id_select');
                studentSelect.innerHTML = '';
                const availableStudents = data.students || [];
                availableStudents.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id;
                    option.textContent = student.label;
                    studentSelect.appendChild(option);
                });
                
                if ($.fn.select2 && $(studentSelect).hasClass('select2-hidden-accessible')) {
                    $(studentSelect).select2('destroy');
                }
                $(studentSelect).select2({
                    dropdownParent: $('#addActivitieModal'),
                    placeholder: "Chọn học viên khóa học",
                    allowClear: true,
                    multiple: true
                });
            })
            .catch(error => console.error('Error:', error));
        
    }
    
    // Hàm lấy danh sách xe
    function fetchAndUpdateVehicles(startTime, endTime) {
        if (!startTime || !endTime) return;
        if (new Date(endTime) < new Date(startTime)) {
            const vehicleSelect = document.getElementById('vehicle_select');
            vehicleSelect.innerHTML = '<option value="">Thời gian kết thúc không hợp lệ</option>';
        }
        
        const params = new URLSearchParams({ start_time: startTime, end_time: endTime });
        fetch(`/vehicles-available?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                const vehicleSelect = document.getElementById('edit_vehicle_select');
                vehicleSelect.innerHTML = '<option value="">Chọn xe</option>';
                if (data.vehicles && data.vehicles.length > 0) {
                    data.vehicles.forEach(vehicle => {
                        const option = document.createElement('option');
                        option.value = vehicle.id;
                        option.textContent = `${vehicle.license_plate} - ${vehicle.model}`;
                        vehicleSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Không có xe phù hợp';
                    vehicleSelect.appendChild(option);
                }
                if ($.fn.select2 && $('#edit_vehicle_select').hasClass('select2-hidden-accessible')) {
                    $('#edit_vehicle_select').select2('destroy');
                }
                $('#edit_vehicle_select').select2({
                    dropdownParent: $('#addActivitieModal')
                });
            })
            .catch(error => console.error('Error fetching vehicles:', error));
    }

    // Hàm lấy danh sách giáo viên
    function fetchAndUpdateUser(dateOrStartTime, timeOrEndTime, type) {
        if (!dateOrStartTime || !timeOrEndTime) return;
        const params = new URLSearchParams({
            type: type
        });
        params.append('edit_date_start', dateOrStartTime);
        params.append('edit_date_time', timeOrEndTime);

        fetch(`/users-available?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                const teacherSelect = document.getElementById(type === 'study' ? 'edit_learn_teacher_id' : 'edit_exam_teacher_id');
                if (teacherSelect) {
                    teacherSelect.innerHTML = '<option value="">Chọn giáo viên</option>';
                    (data.teachers || []).forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = user.name;
                        teacherSelect.appendChild(option);
                    });
                    $(`#${type === 'study' ? 'edit_learn_teacher_id' : 'edit_exam_teacher_id'}`).select2({
                        dropdownParent: $('#addActivitieModal'),
                        placeholder: "Chọn giáo viên",
                        allowClear: true,
                        width: '100%'
                    });
                }
            })
            .catch(error => console.error('Error fetching users:', error));
    }

    // Hàm xử lý thay đổi thời gian
    function formatToDateTimeLocal(dateTimeStr) {
        // Tách ngày và giờ
        const [datePart, timePart] = dateTimeStr.split(' '); // ["16/07/2025", "12:00"]
        const [day, month, year] = datePart.split('/');       // ["16", "07", "2025"]
        return `${year}-${month}-${day}T${timePart}`;
    }


    function handleTimeChange() {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        const vehicleType = $('#vehicle_type_learn').val();
        const formatStartTime = formatToDateTimeLocal(startTime)
        const formatEndTime = formatToDateTimeLocal(endTime)
        const studentInputGroup = document.querySelector('.student-input-group');
        if (studentInputGroup) {
            studentInputGroup.style.display = 'none';
        }

        const itemHidden = document.querySelectorAll('.form-calendar-learn-instructor');
        if(vehicleType == 1){
            itemHidden.forEach(e => {
                e.style.display = 'none';
            });
        } else {
            itemHidden.forEach(e => {
                e.style.display = 'block';
            });
        }
    
        fetchAndUpdate(vehicleType, 'study');
        fetchAndUpdateVehicles(formatStartTime, formatEndTime);
        fetchAndUpdateUser(startTime, endTime, 'study');
    }

    // Gắn sự kiện thay đổi
    if (dateInput) dateInput.addEventListener('change', handleTimeChange);
    if (timeInput) timeInput.addEventListener('change', handleTimeChange);
    startTimeInput.addEventListener('change', handleTimeChange);
    vehicleTypeSelect.addEventListener('change', handleTimeChange);
    endTimeInput.addEventListener('change', handleTimeChange);
});
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('.select2').select2({
            placeholder: "Chọn sân thi",
            allowClear: true
        });
    })
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let isModalActive = false;
    
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('show.bs.modal', () => isModalActive = true);
            modal.addEventListener('hidden.bs.modal', () => setTimeout(() => isModalActive = false, 300));
        });
    
        // Sự kiện click toggle
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
    
                const targetSelector = this.getAttribute('href') || this.dataset.bsTarget;
                const targetCollapse = document.querySelector(targetSelector);
                if (!targetCollapse) return;
    
                const rawDateId = targetSelector.replace('#collapse-', '');
                const currentMainRow = document.querySelector(`.calendar-main-row[data-date-id="${rawDateId}"]`);
                const isOpen = targetCollapse.classList.contains('show');
    
                const allMainRows = document.querySelectorAll('.calendar-main-row');
                const allCollapses = document.querySelectorAll('tr.collapse');
    
                if (!isOpen) {
                    // Mở mới: ẩn tất cả các collapse khác và row khác
                    allCollapses.forEach(el => {
                        if (el !== targetCollapse) {
                            bootstrap.Collapse.getOrCreateInstance(el, { toggle: false }).hide();
                        }
                    });
    
                    allMainRows.forEach(row => {
                        row.style.display = row === currentMainRow ? '' : 'none';
                    });
    
                    bootstrap.Collapse.getOrCreateInstance(targetCollapse, { toggle: false }).show();
                } else {
                    // Đóng hiện tại: collapse sẽ gọi sự kiện hidden để show lại rows
                    bootstrap.Collapse.getOrCreateInstance(targetCollapse, { toggle: false }).hide();
                }
            });
        });
    
        // Sau khi collapse đã đóng -> show lại tất cả các calendar-main-row
        document.querySelectorAll('tr.collapse').forEach(collapseRow => {
            collapseRow.addEventListener('hidden.bs.collapse', function () {
                document.querySelectorAll('.calendar-main-row').forEach(row => {
                    row.style.display = '';
                });
            });
        });
    
        // Click ra ngoài để đóng collapse và hiện lại tất cả rows
        document.addEventListener('click', function (e) {
            if (isModalActive) return;
    
            const isInsideToggle = e.target.closest('[data-bs-toggle="collapse"]');
            const isInsideCollapse = e.target.closest('.collapse');
            const isInsideCard = e.target.closest('#card');
    
            if (!isInsideToggle && !isInsideCollapse && !isInsideCard) {
                document.querySelectorAll('tr.collapse.show').forEach(el => {
                    bootstrap.Collapse.getOrCreateInstance(el, { toggle: false }).hide();
                });
    
                document.querySelectorAll('.calendar-main-row').forEach(row => {
                    row.style.display = '';
                });
            }
        });
    });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Chỉnh sửa form và dữ liệu khi thay đổi type
            const radios = document.querySelectorAll('input[name="type"]');
            const statusSelect = document.getElementById('status');
            
            const currentStatus = "{{ old('status', $calendar->status ?? '') }}";

            function selectedAttr(val) {
                return currentStatus == val ? 'selected' : '';
            }

            function updateForm(selectedType) {
                // Ẩn tất cả các trường
                document.querySelectorAll('#edit-study-fields, #exam-fields, #work-fields, #meeting-fields, #call-fields').forEach(function(field) {
                    field.style.display = 'none';
                });

                // Thêm options mới vào select status
                let options = '';
                if (selectedType === 'study') {
                    options = `
                        <option value="">Chọn trạng thái</option>
                        <option value="1" ${selectedAttr(1)}>Đang chờ</option>
                        <option value="2" ${selectedAttr(2)}>Đang diễn ra</option>
                        <option value="3" ${selectedAttr(3)}>Hoàn Thành</option>
                        <option value="4" ${selectedAttr(4)}>Đã hủy</option>
                    `;
                    document.getElementById('edit-study-fields').style.display = 'block';
                } else if (selectedType === 'exam') {
                    options = `
                        <option value="">Chọn trạng thái</option>
                        <option value="1" ${selectedAttr(1)}>Đang chờ</option>
                        <option value="2" ${selectedAttr(2)}>Đang diễn ra</option>
                        <option value="3" ${selectedAttr(3)}>Hoàn Thành</option>
                        <option value="4" ${selectedAttr(4)}>Thi lại</option>
                    `;
                    document.getElementById('exam-fields').style.display = 'block';
                } else if (selectedType === 'work') {
                    options = `
                        <option value="">Chọn trạng thái</option>
                        <option value="1" ${selectedAttr(1)}>Chưa bắt đầu</option>
                        <option value="2" ${selectedAttr(2)}>Đang tiến hành</option>
                        <option value="3" ${selectedAttr(3)}>Hoàn Thành</option>
                        <option value="4" ${selectedAttr(4)}>Hoãn lại</option>
                    `;
                    document.getElementById('work-fields').style.display = 'block';
                } else if (selectedType === 'meeting') {
                    options = `
                        <option value="">Chọn trạng thái</option>
                        <option value="1" ${selectedAttr(1)}>Đã lên lịch</option>
                        <option value="2" ${selectedAttr(2)}>Đang diễn ra</option>
                        <option value="3" ${selectedAttr(3)}>Hoàn Thành</option>
                        <option value="4" ${selectedAttr(4)}>Đã hủy</option>
                    `;
                    document.getElementById('meeting-fields').style.display = 'block';
                } else if (selectedType === 'call') {
                    options = `
                        <option value="">Chọn trạng thái</option>
                        <option value="1" ${selectedAttr(1)}>Đã lên kế hoạch</option>
                        <option value="2" ${selectedAttr(2)}>Đã thực hiện</option>
                        <option value="3" ${selectedAttr(3)}>Không thực hiện</option>
                    `;
                    document.getElementById('call-fields').style.display = 'block';
                }

                // Cập nhật lại select
                statusSelect.innerHTML = options;
            }

            // Gán sự kiện thay đổi radio
            radios.forEach(radio => {
                radio.addEventListener("change", function () {
                    updateForm(this.value);
                });
            });

            // Gọi khi load trang
            const checkedRadio = document.querySelector('input[name="type"]:checked');
            if (checkedRadio) {
                updateForm(checkedRadio.value);
            }

            document.querySelectorAll('.openResultModalBtn').forEach(button => {
                button.addEventListener('click', function () {
                    const calendarId = this.getAttribute('data-calendar-id');
                    const courseStudentId = this.getAttribute('data-student-id');
                    const type = this.getAttribute('data-type');
                    const resultForm = document.getElementById('resultForm');
                    resultForm.setAttribute('data-calendar-id', calendarId);
                    resultForm.setAttribute('data-course-student-id', courseStudentId);
                    
                    fetch(`/calendars/${calendarId}/info?course_student_id=${courseStudentId}`).then(response => response.json()).then(data => {
                        const container = document.getElementById('studentsResultFields');
                        const calendar = data.calendar;
                        const fieldLabel = calendar.type === 'study' ? 'Môn học' : 'Môn thi';
                        const start = new Date(calendar.date_start);
                        const end = new Date(calendar.date_end);
                        const startDate = start.toLocaleDateString('vi-VN');
                        const startTime = start.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
                        const endDate = end.toLocaleDateString('vi-VN');
                        const endTime = end.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
                        let fieldContent = '';
                        if (Array.isArray(calendar.field_name)) {
                            fieldContent = calendar.field_name.map(item => `<span>${item.name}</span>`).join(', ');
                        } else {
                            fieldContent = calendar.field_name;
                        }
                        container.innerHTML = '';
                        let fields = `
                            <table class="table table-bordered mb-3">
                                <tbody>
                                    <tr>
                                        <th>Tên lịch</th>
                                        <td>${calendar.name}</td>
                                    </tr>
                                `;
                        if (type == 'exam' || type == 'study') {
                            fields += `
                                    <tr>
                                        <th>${fieldLabel}:</th>
                                        <td>${fieldContent}</td>
                                    </tr>
                                `;
                        }

                            fields += `
                                    <tr>
                                        <th>Ngày bắt đầu</th>
                                        <td>${startDate}</td>
                                    </tr>
                                    <tr>
                                        <th>Ngày kết thúc</th>
                                        <td>${endDate}</td>
                                    </tr>
                                    <tr>
                                        <th>Thời gian</th>
                                        <td>${startTime} đến ${endTime}</td>
                                    </tr>
                                    <tr>
                                        <th>Thời lượng</th>
                                        <td>${calendar.duration} phút</td>
                                    </tr>
                                    <tr>
                                        <th>Địa điểm</th>
                                        <td>${calendar.location}</td>
                                    </tr>
                                </tbody>
                            </table>
                        `;
                        data.students.forEach(students => {
                            fields += `
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header fw-bold">
                                        <h6>Học viên: ${students.student_name} - ${students.student_code} - ${students.course_code}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                            `;

                            if (type === 'study') {
                                const currentStatus = "{{ old('status', $calendar->status ?? '') }}";
                                function selectedAttr(val) {
                                    return currentStatus == val ? 'selected' : '';
                                }
                                fields += `
                                <label>Giờ học: <input type="text" name="students[${students.student_id}][hours]" class="form-control" value="${students.study_results.hours ?? ''}"></label>
                                <label>Số km: <input type="text" step="0.001" name="students[${students.student_id}][km]" class="form-control currency-input" value="${students.study_results.km ?? ''}"></label>
                                <label>Giờ ban đêm: <input type="text" step="0.001" name="students[${students.student_id}][night_hours]" class="form-control currency-input" value="${students.study_results.night_hours ?? ''}"></label>
                                <label>Giờ xe tự động: <input type="text" step="0.001" name="students[${students.student_id}][auto_hours]" class="form-control currency-input" value="${students.study_results.auto_hours ?? ''}"></label>
                                <div class="col-md-6 mb-3">
                                        <div class="mb-2">
                                            <strong>Duyệt Km:</strong>
                                            <select name="status" class="form-control">
                                                <option value="0" ${selectedAttr(0)}> Chưa có kết quả</option>
                                                <option value="3" ${selectedAttr(3)}>Duyệt ngay</option>
                                                <option value="4" ${selectedAttr(4)}>Đã hủy</option>
                                                <option value="10" ${selectedAttr(10)}>Đã duyệt</option>
                                            </select>
                                    </div>
                                </div>
                                `;
                            } else if (type === 'exam') {
                                if (Array.isArray(data.calendar.field_name)) {
                                    data.calendar.field_name.forEach(field => {
                                        const examResult = (students.exam_results || []).find(r => r.exam_field_id === field.id);
                                        const examStatus = examResult?.exam_status ?? 0;
                                        const remarks = examResult?.remarks ?? '';
                                        fields += `
                                            <div class="col-md-6 mb-3">
                                                <div class="border p-3 rounded bg-light">
                                                    <strong>Môn thi: ${field.name}</strong>
                                                    <div class="mb-2">
                                                        <select name="students[${students.student_id}][${field.id}][exam_status]" class="form-control">
                                                            <option value="1" ${examStatus == 1 ? 'selected' : ''}>Đạt</option>
                                                            <option value="2" ${examStatus == 2 ? 'selected' : ''}>Trượt</option>
                                                            <option value="0" ${examStatus == 0 ? 'selected' : ''}>Chưa có kết quả</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="border p-3 rounded bg-light">
                                                    <label class="form-label fw-semibold">Nhận xét chung</label>
                                                    <input type="text" name="students[${students.student_id}][${field.id}][remarks]" class="form-control" value="${remarks}">
                                                </div>
                                            </div>
                                        `;
                                    });
                                }
                            }

                            if (type != 'exam') {
                                fields += `
                                            <div class="col-md-6 mb-3">
                                                <div class="border p-3 rounded bg-light">
                                                    <label class="form-label fw-semibold">Nhận xét chung</label>
                                                    <input type="text" name="students[${students.student_id}][remarks]" class="form-control" value="${students.study_results.remarks ?? ''}">
                                                </div>
                                            </div>
                                `;
                            }

                            fields += `
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                        });
                        container.insertAdjacentHTML('beforeend', fields);
                        new bootstrap.Modal(document.getElementById('resultModal')).show();
                    });
                });
            });

            document.getElementById('resultForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const calendarId = this.getAttribute('data-calendar-id');
                const courseStudentId = this.getAttribute('data-course-student-id');
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const formData = new FormData(this);
                
                fetch(`/calendars/${calendarId}/results?course_student_id=${courseStudentId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                    },
                    body: formData,
                }).then(res => res.json())
                .then(data => {
                    alert('Lưu kết quả thành công!');
                    bootstrap.Modal.getInstance(document.getElementById('resultModal')).hide();
                    location.reload();
                }).catch(err => {
                    alert('Đã xảy ra lỗi!');
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Chuyển tab mà giữ lại bộ lọc
            document.querySelectorAll("#calendarTypeTabs .nav-link").forEach(tab => {
                tab.addEventListener("click", function () {
                    let type = this.getAttribute("data-type"); // Lấy type từ tab được chọn
                    let url = new URL(window.location.href);
                    
                    url.searchParams.set("type", type); // Cập nhật type trong URL
                    
                    window.location.href = url.toString(); // Chuyển hướng đến URL mới
                });
            });
        });
    </script>
@endsection
