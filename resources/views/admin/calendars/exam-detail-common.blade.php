@extends('layouts.admin')

@section('content')

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route($back),
            'title' => "Quay về trang Lịch thi $description"

        ])
    </div>
@endsection
@php
    $firstDate = collect($paginatedCalendars->items())->keys()->first();
@endphp
@php
    function sumHHmm(...$times) {
        $total = 0;
        foreach ($times as $t) {
            if (empty($t)) continue;
            [$h, $m] = array_pad(explode(':', $t), 2, 0);
            $total += ((int)$h) * 60 + ((int)$m);
        }
        $h = floor($total / 60);
        $m = $total % 60;
        return sprintf('%02d:%02d', $h, $m);
    }
@endphp

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a
            {{-- href="{{ route('students.create') }}" --}}
            type="button"
            class="btn"
            data-bs-toggle="modal"
            data-bs-target="#addCalenderModal"
            data-date="{{ $firstDate }}"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;"
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Tạo học viên</span>
        </a>
    </div>
@endsection

<div class="card">
    <div class="card-body">
        <div class="filter-options mb-3">
            <form id="calendarFilterForm" class="row" method="GET">
                <input type="hidden" name="type" id="selectedType" value="{{ request('type', 'study') }}">
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

                {{-- <div class="col-12 col-md-3 mb-2">
                    <label class="form-label fw-bold">Tên giáo viên</label>
                    <input type="text" name="user_name" placeholder="Tên giáo viên, quản lý,..." class="form-control mb-2" value="{{ request('user_name') }}">
                </div> --}}

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

                {{-- <div class="col-12 col-md-3 mb-2">
                    <button type="submit" class="btn btn-primary mb-1">Lọc</button>
                    <a href="{{ route($removeFilter, ['type' => $type]) }}" class="btn btn-secondary mb-1" id="clearFilters">Bỏ Lọc</a>
                </div> --}}
                <div class="col-12 col-lg-3 col-md-4 mb-2">
                    <label for="">&nbsp;</label>
                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mb-1">
                            <b>Tìm Kiếm</b>
                        </button>
                        <div class="ms-2">
                            <a href="{{ route($removeFilter, ['type' => $type]) }}" class="btn btn-outline-danger mb-1" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card" id="card">
    <div class="card-body">
        {{-- <a
        href="{{ route('calendars.create') }}"
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 40px; height: 40px; font-size: 30px; bottom: 3%; right: 60px; z-index: 99;"data-bs-toggle="modal" data-bs-target="#addActivitieModal"
            data-calendar-type="{{ $typeAccept }}"
            data-exam-course-type="{{ $courseType }}">
            <i class="mdi mdi-plus"></i>
        </a> --}}
        {{-- Tab Content cho loại lịch --}}
        <div class="tab-content mt-3" id="calendarTypeTabsContent">
        <!-- Nếu người dùng chọn loại lịch này, sẽ hiển thị nội dung dưới đây -->
            <h4 class="text-lg font-bold mt-6">
                Lịch thi {{ $description }}
            </h4>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" class="align-middle">STT</th>
                            <th rowspan="2" class="align-middle">Buổi thi</th>
                            <th rowspan="2" class="align-middle">Lần thi</th>
                            <th rowspan="2" class="align-middle">Học viên</th>
                            <th rowspan="2" class="align-middle" style="width: 100px">Ngày sinh</th>
                            {{-- @if ($exam != 'sh') --}}
                                <th rowspan="2" class="align-middle">CCCD</th>
                            {{-- @endif --}}

                            <th rowspan="2" class="align-middle">SĐT</th>
                            <th rowspan="2" class="align-middle">SBD</th>
                            <th rowspan="2" class="align-middle">Khóa học</th>
                            <th rowspan="2" class="align-middle">Môn thi</th>
                            <th rowspan="2" class="align-middle">Khám SK</th>
                            <th rowspan="2" class="align-middle">Sân</th>
                            <th rowspan="2" class="align-middle">Đưa đón</th>
                            <th rowspan="2" class="align-middle">Kết quả</th>
                            @if ($exam == 'sh')
                                <th colspan="3" class="align-middle">Xe chip</th>
                            @endif
                            <th rowspan="2" class="align-middle" style="width: 150px">Ghi chú</th>
                            <th rowspan="2" class="align-middle">Hành động</th>
                        </tr>
                        @if ($exam == 'sh')
                            <tr>
                                <th class="align-middle" style="min-width: 100px">Giờ tặng</th>
                                <th class="align-middle" style="min-width: 100px">Giờ đăng ký</th>
                                <th class="align-middle" style="min-width: 100px">Tổng giờ</th>
                            </tr>
                        @endif
                    </thead>
                    <tbody>
                    @php $counter = 1; @endphp

                    @foreach($paginatedCalendars as $date => $group)
                        @foreach($group['calendars_by_time'] as $timeKey => $calendarGroup)
                            @php
                                $session = $timeMap[$timeKey] ?? ($timeKey == 1 ? 'Buổi sáng' : ($timeKey == 2 ? 'Buổi chiều' : 'Cả ngày'));
                                $badgeClass = match($session) {
                                  'Buổi sáng' => 'bg-primary',
                                  'Buổi chiều' => 'bg-warning text-dark',
                                  'Cả ngày'   => 'bg-success',
                                  default     => 'bg-secondary'
                                };
                            @endphp

                            @foreach ($calendarGroup['calendars_by_stadium'] as $stadiumGroup)
                                @foreach ($stadiumGroup['calendars'] as $calendar)

                                    @php
                                        $courseStudents = $calendar->courseStudents;
                                        //@dd($courseStudents);
                                        $rowspan = max(1, $courseStudents->count());

                                        $firstStudent = $calendar['calendarStudents'][0] ?? null;
                                        $attemptNumber = $firstStudent && !empty($firstStudent['examFields'])
                                            ? ($firstStudent['examFields'][0]['attempt_number'] ?? '--')
                                            : '--';

                                        $statuses = collect($calendar['calendarStudents'] ?? [])
                                            ->pluck('examFields')->flatten()
                                            ->pluck('exam_all_status')->unique()->toArray();
                                    @endphp

                                    @forelse ($courseStudents as $i => $cs)
                                        <tr>
                                            {{-- Các cột dùng chung: chỉ in ở hàng đầu và rowspan --}}
                                            @if($i === 0)
                                                <td rowspan="{{ $rowspan }}">{{ $counter++ }}</td>
                                                <td rowspan="{{ $rowspan }}"><span class="badge {{ $badgeClass }}">{{ $session }}</span></td>
                                                <td rowspan="{{ $rowspan }}"><span class="badge bg-secondary">Lần {{ $attemptNumber }}</span></td>
                                            @endif

                                            {{-- Thông tin học viên (1 hàng / 1 học viên) --}}
                                            <td class="text-nowrap">{{ $cs->student->name }}</td>
                                            <td class="text-nowrap">{{ !empty($cs->student->dob) ? \Carbon\Carbon::parse($cs->student->dob)->format('d-m-Y') : '--' }}</td>
                                            <td>{{ $cs->student->identity_card ?? '--' }}</td>
                                            <td>{{ $cs->student->phone ?? '--' }}</td>
                                            <td>{{ $cs->pivot->exam_number ?? '--' }}</td>

                                            @if($i === 0)
                                                <td rowspan="{{ $rowspan }}" class="text-nowrap">
                                                    <span class="badge bg-success">{{ $cs->course->code ?? '--' }}</span>
                                                </td>
                                                <td rowspan="{{ $rowspan }}">
                                                    @if(!empty($calendar['exam_fields_of_calendar']))
                                                        @foreach($calendar['exam_fields_of_calendar'] as $field)
                                                            <span class="badge bg-success">{{ $field->name ?? '--' }}</span>
                                                        @endforeach
                                                    @else
                                                        <p class="mb-0">Không có dữ liệu môn thi</p>
                                                    @endif
                                                </td>
                                            @endif

                                            <td class="text-nowrap">
                                                {{ !empty($cs->health_check_date) ? \Carbon\Carbon::parse($cs->health_check_date)->format('d-m-Y') : '--' }}
                                            </td>

                                            @if($i === 0)
                                                <td rowspan="{{ $rowspan }}" class="text-nowrap">
                                                    <a href="{{ route('stadiums.index') }}">{{ $calendar->stadium->location ?? '--' }}</a>
                                                </td>
                                            @endif

                                            <td>
                                                @if (!empty($cs->pivot->pickup) && $cs->pivot->pickup == 1)
                                                    <i class="mdi mdi-check-circle-outline" style="color: green"></i>
                                                @else
                                                    <i class="mdi mdi-close-circle-outline" style="color: red"></i>
                                                @endif
                                            </td>

                                            @if($i === 0)
                                                <td rowspan="{{ $rowspan }}">
                                                    @foreach ($statuses as $status)
                                                        @if ($status === 1)
                                                            <span class="badge bg-success">Đỗ</span>
                                                        @elseif ($status === 2)
                                                            <span class="badge bg-danger">Trượt</span>
                                                        @else
                                                            <span class="badge bg-secondary">Chưa có kết quả</span>
                                                        @endif
                                                    @endforeach
                                                </td>

                                                {{-- 3 CỘT XE CHIP — MỖI SV 1 DÒNG, KHÔNG ROWSPAN --}}
                                                @if ($exam == 'sh')
                                                    <td>{{ $cs->give_chip_hour  ?? '00:00' }}</td>
                                                    <td>{{ $cs->order_chip_hour ?? '00:00' }}</td>
                                                    <td>{{ sumHHmm($cs->give_chip_hour, $cs->order_chip_hour) }}</td>
                                                @endif



                                                <td rowspan="{{ $rowspan }}">{{ $calendar['description'] ?? '--' }}</td>
                                            @endif

                                            {{-- Hành động theo từng học viên --}}
                                            <td>
                                                <div class="d-flex gap-2 align-items-center">
{{--                                                    <a href="#"--}}
{{--                                                       class="btn btn-sm btn-warning editExamBtn"--}}
{{--                                                       data-bs-toggle="modal"--}}
{{--                                                       data-bs-target="#examEditModal"--}}
{{--                                                       data-calendar-id="{{ $calendar->id }}"--}}
{{--                                                       data-time="{{ $calendar->time }}"--}}
{{--                                                       data-lanthi="{{ $attemptNumber }}"--}}
{{--                                                       data-course-id="{{ $calendar->courses[0]['id'] ?? '' }}"--}}
{{--                                                       data-course-type="{{ $calendar->exam_course_type }}"--}}
{{--                                                       data-status="{{ $calendar->status }}"--}}
{{--                                                       data-description="{{ $calendar->description }}"--}}
{{--                                                       data-stadium="{{ $calendar->stadium?->id }}"--}}
{{--                                                       data-students='@json([$cs->id])'--}}
{{--                                                    >--}}
{{--                                                        <i class="fa-solid fa-pen-to-square"></i>--}}
{{--                                                    </a>--}}

