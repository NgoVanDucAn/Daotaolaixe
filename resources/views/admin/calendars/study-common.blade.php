@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="card-body">
        @php
            $baseQuery = request()->except('type', 'page');
        @endphp
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
                <input type="hidden" name="type" id="selectedType" value="{{ request('type', 'study') }}">
                <div class="col-6 col-md-3 mb-3">
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
                </div>

                <div class="col-12 col-md-3 mb-2">
                    <label class="form-label fw-bold">Tên học viên</label>
                    <input type="text" name="student_name" placeholder="Tên học viên" class="form-control mb-2" value="{{ request('student_name') }}">
                </div> --}}

                <div class="col-12 col-md-3 mb-3">
                    <label class="form-label fw-bold">Tên giáo viên</label>
                    <select name="user_name" id="teacher_id" class="form-select select2">
                        <option value="">Tên giáo viên</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
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

                <div class="col-6 col-md-3 mb-3 position-relative">
                    <label class="form-label fw-bold">Ngày bắt đầu</label>
                    <input 
                        type="text" 
                        placeholder="dd/mm/yyyy"
                        name="start_date" 
                        value="{{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '' }}"
                        class="form-control real-date" autocomplete="off" 
                    >
                </div>
                <div class="col-6 col-md-3 mb-3 position-relative">
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
                
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mb-1">
                        <b>Tìm Kiếm</b>
                    </button>
                    <div class="ms-2">
                        <a href="{{ route($removeFilter) }}" class="btn btn-outline-danger mb-1" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a
            {{-- href="{{ route('calendars.create') }}" --}}
            type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;"
            data-bs-toggle="modal"
            data-bs-target="#addActivitieModal"
            data-calendar-type="{{ $typeAccept }}"
            data-exam-course-type="{{ $courseType }}"
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Tạo lịch học mới</span>
        </a>
    </div>
@endsection

<div class="card" id="card">
    <div class="card-body">
        <a 
            {{-- href="{{ route('calendars.create') }}"  --}}
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 40px; height: 40px; font-size: 30px; bottom: 3%; right: 60px; z-index: 99;"
            {{-- data-bs-toggle="modal" data-bs-target="#addActivitieModal"  --}}
            data-bs-toggle="modal"
            data-bs-target="#addActivitieModal"
            data-calendar-type="{{ $typeAccept }}">
            +
        </a>

        {{-- Tab Content cho loại lịch --}}
        <div class="tab-content mt-3" id="calendarTypeTabsContent">
            @foreach($calendarTypes as $key => $label)
                <div class="tab-pane fade {{ $key == $type ? 'show active' : '' }}" id="tab-{{ $key }}" role="tabpanel" aria-labelledby="tab-{{ $key }}-tab">
                    <!-- Nếu người dùng chọn loại lịch này, sẽ hiển thị nội dung dưới đây -->
                    <div class="alert alert-primary d-flex align-items-center" role="alert">
                        <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-20 me-1"></iconify-icon>
                        <div class="lh-1"><strong>{{ $titleQuantity }}: </strong><strong>{{ $totalStudentCount }}</strong></div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Thứ</th>
                                    <th>Ngày</th>
                                    @if ($study == 'lt')
                                        <th>Môn học</th>
                                    @endif
                                    <th>Số lượng học viên</th>
                                </tr>
                                
                            </thead>
                            <tbody>
                                @foreach($paginatedCalendars as $date => $group)
                                @php
                                    $dateParts = explode('-', $date);
                                    if (count($dateParts) === 4) {
                                        $dateOnly = implode('-', array_slice($dateParts, 1));
                                    } else {
                                        $dateOnly = $date;
                                    }
                                @endphp
                                    <tr class="calendar-main-row" data-date-id="{{ \Carbon\Carbon::parse($dateOnly)->format('Ymd') }}">
                                        <td>{{ $loop->iteration }}</td>
                                        @php
                                            \Carbon\Carbon::setLocale('vi');
                                        @endphp

                                        <td>
                                            <a style="font-weight: 600; color: #4C9AFF" href="{{ route($detailRoute, ['start_date' => \Carbon\Carbon::parse($date)->format('Y-m-d')]) }}">
                                                {{ ucfirst(\Carbon\Carbon::parse($date)->translatedFormat('l')) }}
                                            </a>
                                        </td>

                                        <td>
                                            <a style="font-weight: 600; color: #4C9AFF" href="{{ route($detailRoute, ['start_date' => \Carbon\Carbon::parse($date)->format('Y-m-d')]) }}"
                                                >
                                                {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
                                            </a>
                                        </td>
                                        @if ($study == 'lt')
                                            @foreach($group['calendars'] as $calendar)
                                                <td>{{ optional($calendar->learningField)->name ?? '--' }}</td>
                                            @endforeach
                                        @endif
                                    
                                        
                                        <td>{{ $group['student_count'] }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Phân trang --}}
                    <div class="mt-3">
                        {{ $paginatedCalendars->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- modal tạo lịch --}}


<!-- Modal thêm mới lịch -->
@include('admin.calendars.add-calendar-modal')
@endsection
@section('js')
<script>
    $(document).ready(function() {
        $('#stadium_id').select2({
            placeholder: 'Chọn sân',
            width: '100%',
            allowClear: true
        });
        $('#edit_stadium_id').select2({
            placeholder: 'Chọn sân',
            width: '100%',
            allowClear: true
        });
        $('#select_stadium_id').select2({
            placeholder: 'Chọn sân',
            width: '100%',
            allowClear: true
        });
        $('#teacher_id').select2({
            placeholder: 'Chọn giáo viên',
            width: '100%',
            allowClear: true
        });
    });
</script>
<script src="{{ asset('assets/js/add-calendar-modal.js') }}"></script>
@endsection