{{--                                                    @if (!in_array($calendar['status'], [4, 10]) && now()->lt(\Carbon\Carbon::parse($calendar['date_start'])))--}}
                                                        <a href="{{ route('calendars.edit', $calendar['id']) }}" class="btn btn-primary btn-sm">
                                                            <i class="fa-solid fa-user-pen" title="Chỉnh sửa"></i>
                                                        </a>
{{--                                                    @endif--}}

                                                    <button class="btn btn-sm btn-info openResultModalBtn"
                                                            data-calendar-id="{{ $calendar['id'] }}"
                                                            data-student-id="{{ $cs->id }}"
                                                            data-type="{{ $calendar['type'] }}">
                                                        <i class="fa-solid fa-file-pen" title="Nhập kết quả"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        {{-- Lịch không có học viên --}}
                                        <tr>
                                            <td>{{ $counter++ }}</td>
                                            <td><span class="badge {{ $badgeClass }}">{{ $session }}</span></td>
                                            <td><span class="badge bg-secondary">Lần {{ $attemptNumber }}</span></td>
                                            <td colspan="13" class="text-muted">Không có học viên</td>
                                        </tr>
                                    @endforelse

                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Phân trang riêng cho từng bảng --}}
            {{-- <div class="mb-8">
                @if($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $paginator->appends(request()->except("page_{$level}"))->links('pagination::bootstrap-5') }}
                @endif
            </div> --}}
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

    {{-- modal add --}}
    <div class="modal fade" id="addCalenderModal" tabindex="-1" aria-labelledby="addCalenderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCalenderModalLabel">Thêm Mới Lịch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form id="formAddCalender" action="{{ route('calendars.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" id="type" value="">
                        <div class="row">
                            <div class="col-12 ">
                                <div class="row">
                                    {{-- Nếu là Lịch thi --}}
                                    <div id="exam-fields" class="mb-4">
                                        <div class="row g-3">
                                            <div class="col-12 col-lg-8">
                                                <input type="hidden" name="exam_course_type" id="exam_course_type" value="">
                                                <div class="row g-3" id="exam-date-time">
                                                        {{-- <label for="date" class="form-label">Chọn ngày <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Trường bắt buộc" style="cursor: pointer;">*</span></label> --}}
                                                        <input type="hidden" name="date" id="date" value="" />

                                                    <div class="form-group col-12 col-md-6">
                                                        <label for="time" class="form-label">Chọn thời gian <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Trường bắt buộc" style="cursor: pointer;">*</span></label>
                                                        <select name="time" id="time" class="form-control @error('time') is-invalid @enderror">
                                                            <option value="">Chọn thời gian</option>
                                                            <option value="1">Buổi sáng</option>
                                                            <option value="2">Buổi chiều</option>
                                                            <option value="3">Cả ngày</option>
                                                        </select>
                                                        @error('time')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-12 col-md-6">
                                                        <label for="lanthi" class="form-label">Lần thi <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Trường bắt buộc" style="cursor: pointer;">*</span></label>
                                                        <select name="lanthi" id="lanthi" class="form-control">
                                                            @foreach(range(1,100) as $i)
                                                                <option value="{{ $i }}">Lần {{ $i }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Hàng 2 --}}
                                                <div class="row g-3">
                                                    <div class="form-group mb-3 col-12 col-md-12">
                                                        <label for="exam_course_id" class="form-label">Khóa học</label>
                                                        <select name="exam_course_id" id="exam_course_id" class="form-control @error('exam_course_id') is-invalid @enderror">
                                                            <option value="">Chọn khóa học</option>
                                                        </select>
                                                        @error('exam_course_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group mb-3 col-12 col-md-12">
                                                        <label for="exam_id" class="form-label">Môn thi
                                                            <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Cần chọn khóa học trước" style="cursor: pointer;">&#x3f;</span>
                                                        </label>
                                                        <div id="exam_id">

                                                        </div>
                                                        @error('exam_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                {{-- Hàng 3 --}}
                                                <div class="row g-3">
                                                    <div class="form-group col-12">
                                                        <label for="exam_schedule_id" class="form-label">Sân thi <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Chọn thời gian bắt đầu, kết thúc, khóa học và môn học trước" style="cursor: pointer;">&#x3f;</span></label>
                                                        <select name="exam_schedule_id" id="exam_schedule_id" class="form-select">
                                                            <option value="">-- Vui lòng chọn thời gian bắt đầu, kết thúc, khóa học và môn học trước --</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                {{-- Hàng 4 --}}
                                                <div class="row g-3">
                                                    <div class="form-group col-12">
                                                        <label for="status" class="form-label mt-2">Trạng thái</label>
                                                        <select name="status" id="status_id" class="form-select">
                                                            <option value="">Chọn trạng thái</option>
                                                            <option value="1">Đang chờ</option>
                                                            <option value="2">Đỗ</option>
                                                            <option value="3">Trượt</option>
                                                            <option value="4">Thi lại</option>
                                                            <option value="5">Thi mới</option>
                                                            <option value="6">Bỏ thi</option>
                                                            <option value="7">Hoãn lại</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="form-group mb-3 col-12">
                                                        <label for="description" class="form-label">Mô Tả</label>
                                                        <textarea name="description" id="description" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-4">
                                                <div class="form-group mb-3">
                                                    <label for="exam_student_id" class="form-label">Học viên
                                                        <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Cần chọn thời gian và khóa học trước" style="cursor: pointer;">&#x3f;</span>
                                                    </label>
                                                    <select name="exam_student_id[]" id="exam_student_id_select" class="form-control @error('exam_student_id') is-invalid @enderror" multiple>
                                                        <option value="">Vui lòng chọn khóa học, thời gian của lịch trước</option>
                                                    </select>
                                                    @error('exam_student_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div id="alert-message" class="alert alert-danger d-none"></div>

                                                <div id="student-inputs" class="mb-3 space-y-4"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-2"></i> <span>Hủy</span>
                    </button>
                    <button type="submit" class="btn btn-primary" form="formAddCalender">
                        <i class="mdi mdi-content-save-outline me-2"></i> <span>Lưu</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="examEditModal" tabindex="-1" aria-labelledby="examEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="examEditModalLabel">Chỉnh Sửa Lịch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formExamEditModal" action="{{ route('calendars.store') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" id="type" value="">
                            <div class="row">
                                <div class="col-12 ">
                                    <div class="row">
                                        {{-- Nếu là Lịch thi --}}
                                        <div id="exam-fields" class="mb-4">
                                            <div class="row g-3">
                                                <div class="col-12 col-lg-8">
                                                    <input type="hidden" name="exam_course_type" id="edit_exam_course_type" value="">
                                                    <div class="row g-3" id="edit_exam-date-time">
                                                        <input type="hidden" class="date" name="date" value="" />
                                                        <div class="form-group col-12 col-md-6">
                                                            <label for="time" class="form-label">Chọn thời gian <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Trường bắt buộc" style="cursor: pointer;">*</span></label>
                                                            <select name="time" id="edit_time" class="form-control @error('time') is-invalid @enderror">
                                                                <option value="">Chọn thời gian</option>
                                                                <option value="1">Buổi sáng</option>
                                                                <option value="2">Buổi chiều</option>
                                                                <option value="3">Cả ngày</option>
                                                            </select>
                                                            @error('time')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group col-12 col-md-6">
                                                            <label for="lanthi" class="form-label">Lần thi <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Trường bắt buộc" style="cursor: pointer;">*</span></label>
                                                            <select name="lanthi" id="edit_lanthi" class="form-control">
                                                                @foreach(range(1,100) as $i)
                                                                    <option value="{{ $i }}">Lần {{ $i }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- Hàng 2 --}}
                                                    <div class="row g-3">
                                                        <div class="form-group mb-3 col-12 col-md-12">
                                                            <label for="exam_course_id" class="form-label">Khóa học</label>
                                                            <select name="exam_course_id" id="edit_exam_course_id" class="form-control @error('exam_course_id') is-invalid @enderror">
                                                                <option value="">Chọn khóa học</option>
                                                            </select>
                                                            @error('exam_course_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group mb-3 col-12 col-md-12">
                                                            <label for="exam_id" class="form-label">Môn thi
                                                                <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Cần chọn khóa học trước" style="cursor: pointer;">&#x3f;</span>
                                                            </label>
                                                            <div id="edit_exam_id">
                                                            </div>
                                                            @error('exam_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    {{-- Hàng 3 --}}
                                                    <div class="row g-3">
                                                        <div class="form-group col-12">
                                                            <label for="exam_schedule_id" class="form-label">Sân thi <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Chọn thời gian bắt đầu, kết thúc, khóa học và môn học trước" style="cursor: pointer;">&#x3f;</span></label>
                                                            <select name="exam_schedule_id" id="edit_exam_schedule_id" class="form-select">
                                                                <option value="">-- Vui lòng chọn thời gian bắt đầu, kết thúc, khóa học và môn học trước --</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    {{-- Hàng 4 --}}
                                                    <div class="row g-3">
                                                        <div class="form-group col-12">
                                                            <label for="status" class="form-label mt-2">Trạng thái</label>
                                                            <select name="status" id="edit_status_id" class="form-select">
                                                                <option value="">Chọn trạng thái</option>
                                                                <option value="1">Đang chờ</option>
                                                                <option value="2">Đỗ</option>
                                                                <option value="3">Trượt</option>
                                                                <option value="4">Thi lại</option>
                                                                <option value="5">Thi mới</option>
                                                                <option value="6">Bỏ thi</option>
                                                                <option value="7">Hoãn lại</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="form-group mb-3 col-12">
                                                            <label for="description" class="form-label">Mô Tả</label>
                                                            <textarea name="description" id="edit_description" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group mb-3">
                                                        <label for="exam_student_id" class="form-label">Học viên
                                                            <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Cần chọn thời gian và khóa học trước" style="cursor: pointer;">&#x3f;</span>
                                                        </label>
                                                        <select name="exam_student_id[]" id="edit_exam_student_id_select" class="form-control @error('exam_student_id') is-invalid @enderror" multiple>
                                                            <option value="">Vui lòng chọn khóa học, thời gian của lịch trước</option>
                                                        </select>
                                                        @error('exam_student_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div id="alert-message" class="alert alert-danger d-none"></div>

                                                    <div id="student-inputs" class="mb-3 space-y-4"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-2"></i> <span>Hủy</span>
                    </button>
                    <button type="submit" class="btn btn-primary" form="formExamEditModal">
                        <i class="mdi mdi-content-save-outline me-2"></i> <span>Lưu</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('js')
{{-- add --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    const examCourseSelect = document.getElementById('exam_course_id');
    const examDateTimeField = document.getElementById('exam-date-time');
    const typeInput = document.getElementById('type');
    const examCourseTypeInput = document.getElementById('exam_course_type');

    // Reset modal khi đóng (chỉ phần liên quan đến thi)
    $('#addCalenderModal').on('hidden.bs.modal', function () {
        var modalForm = $(this).find('form');
        modalForm[0].reset();
        $('#exam_course_id').val('').trigger('change');
        $('#exam_student_id_select').val('').trigger('change');
        $('#exam_teacher_id').val('').trigger('change');
        $('#exam_student_id_select').empty();
        $('#exam_course_id').empty().append(new Option('-- Chọn khóa học --', ''));
        $('#exam_teacher_id').empty().append(new Option('Chọn giáo viên', ''));
        $('#student-inputs').empty();
        $('#alert-message').addClass('d-none');
        typeInput.value = '';
        if (examCourseTypeInput) examCourseTypeInput.value = '';
    });

    let date = ''
    // Khởi tạo modal khi mở (chỉ phần liên quan đến thi)
    $('#addCalenderModal').on('show.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget);
        var calendarType = button.data('calendar-type');
        var examCourseType = button.data('exam-course-type');
        if (examCourseTypeInput) examCourseTypeInput.value = examCourseType;
        date = button.data('date');
        updateForm('exam');
        fetchCourses(examCourseSelect);

    });

    // Hàm cập nhật giao diện form cho thi
    function updateForm(selectedType) {
        if (selectedType !== 'exam') return;
        examDateTimeField.style.display = 'flex';
        if (timeInput) timeInput.disabled = false;
        document.getElementById('exam-fields').style.display = 'block';

        $('#exam_course_id').select2({
            dropdownParent: $('#addCalenderModal'),
            placeholder: "Chọn khóa học",
            allowClear: true,
            width: '100%'
        }).on('change', function () {
            const time = timeInput ? timeInput.value : '';
            const courseId = $(this).val();
            const examCourseType = examCourseTypeInput ? examCourseTypeInput.value : '';
            fetchAndUpdate(courseId, 'exam', date, time, examCourseType);
        });

        $('#exam_student_id_select').select2({
            dropdownParent: $('#addCalenderModal'),
            placeholder: "Chọn học viên",
            allowClear: true,
            multiple: true,
            width: '100%'
        }).on('change', function () {
            const selectedOptions = $(this).val() || [];
            const $container = $('#student-inputs');
            $container.find('.student-input-group').each(function () {
                const studentId = $(this).data('student-id');
                if (!selectedOptions.includes(studentId.toString())) {
                    $(this).remove();
                }
            });

            selectedOptions.forEach(function (studentId) {
                if ($container.find(`.student-input-group[data-student-id="${studentId}"]`).length === 0) {
                    const studentName = $(`#exam_student_id_select option[value="${studentId}"]`).text();
                    const groupHtml = `
                        <div class="student-input-group border p-4 rounded bg-gray-50" data-student-id="${studentId}">
                            <div class="mb-2 font-semibold">${studentName}</div>
                            <div class="mb-2">
                                <label class="block text-sm mb-1">Số báo danh:</label>
                                <input style="width: 100%;" type="text" name="students[${studentId}][exam_number]" class="w-full rounded border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm mb-1">
                                    <input type="checkbox" name="students[${studentId}][pickup]" value="1" class="mr-1">
                                    Đăng ký đưa đón
                                </label>
                            </div>
                        </div>
                    `;
                    $container.append(groupHtml);
                }
            });
        });
    }

    // Hàm lấy danh sách khóa học
    function fetchCourses(selectElement) {
        fetch('/courses-alls')
            .then(response => response.json())
            .then(data => {
                selectElement.innerHTML = '<option value="">Chọn khóa học</option>';
                data.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = course.code;
                    selectElement.appendChild(option);
                });
                $(selectElement).select2({
                    dropdownParent: $('#addCalenderModal'),
                    placeholder: "Chọn khóa học",
                    allowClear: true,
                    width: '100%'
                });
            })
            .catch(error => console.error('Error fetching courses:', error));
    }

    // Hàm lấy dữ liệu môn thi và học viên
    function fetchAndUpdate(courseId, type, dateStart, dateEnd, holdType) {
        if (type !== 'exam') return;
        if (!courseId) {
            const subjectContainer = document.getElementById('exam_id');
            subjectContainer.innerHTML = '<p class="text-muted">-- Vui lòng chọn khóa học trước --</p>';
            const studentSelect = document.getElementById('exam_student_id_select');
            studentSelect.innerHTML = '';
            $(studentSelect).select2({
                dropdownParent: $('#addCalenderModal'),
                placeholder: "Chọn học viên",
                allowClear: true,
                multiple: true,
                width: '100%'
            });
            return;
        }

        let url = `/course-data/exam/${courseId}?date=${encodeURIComponent(dateStart)}&time=${encodeURIComponent(dateEnd)}&examType=${encodeURIComponent(holdType)}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const subjectContainer = document.getElementById('exam_id');
                subjectContainer.innerHTML = '';
                const subjects = data.course.exam_fields || [];
                if (subjects.length > 0) {
                    const selectAllDiv = document.createElement('div');
                    selectAllDiv.classList.add('mb-2');
                    selectAllDiv.innerHTML = `
                        <label>
                            <input type="checkbox" id="select_all_exams" /> Chọn tất cả
                        </label>
                    `;
                    subjectContainer.appendChild(selectAllDiv);
                }

                const rowWrapper = document.createElement('div');
                rowWrapper.classList.add('row');
                subjects.forEach(subject => {
                    const col = document.createElement('div');
                    col.classList.add('form-check', 'col-md-2', 'mb-2');
                    col.style.margin = '0 .63rem';
                    col.innerHTML = `
                        <input type="checkbox" name="exam_id[]" value="${subject.id}" class="form-check-input exam-checkbox" id="exam_${subject.id}">
                        <label for="exam_${subject.id}" class="form-check-label">${subject.name}</label>
                    `;
                    rowWrapper.appendChild(col);
                });
                subjectContainer.appendChild(rowWrapper);

                const selectAll = document.getElementById('select_all_exams');
                if (selectAll) {
                    selectAll.addEventListener('change', function () {
                        document.querySelectorAll('.exam-checkbox').forEach(cb => {
                            cb.checked = this.checked;
                        });
                    });
                }

                const studentSelect = document.getElementById('exam_student_id_select');
                studentSelect.innerHTML = '';
                const availableStudents = data.available_students || [];
                availableStudents.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id;
                    option.textContent = `${student.name} - ${student.student_code}`;
                    studentSelect.appendChild(option);
                });

                if ($.fn.select2 && $(studentSelect).hasClass('select2-hidden-accessible')) {
                    $(studentSelect).select2('destroy');
                }
                $(studentSelect).select2({
                    dropdownParent: $('#addCalenderModal'),
                    placeholder: "Chọn học viên",
                    allowClear: true,
                    multiple: true
                });
            })
            .catch(error => console.error('Error:', error));
    }

    // Hàm lấy danh sách sân thi
    function fetchExamSchedules(courseId, examFieldIds, date, time) {
        if (!courseId || !examFieldIds) return;
        const params = new URLSearchParams({
            course_id: courseId,
            date: date,
            time: time
        });
        if (Array.isArray(examFieldIds)) {
            examFieldIds.forEach(id => params.append('exam_field_ids[]', id));
        } else {
            params.append('exam_field_ids[]', examFieldIds);
        }

        fetch(`/exam-schedules-available?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                const examScheduleSelect = document.getElementById('exam_schedule_id');
                examScheduleSelect.innerHTML = '<option value="">-- Chọn sân thi --</option>';
                data.exam_schedules.forEach(schedule => {
                    const option = document.createElement('option');
                    const location = schedule.stadium?.location || 'Không rõ địa điểm';
                    option.value = schedule;
                    option.textContent = `${location} (${schedule.start_time} → ${schedule.end_time})`;
                    examScheduleSelect.appendChild(option);
                });
                $('#exam_schedule_id').select2({
                    dropdownParent: $('#addCalenderModal')
                });
            })
            .catch(error => console.error('Error:', error));
    }

    // Hàm xử lý thay đổi thời gian
    function handleTimeChange() {
        const time = timeInput ? timeInput.value : '';
        const courseId = $('#exam_course_id').val();
        const examCourseType = examCourseTypeInput ? examCourseTypeInput.value : '';
        if (date && time) {
            fetchAndUpdate(courseId, 'exam', date, time, examCourseType);
        }
    }

    // Hàm xử lý thay đổi thời gian và môn thi để lấy sân thi
    function handleTimeChangeSchedules() {
        const time = timeInput ? timeInput.value : '';
        const courseId = $('#exam_course_id').val();
        const examFieldIds = Array.from(document.querySelectorAll('.exam-checkbox:checked')).map(cb => cb.value);

        if (date && time && courseId && examFieldIds) {
            fetchExamSchedules(courseId, examFieldIds, date, time);
        }
    }

    // Gắn sự kiện thay đổi

    if (timeInput) timeInput.addEventListener('change', handleTimeChange);
    examCourseSelect.addEventListener('change', handleTimeChange);
    examCourseTypeInput.addEventListener('change', handleTimeChange);
    $(document).on('change', '.exam-checkbox', function () {
        handleTimeChangeSchedules();
    });
});
</script>
{{-- edit --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const editCourseSelect = document.getElementById('edit_exam_course_id');
        const editCourseTypeInput = document.getElementById('edit_exam_course_type');
        const editStudentSelect = $('#edit_exam_student_id_select');
        const editExamFieldContainer = document.getElementById('edit_exam_id');
        const timeInput = document.getElementById('edit_time');
        let date = ''
        let time = ''
        let selectedExamIds = []
        let stadiumId = ''
        $('#examEditModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const modal = $(this);

            // Lấy dữ liệu từ data-*
            const calendarId = button.data('calendar-id');
            time = button.data('time');
            const lanthi = button.data('lanthi');
            const courseId = button.data('course-id');
            const courseType = button.data('course-type');
            const status = button.data('status');
            const description = button.data('description');
            const studentIds = button.data('students') || [];
            const examFieldData = button.data('exam-field-data') || [];
            stadiumId = button.data('stadium');
            selectedExamIds = Array.isArray(examFieldData)
                ? examFieldData.map(sub => sub.id)
                : [];
            date = button.data('date');

            // Gán dữ liệu vào form
            modal.find('input[name="calendar_id"]').val(calendarId);
            $('#edit_time').val(time);
            $('#edit_lanthi').val(lanthi);
            $('#edit_exam_course_id').val(courseId).trigger('change');
            $('#edit_exam_course_type').val(courseType);
            $('#edit_status_id').val(status);
            $('#edit_description').val(description);

            // Gọi lại danh sách môn thi và học viên
            editFetchCourses(editCourseSelect, courseId, function () {
                const timeVal = timeInput.value;
                const examCourseType = editCourseTypeInput.value;
                // editFetchAndUpdate(courseId, 'exam', '', timeVal, examCourseType, '#edit_exam_id', '#edit_exam_student_id_select', '');
                editFetchAndUpdate(courseId, 'exam', '', timeVal, examCourseType, '#edit_exam_id', '#edit_exam_student_id_select', studentIds, selectedExamIds );

            });
            $('#edit_exam_student_id_select').val(studentIds).trigger('change');

        });


        $('#edit_exam_course_id').select2().on('change', function () {
            const courseId = $('#edit_exam_course_id').val();
            const examCourseType = editCourseTypeInput.value;
            editFetchAndUpdate(courseId, 'exam', date, time, examCourseType, '#edit_exam_id', '#edit_exam_student_id_select', '');
        });

        // Gọi lại fetchCourses nhưng cho edit
        function editFetchCourses(selectElement, selectedValue = '', callback = null) {
            fetch('/courses-alls')
                .then(response => response.json())
                .then(data => {
                    selectElement.innerHTML = '<option value="">Chọn khóa học</option>';
                    data.forEach(course => {
                        const option = document.createElement('option');
                        option.value = course.id;
                        option.textContent = course.code;
                        if (course.id == selectedValue) option.selected = true;
                        selectElement.appendChild(option);
                    });

                    $(selectElement).select2({
                        dropdownParent: $('#examEditModal'),
                        placeholder: "Chọn khóa học",
                        allowClear: true,
                        width: '100%'
                    });

                    if (typeof callback === 'function') callback();
                })
                .catch(error => console.error('Error fetching courses:', error));
        }

        // fetch môn thi và học viên cho edit
        function editFetchAndUpdate(courseId, type, date, time, holdType, examFieldSelector, studentSelector, selectedStudentIds = []) {
            if (type !== 'exam') return;
            if (!courseId) {
                const subjectContainer = document.getElementById('exam_id');
                subjectContainer.innerHTML = '<p class="text-muted">-- Vui lòng chọn khóa học trước --</p>';
                const studentSelect = document.getElementById('edit_exam_student_id_select');
                studentSelect.innerHTML = '';
                $(studentSelect).select2({
                    dropdownParent: $('#addCalenderModal'),
                    placeholder: "Chọn học viên",
                    allowClear: true,
                    multiple: true,
                    width: '100%'
                });
                return;
            }

            let url = `/course-data/exam/${courseId}?date=${encodeURIComponent(date)}&time=${encodeURIComponent(time)}&examType=${encodeURIComponent(holdType)}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const subjectContainer = document.getElementById('edit_exam_id');
                    subjectContainer.innerHTML = '';
                    const subjects = data.course.exam_fields || [];
                    if (subjects.length > 0) {
                        const selectAllDiv = document.createElement('div');
                        selectAllDiv.classList.add('mb-2');
                        selectAllDiv.innerHTML = `
                            <label>
                                <input type="checkbox" id="edit_select_all_exams" /> Chọn tất cả
                            </label>
                        `;
                        subjectContainer.appendChild(selectAllDiv);
                    }

                    const rowWrapper = document.createElement('div');
                    rowWrapper.classList.add('row');

                    subjects.forEach(subject => {
                        const isChecked = selectedExamIds.includes(subject.id) ? 'checked' : '';
                        const col = document.createElement('div');
                        col.classList.add('form-check', 'col-md-2', 'mb-2');
                        col.style.margin = '0 .63rem';
                        col.innerHTML = `
                            <input type="checkbox" name="exam_id[]" value="${subject.id}" class="form-check-input edit-exam-checkbox" id="edit_exam_${subject.id}" ${isChecked}>
                            <label for="edit_exam_${subject.id}" class="form-check-label">${subject.name}</label>
                        `;
                        rowWrapper.appendChild(col);
                    });

                    subjectContainer.appendChild(rowWrapper);

                    const selectAll = document.getElementById('edit_select_all_exams');
                    if (selectAll) {
                        selectAll.addEventListener('change', function () {
                            document.querySelectorAll('.edit-exam-checkbox').forEach(cb => {
                                cb.checked = this.checked;
                            });
                        });
                    }


                    // Gán học viên
                    const studentSelect = document.querySelector(studentSelector);
                    studentSelect.innerHTML = '';
                    const availableStudents = data.available_students || [];
                    availableStudents.forEach(student => {
                        const option = document.createElement('option');
                        option.value = student.id;
                        option.textContent = `${student.name} - ${student.student_code}`;
                        if (selectedStudentIds.includes(student.id)) {
                            option.selected = true;
                        }
                        studentSelect.appendChild(option);
                    });
                    if ($.fn.select2 && $(studentSelect).hasClass('select2-hidden-accessible')) {
                        $(studentSelect).select2('destroy');
                    }
                    $(studentSelector).select2({
                        dropdownParent: $('#examEditModal'),
                        placeholder: "Chọn học viên",
                        allowClear: true,
                        multiple: true,
                        width: '100%'
                    });

                    // Fetch lại danh sách sân thi (optional)
                    if (stadiumId && selectedExamIds.length > 0) {
                        editFetchExamSchedules(courseId, selectedExamIds, date, time);
                    }

                })
                .catch(error => console.error('Error:', error));



        }

        // Gọi sân thi cho edit
        function editFetchExamSchedules(courseId, examFieldIds, date, time) {
            if (!courseId || !examFieldIds) return;

            const params = new URLSearchParams({
                course_id: courseId,
                date: date,
                time: time
            });
            if (Array.isArray(examFieldIds)) {
                examFieldIds.forEach(id => params.append('exam_field_ids[]', id));
            } else {
                params.append('exam_field_ids[]', examFieldIds);
            }

            fetch(`/exam-schedules-available?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('edit_exam_schedule_id');
                    select.innerHTML = '<option value="">-- Chọn sân thi --</option>';
                    data.exam_schedules.forEach(schedule => {
                        const location = schedule.stadium?.location || 'Không rõ địa điểm';
                        const option = document.createElement('option');
                        option.value = schedule.id;
                        option.textContent = `${location} (${schedule.start_time} → ${schedule.end_time})`;

                        // Tự động chọn sân nếu match với stadiumId
                        if (schedule.stadium?.id == stadiumId) {
                            option.selected = true;
                        }

                        select.appendChild(option);
                    });

                    $(select).select2({
                        dropdownParent: $('#examEditModal')
                    });
                })

        }

        // Hàm xử lý thay đổi thời gian
        function handleEditTimeChange() {
            const time = timeInput ? timeInput.value : '';
            const courseId = $('#edit_exam_course_id').val();
            const examCourseType = examCourseTypeInput ? examCourseTypeInput.value : '';
            if (date && time) {
                editFetchAndUpdate(courseId, 'exam', date, time, examCourseType);
            }
        }

        // Hàm xử lý thay đổi thời gian và môn thi để lấy sân thi
        function handleEditTimeChangeSchedules() {
            const time = timeInput ? timeInput.value : '';
            const courseId = $('#edit_exam_course_id').val();
            const examFieldIds = Array.from(document.querySelectorAll('.edit-exam-checkbox:checked')).map(cb => cb.value);

            if (date && time && courseId && examFieldIds) {
                editFetchExamSchedules(courseId, examFieldIds, date, time);
            }
        }

        // Gắn sự kiện thay đổi

        if (timeInput) timeInput.addEventListener('change', handleEditTimeChange);
        editCourseSelect.addEventListener('change', handleEditTimeChange);
        $(document).on('change', '.edit-exam-checkbox', function () {
            handleEditTimeChangeSchedules();
        });
    });
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
                    document.getElementById('study-fields').style.display = 'block';
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
                        data.students.forEach(student => {
                            fields += `
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header fw-bold">
                                        <h6>Học viên: ${student.name} - ${student.student_code}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                            `;

                            if (type === 'study') {
                                fields += `
                                <label>Giờ học: <input type="text" name="students[${student.student_id}][hours]" class="form-control" value="${student.pivot.hours ?? ''}"></label>
                                <label>Số km: <input type="text" step="0.001" name="students[${student.student_id}][km]" class="form-control currency-input" value="${student.pivot.km ?? ''}"></label>
                                <label>Giờ ban đêm: <input type="text" step="0.001" name="students[${student.student_id}][night_hours]" class="form-control currency-input" value="${student.pivot.night_hours ?? ''}"></label>
                                <label>Giờ xe tự động: <input type="text" step="0.001" name="students[${student.student_id}][auto_hours]" class="form-control currency-input" value="${student.pivot.auto_hours ?? ''}"></label>
                                `;
                            } else if (type === 'exam') {
                                if (Array.isArray(data.calendar.field_name)) {
                                    data.calendar.field_name.forEach(field => {
                                        const examResult = (student.exam_results || []).find(r => r.exam_field_id === field.id);
                                        const examStatus = examResult?.exam_status ?? 0;
                                        const remarks = examResult?.remarks ?? '';
                                        fields += `
                                            <div class="col-md-6 mb-3">
                                                <div class="border p-3 rounded bg-light">
                                                    <strong>Môn thi: ${field.name}</strong>
                                                    <div class="mb-2">
                                                        <select name="students[${student.student_id}][${field.id}][exam_status]" class="form-control">
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
                                                    <input type="text" name="students[${student.student_id}][${field.id}][remarks]" class="form-control" value="${remarks}">
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
                                                    <input type="text" name="students[${student.student_id}][remarks]" class="form-control" value="${student.pivot.remarks ?? ''}">
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
