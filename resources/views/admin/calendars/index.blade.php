@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="card-body">
        @php
            $baseQuery = request()->except('type', 'page');
        @endphp
        {{-- Giả dạng tab để chọn loại lịch --}}
        <div class="mb-3">
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
        </div>

        <div class="filter-options mb-3">
            <form id="calendarFilterForm" class="row" method="GET">
                <input type="hidden" name="type" id="selectedType" value="{{ request('type', 'study') }}">

                <div class="col-12 col-md-2 mb-2">
                    <label class="form-label fw-bold">Mã học viên</label>
                    <input type="text" name="student_code" placeholder="Mã học viên" class="form-control" value="{{ request('student_code') }}">
                </div>

                <div class="col-12 col-md-3 mb-2">
                    <label class="form-label fw-bold">Tên học viên</label>
                    <input type="text" name="student_name" placeholder="Tên học viên" class="form-control mb-2" value="{{ request('student_name') }}">
                </div>

                <div class="col-12 col-md-3 mb-2">
                    <label class="form-label fw-bold">Tên giáo viên</label>
                    <input type="text" name="user_name" placeholder="Tên giáo viên, quản lý,..." class="form-control mb-2" value="{{ request('user_name') }}">
                </div>

                <div class="col-12 col-md-3 mb-2">
                    <label class="form-label fw-bold">Khóa học</label>
                    <select name="course_id" class="form-control">
                        <option value="">Chọn khóa học</option>
                        @foreach($courseAlls as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->code }}
                            </option>
                        @endforeach
                    </select>
                </div>

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

                <div class="col-12 col-md-2 mb-2">
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
                </div>

                <div class="col-12 col-md-2 mb-2" id="examFilter" style="{{ request('type') === 'exam' ? '' : 'display: none;' }}">
                    <label class="form-label fw-bold">Loại kỳ thi</label>
                    <select name="level_filter" class="form-select mb-2" {{ request('type') === 'exam' ? '' : 'disabled' }}>
                        <option value="">Chọn loại kỳ thi</option>
                        <option value="1" {{ request('level_filter') == '1' ? 'selected' : '' }}>Thi tốt nghiệp</option>
                        <option value="2" {{ request('level_filter') == '2' ? 'selected' : '' }}>Thi sát hạch</option>
                    </select>
                </div>
                
                <div class="col-12 col-md-3 mb-2">
                    <button type="submit" class="btn btn-primary mb-1">Lọc</button>
                    <a href="{{ route('calendars.index', ['type' => $type]) }}" class="btn btn-secondary mb-1" id="clearFilters">Bỏ Lọc</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card" id="card">
    <div class="card-body">
        <a href="{{ route('calendars.create') }}" 
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 40px; height: 40px; font-size: 30px; bottom: 3%; right: 60px; z-index: 99;">
                +
        </a>
        {{-- Tab Content cho loại lịch --}}
        <div class="tab-content mt-3" id="calendarTypeTabsContent">
            @foreach($calendarTypes as $key => $label)
                <div class="tab-pane fade {{ $key == $type ? 'show active' : '' }}" id="tab-{{ $key }}" role="tabpanel" aria-labelledby="tab-{{ $key }}-tab">
                    <!-- Nếu người dùng chọn loại lịch này, sẽ hiển thị nội dung dưới đây -->
                    @if($key == $type && $key != 'exam')
                        <h5>Lịch {{ $label }}</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ngày</th>
                                        <th class="text-center">Số lượng lịch</th>
                                        <th class="text-center">Chưa duyệt</th>
                                        <th class="text-center">Đã duyệt</th>
                                        <th class="text-center">Đã hủy</th>
                                        <th class="text-center">Chưa nhập kết quả</th>
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
                                            <td>{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</td>
                                            <td class="text-center">
                                                {{ $group['count'] }} lịch
                                                <a class="btn btn-link" data-bs-toggle="collapse" href="#collapse-{{ \Carbon\Carbon::parse($dateOnly)->format('Ymd') }}" role="button" aria-expanded="false" aria-controls="collapse-{{ \Carbon\Carbon::parse($dateOnly)->format('Ymd') }}">
                                                    <i class="fas fa-chevron-down"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">{{ $group['status_3_count'] }}</td>
                                            <td class="text-center">{{ $group['status_10_count'] }}</td>
                                            <td class="text-center">{{ $group['status_4_count'] }}</td>
                                            <td class="text-center">{{ $group['status_1_2_count'] }}</td>
                                        </tr>
                                        
                                        <tr class="collapse" id="collapse-{{ \Carbon\Carbon::parse($dateOnly)->format('Ymd') }}">
                                            <td colspan="6">
                                                <div style="overflow-x: auto;">
                                                    <table class="table table-bordered mt-3" style="width: 100%; table-layout: auto;">
                                                        @if($key == 'study')
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-nowrap">Tiêu đề</th>
                                                                    <th class="text-nowrap">Trạng thái</th>
                                                                    <th class="text-nowrap">Xét duyệt</th>
                                                                    <th class="text-nowrap">Mức độ ưu tiên</th>
                                                                    <th class="text-nowrap">Ngày bắt đầu</th>
                                                                    <th class="text-nowrap">Ngày kết thúc</th>
                                                                    <th class="text-nowrap">Thời lượng</th>
                                                                    <th class="text-nowrap">Sân học</th>
                                                                    <th class="text-nowrap">Địa điểm</th>
                                                                    <th class="text-nowrap">Khóa học</th>
                                                                    <th class="text-nowrap">Môn học</th>
                                                                    <th class="text-nowrap">Học viên</th>
                                                                    <th class="text-nowrap">Giáo viên</th>
                                                                    <th class="text-nowrap">Mô tả</th>
                                                                    <th class="text-nowrap">Hành động</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($group['calendars'] as $calendar)
                                                                    <tr>
                                                                        <td class="text-nowrap" rowspan="{{ count($calendar->students) }}">{{ $calendar->name }}</td>
                                                                        <td class="text-nowrap" rowspan="{{ count($calendar->students) }}">
                                                                            <form action="{{ route('calendar.updateStatus', $calendar->id) }}" method="POST">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                @php
                                                                                    // Lấy các trạng thái hợp lệ mà calendar có thể chuyển sang dựa trên trạng thái hiện tại
                                                                                    $validTransitions = $statusConfig['transitions'][$calendar->status] ?? [];
                                                                                @endphp

                                                                                @if (count($validTransitions) > 0)
                                                                                    <!-- Hiển thị dropdown nếu có trạng thái hợp lệ để chuyển -->
                                                                                    <select name="status" class="form-control" style="width: 120px;" onchange="this.form.submit()">
                                                                                        @foreach ($validTransitions as $statusId)
                                                                                            <option value="{{ $statusId }}" {{ $calendar->status == $statusId ? 'selected' : '' }}>
                                                                                                {{ $statusConfig['labels'][$statusId] ?? 'Không xác định' }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                @else
                                                                                    @php
                                                                                        // Lấy label của trạng thái hiện tại
                                                                                        $currentStatusLabel = $statusConfig['labels'][$calendar->status] ?? 'Không xác định';

                                                                                        // Xác định màu nền dựa trên trạng thái
                                                                                        $bgColorClass = '';
                                                                                        if ($calendar->status == 4) {
                                                                                            $bgColorClass = 'bg-danger text-white';
                                                                                        } elseif ($calendar->status == 10) {
                                                                                            $bgColorClass = 'bg-success text-white';
                                                                                        }
                                                                                    @endphp

                                                                                    <!-- Hiển thị trạng thái hiện tại với màu nền -->
                                                                                    <p class="{{ $bgColorClass }} text-center m-0 p-1" style="border-radius: 5px;">
                                                                                        {{ $currentStatusLabel }}
                                                                                    </p>
                                                                                @endif
                                                                            </form>
                                                                        </td>
                                                                        <td rowspan="{{ count($calendar->students) }}">
                                                                            @if ($calendar->status == 3)
                                                                                <form action="{{ route('calendar.approve', $calendar->id) }}" method="POST">
                                                                                    @csrf
                                                                                    @method('PATCH')
                                                                                    <button type="submit" class="btn btn-primary">Duyệt ngay</button>
                                                                                </form>
                                                                            @elseif ($calendar->status == 10)
                                                                                Đã duyệt
                                                                            @elseif ($calendar->status == 4)
                                                                                Đã hủy
                                                                            @else
                                                                                Chưa có kết quả
                                                                            @endif
                                                                        </td>
                                                                        <td rowspan="{{ count($calendar->students) }}">
                                                                            @if($calendar->priority == 'Low')
                                                                                Thấp
                                                                            @elseif($calendar->priority == 'Normal')
                                                                                Bình thường
                                                                            @elseif($calendar->priority == 'High')
                                                                                Cao
                                                                            @elseif($calendar->priority == 'Urgent')
                                                                                Khẩn cấp
                                                                            @else
                                                                                {{ $calendar->priority }}
                                                                            @endif
                                                                        </td>
                                                                        <td rowspan="{{ count($calendar->students) }}">{{ $calendar->date_start }}</td>
                                                                        <td rowspan="{{ count($calendar->students) }}">{{ $calendar->date_end }}</td>
                                                                        <td rowspan="{{ count($calendar->students) }}">{{ $calendar->duration }}</td>
                                                                        <td rowspan="{{ count($calendar->students) }}">
                                                                            <a href="{{ $calendar->stadium?->google_maps_url }}" target="_blank" rel="noopener noreferrer">
                                                                                {{ $calendar->stadium?->location }}
                                                                            </a>
                                                                        </td>
                                                                        <td rowspan="{{ count($calendar->students) }}">{{ $calendar->location }}</td>
                                                                        <td rowspan="{{ count($calendar->students) }}">
                                                                            @foreach($calendar->courses as $course)
                                                                                {{ $course->code }}
                                                                                @if (!$loop->last), @endif
                                                                            @endforeach
                                                                        </td>
                                                                        <td rowspan="{{ count($calendar->students) }}">{{ $calendar->learningField->name ?? "-" }}</td>
                                                                        <td>
                                                                            @if($calendar->students->isNotEmpty())
                                                                                {{ $calendar->students[0]->name }} - {{ $calendar->students[0]->student_code }}
                                                                            @else
                                                                                <span class="text-muted">Chưa có học viên</span>
                                                                            @endif
                                                                        </td>
                                                                        <td rowspan="{{ count($calendar->students) }}">
                                                                            @foreach($calendar->users as $user) 
                                                                                {{ $user->name }} 
                                                                                @if (!$loop->last), @endif
                                                                            @endforeach
                                                                        </td>
                                                                        <td rowspan="{{ count($calendar->students) }}">{{ $calendar->description }}</td>
                                                                        <td rowspan="{{ count($calendar->students) }}">
                                                                            <div class="d-flex gap-2 align-items-center">
                                                                                @if (!in_array($calendar->status, [4, 10]))
                                                                                    @if (now()->lt(\Carbon\Carbon::parse($calendar->date_start)))
                                                                                        <a href="{{ route('calendars.edit', $calendar->id) }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-user-pen" title="Chỉnh sửa"></i></a>
                                                                                    @endif
                                                                                    <button class="btn btn-sm btn-info openResultModalBtn" 
                                                                                        data-calendar-id="{{ $calendar->id }}" 
                                                                                        data-type="{{ $calendar->type }}">
                                                                                        <i class="fa-solid fa-file-pen" title="Nhập kết quả"></i>
                                                                                    </button>
                                                                                @endif
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    @foreach($calendar->students->skip(1) as $student)
                                                                        <tr>
                                                                            <td>{{ $student->name }} - {{ $student->student_code }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endforeach
                                                            </tbody>
                                                        @elseif($key == 'work')  <!-- Nếu loại lịch là công việc -->
                                                            <thead>
                                                                <tr>
                                                                    <th>Tiêu đề</th>
                                                                    <th>Trạng thái</th>
                                                                    <th>Mức độ ưu tiên</th>
                                                                    <th>Ngày bắt đầu</th>
                                                                    <th>Ngày kết thúc</th>
                                                                    <th>Thời lượng</th>
                                                                    <th>Địa điểm</th>
                                                                    <th>Người phụ trách</th>
                                                                    <th>Người hỗ trợ</th>
                                                                    <th>Mô tả</th>
                                                                    <th>Hành động</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($group['calendars'] as $calendar)
                                                                    <tr>
                                                                        <td>{{ $calendar->name }}</td>
                                                                        <td class="text-nowrap">
                                                                            <form action="{{ route('calendar.updateStatus', $calendar->id) }}" method="POST">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                @php
                                                                                    // Lấy các trạng thái hợp lệ mà calendar có thể chuyển sang dựa trên trạng thái hiện tại
                                                                                    $validTransitions = $statusConfig['transitions'][$calendar->status] ?? [];
                                                                                @endphp

                                                                                @if (count($validTransitions) > 0)
                                                                                    <!-- Hiển thị dropdown nếu có trạng thái hợp lệ để chuyển -->
                                                                                    <select name="status" class="form-control" style="width: 120px;" onchange="this.form.submit()">
                                                                                        @foreach ($validTransitions as $statusId)
                                                                                            <option value="{{ $statusId }}" {{ $calendar->status == $statusId ? 'selected' : '' }}>
                                                                                                {{ $statusConfig['labels'][$statusId] ?? 'Không xác định' }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                @else
                                                                                    @php
                                                                                        // Lấy label của trạng thái hiện tại
                                                                                        $currentStatusLabel = $statusConfig['labels'][$calendar->status] ?? 'Không xác định';

                                                                                        // Xác định màu nền dựa trên trạng thái
                                                                                        $bgColorClass = '';
                                                                                        if ($calendar->status == 4) {
                                                                                            $bgColorClass = 'bg-danger text-white';
                                                                                        } elseif ($calendar->status == 10) {
                                                                                            $bgColorClass = 'bg-success text-white';
                                                                                        }
                                                                                    @endphp

                                                                                    <!-- Hiển thị trạng thái hiện tại với màu nền -->
                                                                                    <p class="{{ $bgColorClass }} text-center m-0 p-1" style="border-radius: 5px;">
                                                                                        {{ $currentStatusLabel }}
                                                                                    </p>
                                                                                @endif
                                                                            </form>
                                                                        </td>
                                                                        <td>
                                                                            @if($calendar->priority == 'Low')
                                                                                Thấp
                                                                            @elseif($calendar->priority == 'Normal')
                                                                                Bình thường
                                                                            @elseif($calendar->priority == 'High')
                                                                                Cao
                                                                            @elseif($calendar->priority == 'Urgent')
                                                                                Khẩn cấp
                                                                            @else
                                                                                {{ $calendar->priority }}
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $calendar->date_start }}</td>
                                                                        <td>{{ $calendar->date_end }}</td>
                                                                        <td>{{ $calendar->duration }}</td>
                                                                        <td>{{ $calendar->location }}</td>
                                                                        <td>
                                                                            @foreach($calendar->users->where('pivot.role', 1) as $user)
                                                                                {{ $user->name }} 
                                                                                @if (!$loop->last), @endif
                                                                            @endforeach
                                                                        </td>
                                                                        <td>
                                                                            @foreach($calendar->users->where('pivot.role', 4) as $user)
                                                                                {{ $user->name }} 
                                                                                @if (!$loop->last), @endif
                                                                            @endforeach
                                                                        </td>
                                                                        <td>{{ $calendar->description }}</td>
                                                                        <td>
                                                                            @if (now()->lt($calendar->date_start) || !in_array($calendar->status, [4, 10]))
                                                                                <button class="btn btn-sm btn-info openResultModalBtn" 
                                                                                    data-calendar-id="{{ $calendar->id }}" 
                                                                                    data-type="{{ $calendar->type }}">
                                                                                    <i class="fa-solid fa-file-pen" title="Nhập kết quả"></i>
                                                                                </button>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        @elseif($key == 'meeting')  <!-- Nếu loại lịch là lịch họp -->
                                                            <thead>
                                                                <tr>
                                                                    <th rowspan="2">Tiêu đề</th>
                                                                    <th rowspan="2">Trạng thái</th>
                                                                    <th rowspan="2">Mức độ ưu tiên</th>
                                                                    <th rowspan="2">Ngày bắt đầu</th>
                                                                    <th rowspan="2">Ngày kết thúc</th>
                                                                    <th rowspan="2">Thời lượng</th>
                                                                    <th rowspan="2">Địa điểm</th>
                                                                    <th rowspan="2">Người phụ trách</th>
                                                                    <th rowspan="2">Người hỗ trợ</th>
                                                                    <th colspan="2">Người tham gia</th>
                                                                    <th rowspan="2">Mô tả</th>
                                                                    <th rowspan="2">Hành động</th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="align-middle">Nhân viên</th>
                                                                    <th class="align-middle">Học viên</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($group['calendars'] as $calendar)
                                                                    <tr>
                                                                        <td  rowspan="{{ count($calendar->students) }}">{{ $calendar->name }}</td>
                                                                        <td class="text-nowrap" rowspan="{{ count($calendar->students) }}">
                                                                            <form action="{{ route('calendar.updateStatus', $calendar->id) }}" method="POST">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                @php
                                                                                    // Lấy các trạng thái hợp lệ mà calendar có thể chuyển sang dựa trên trạng thái hiện tại
                                                                                    $validTransitions = $statusConfig['transitions'][$calendar->status] ?? [];
                                                                                @endphp

                                                                                @if (count($validTransitions) > 0)
                                                                                    <!-- Hiển thị dropdown nếu có trạng thái hợp lệ để chuyển -->
                                                                                    <select name="status" class="form-control" style="width: 120px;" onchange="this.form.submit()">
                                                                                        @foreach ($validTransitions as $statusId)
                                                                                            <option value="{{ $statusId }}" {{ $calendar->status == $statusId ? 'selected' : '' }}>
                                                                                                {{ $statusConfig['labels'][$statusId] ?? 'Không xác định' }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                @else
                                                                                    @php
                                                                                        // Lấy label của trạng thái hiện tại
                                                                                        $currentStatusLabel = $statusConfig['labels'][$calendar->status] ?? 'Không xác định';

                                                                                        // Xác định màu nền dựa trên trạng thái
                                                                                        $bgColorClass = '';
                                                                                        if ($calendar->status == 4) {
                                                                                            $bgColorClass = 'bg-danger text-white';
                                                                                        } elseif ($calendar->status == 10) {
                                                                                            $bgColorClass = 'bg-success text-white';
                                                                                        }
                                                                                    @endphp

                                                                                    <!-- Hiển thị trạng thái hiện tại với màu nền -->
                                                                                    <p class="{{ $bgColorClass }} text-center m-0 p-1" style="border-radius: 5px;">
                                                                                        {{ $currentStatusLabel }}
                                                                                    </p>
                                                                                @endif
                                                                            </form>
                                                                        </td>
                                                                        <td  rowspan="{{ count($calendar->students) }}">
                                                                            @if($calendar->priority == 'Low')
                                                                                Thấp
                                                                            @elseif($calendar->priority == 'Normal')
                                                                                Bình thường
                                                                            @elseif($calendar->priority == 'High')
                                                                                Cao
                                                                            @elseif($calendar->priority == 'Urgent')
                                                                                Khẩn cấp
                                                                            @else
                                                                                {{ $calendar->priority }}
                                                                            @endif
                                                                        </td>
                                                                        <td rowspan="{{ count($calendar->students) }}">{{ $calendar->date_start }}</td>
                                                                        <td rowspan="{{ count($calendar->students) }}">{{ $calendar->date_end }}</td>
                                                                        <td rowspan="{{ count($calendar->students) }}">{{ $calendar->duration }}</td>
                                                                        <td rowspan="{{ count($calendar->students) }}">{{ $calendar->location }}</td>
                                                                        <td rowspan="{{ count($calendar->students) }}">
                                                                            @foreach($calendar->users->where('pivot.role', 1) as $user)
                                                                                {{ $user->name }} 
                                                                                @if (!$loop->last), @endif
                                                                            @endforeach
                                                                        </td>
                                                                        <td rowspan="{{ count($calendar->students) }}">
                                                                            @foreach($calendar->users->where('pivot.role', 4) as $user)
                                                                                {{ $user->name }} 
                                                                                @if (!$loop->last), @endif
                                                                            @endforeach
                                                                        </td>
                                                                        <td rowspan="{{ count($calendar->students) }}">
                                                                            @foreach($calendar->users->where('pivot.role', 5) as $user)
                                                                                {{ $user->name }} 
                                                                                @if (!$loop->last), @endif
                                                                            @endforeach
                                                                        </td>
                                                                        <td>{{ $calendar->student[0]->name }} - {{ $calendar->student[0]->student_code }}</td>
                                                                        <td rowspan="{{ count($calendar->students) }}">{{ $calendar->description }}</td>
                                                                        <td rowspan="{{ count($calendar->students) }}">
                                                                            @if (now()->lt($calendar->date_start) || !in_array($calendar->status, [4, 10]))
                                                                                <button class="btn btn-sm btn-info openResultModalBtn" 
                                                                                    data-calendar-id="{{ $calendar->id }}" 
                                                                                    data-type="{{ $calendar->type }}">
                                                                                    <i class="fa-solid fa-file-pen" title="Nhập kết quả"></i>
                                                                                </button>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @foreach($calendar->students->skip(1) as $student)
                                                                        <tr>
                                                                            <td>{{ $student->name }} - {{ $student->student_code }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endforeach
                                                            </tbody>
                                                        @elseif($key == 'call')  <!-- Nếu loại lịch là lịch gọi -->
                                                            <thead>
                                                                <tr>
                                                                    <th>Tiêu đề</th>
                                                                    <th>Trạng thái</th>
                                                                    <th>Mức độ ưu tiên</th>
                                                                    <th>Ngày bắt đầu</th>
                                                                    <th>Ngày kết thúc</th>
                                                                    <th>Thời lượng</th>
                                                                    <th>Địa điểm</th>
                                                                    <th>Người thực hiện</th>
                                                                    <th>Học viên</th>
                                                                    <th>Mô tả</th>
                                                                    <th>Hành động</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($group['calendars'] as $calendar)
                                                                    <tr>
                                                                        <td>{{ $calendar->name }}</td>
                                                                        <td class="text-nowrap">
                                                                            <form action="{{ route('calendar.updateStatus', $calendar->id) }}" method="POST">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                @php
                                                                                    // Lấy các trạng thái hợp lệ mà calendar có thể chuyển sang dựa trên trạng thái hiện tại
                                                                                    $validTransitions = $statusConfig['transitions'][$calendar->status] ?? [];
                                                                                @endphp

                                                                                @if (count($validTransitions) > 0)
                                                                                    <!-- Hiển thị dropdown nếu có trạng thái hợp lệ để chuyển -->
                                                                                    <select name="status" class="form-control" style="width: 120px;" onchange="this.form.submit()">
                                                                                        @foreach ($validTransitions as $statusId)
                                                                                            <option value="{{ $statusId }}" {{ $calendar->status == $statusId ? 'selected' : '' }}>
                                                                                                {{ $statusConfig['labels'][$statusId] ?? 'Không xác định' }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                @else
                                                                                    @php
                                                                                        // Lấy label của trạng thái hiện tại
                                                                                        $currentStatusLabel = $statusConfig['labels'][$calendar->status] ?? 'Không xác định';

                                                                                        // Xác định màu nền dựa trên trạng thái
                                                                                        $bgColorClass = '';
                                                                                        if ($calendar->status == 3) {
                                                                                            $bgColorClass = 'bg-danger text-white';
                                                                                        } elseif ($calendar->status == 2) {
                                                                                            $bgColorClass = 'bg-success text-white';
                                                                                        }
                                                                                    @endphp

                                                                                    <!-- Hiển thị trạng thái hiện tại với màu nền -->
                                                                                    <p class="{{ $bgColorClass }} text-center m-0 p-1" style="border-radius: 5px;">
                                                                                        {{ $currentStatusLabel }}
                                                                                    </p>
                                                                                @endif
                                                                            </form>
                                                                        </td>
                                                                        <td>
                                                                            @if($calendar->priority == 'Low')
                                                                                Thấp
                                                                            @elseif($calendar->priority == 'Normal')
                                                                                Bình thường
                                                                            @elseif($calendar->priority == 'High')
                                                                                Cao
                                                                            @elseif($calendar->priority == 'Urgent')
                                                                                Khẩn cấp
                                                                            @else
                                                                                {{ $calendar->priority }}
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $calendar->date_start }}</td>
                                                                        <td>{{ $calendar->date_end }}</td>
                                                                        <td>{{ $calendar->duration }}</td>
                                                                        <td>{{ $calendar->location }}</td>
                                                                        <td>
                                                                            @foreach($calendar->users->where('pivot.role', 3) as $user)
                                                                                {{ $user->name }} 
                                                                                @if (!$loop->last), @endif
                                                                            @endforeach
                                                                        </td>
                                                                        <td>
                                                                            @foreach($calendar->students as $student)
                                                                                {{ $student->name }} - {{ $student->student_code }}
                                                                                @if (!$loop->last), @endif
                                                                            @endforeach
                                                                        </td>
                                                                        <td>{{ $calendar->description }}</td>
                                                                        <td>
                                                                            @if (now()->lt($calendar->date_start) || !in_array($calendar->status, [4, 10]))
                                                                                <button class="btn btn-sm btn-info openResultModalBtn" 
                                                                                    data-calendar-id="{{ $calendar->id }}" 
                                                                                    data-type="{{ $calendar->type }}">
                                                                                    <i class="fa-solid fa-file-pen" title="Nhập kết quả"></i>
                                                                                </button>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        @endif
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Phân trang --}}
                        <div class="mt-3">
                            {{ $paginatedCalendars->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        @foreach ($paginatedCalendars as $level => $paginator)
                            <h4 class="text-lg font-bold mt-6">
                                Lịch thi 
                                {{
                                    $level == 1 ? 'tốt nghiệp' :
                                    ($level == 2 ? 'sát hạch' :
                                    ($level == 5 ? 'hết môn thực hành' :
                                    ($level == 6 ? 'hết môn lý thuyết' : 'chưa xác định')))
                                }}
                            </h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Ngày</th>
                                            <th class="text-center">Số lượng lịch</th>
                                            <th class="text-center">Chưa duyệt</th>
                                            <th class="text-center">Đã duyệt</th>
                                            <th class="text-center">Đã hủy</th>
                                            <th class="text-center">Chưa nhập kết quả</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($paginatedCalendars[$level] as $date => $group)
                                        @php
                                            try {
                                                $parsedDate = \Carbon\Carbon::parse($date);
                                            } catch (\Exception $e) {
                                                $parsedDate = null;
                                            }
                                        @endphp
                                            <tr>
                                                @if ($parsedDate)
                                                <td class="text-center">{{ $parsedDate->format('d-m-Y') }} (Thứ {{ $parsedDate->dayOfWeek + 1 }})</td>
                                                @else
                                                    <span class="text-danger">Ngày không hợp lệ</span>
                                                @endif
                                                <td class="text-center">
                                                    {{ $group['count'] ?? 0 }} lịch
                                                    <a class="btn btn-link" data-bs-toggle="collapse" href="#collapse-{{ $level }}-{{ \Illuminate\Support\Str::slug($date) }}" role="button" aria-expanded="false" aria-controls="collapse-{{ $date }}">
                                                        <i class="fas fa-chevron-down"></i>
                                                    </a>
                                                </td>
                                                <td class="text-center">{{ $group['status_3_count'] ?? 0 }}</td>
                                                <td class="text-center">{{ $group['status_10_count'] ?? 0 }}</td>
                                                <td class="text-center">{{ $group['status_4_count'] ?? 0 }}</td>
                                                <td class="text-center">{{ $group['status_1_2_count'] ?? 0 }}</td>
                                            </tr>
                                            
                                            <tr class="collapse" id="collapse-{{ $level }}-{{ \Illuminate\Support\Str::slug($date) }}">
                                                <td colspan="6">
                                                    <div style="overflow-x: auto;">
                                                        <table class="table table-bordered mt-3" style="width: 100%; table-layout: auto;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Ngày thi</th>
                                                                    <th>Buổi thi</th>
                                                                    <th>Tiêu đề</th>
                                                                    <th>Trạng thái</th>
                                                                    <th class="text-nowrap">Xét duyệt</th>
                                                                    <th>Mức độ ưu tiên</th>
                                                                    <th>Sân thi</th>
                                                                    <th>Địa điểm</th>
                                                                    <th>Khóa học</th>
                                                                    <th>Môn thi</th>
                                                                    <th>Số học viên</th>
                                                                    <th>Giám thị</th>
                                                                    <th>Mô tả</th>
                                                                    <th>Hành động</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if(isset($group['calendars_by_time']) && is_iterable($group['calendars_by_time']))
                                                                    @php
                                                                        $dateRowspan = 0;
                                                                        foreach ($group['calendars_by_time'] as $time => $calendars) {
                                                                            $dateRowspan += count($calendars);
                                                                        }
                                                                    @endphp
                                                                    @php $isFirstDate = true; @endphp
                                                                    @foreach($group['calendars_by_time'] as $time => $calendars)
                                                                        @php
                                                                            $sessionRowspan = count($calendars);
                                                                            $session = isset($timeMap[$time]) ? $timeMap[$time] : 'Buổi ' . ($time == 1 ? "sáng" : ($time == 2 ? "chiều" : "cả ngày"));
                                                                        @endphp
                                                                        @php $isFirstSession = true; @endphp
                                                                        @foreach ($calendars as $calendar)
                                                                            <tr>
                                                                                @if ($isFirstDate)
                                                                                    <td rowspan="{{ $dateRowspan }}" class="text-nowrap">{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}  (Thứ {{ \Carbon\Carbon::parse($date)->dayOfWeek + 1 }})</td>
                                                                                    @php $isFirstDate = false; @endphp
                                                                                @endif
                                                                                @if ($isFirstSession)
                                                                                    <td rowspan="{{ $sessionRowspan }}" class="text-nowrap">{{ $session }}</td>
                                                                                    @php $isFirstSession = false; @endphp
                                                                                @endif
                                                                                <td>{{ $calendar->name }}</td>
                                                                                <td class="text-nowrap" >
                                                                                    <form action="{{ route('calendar.updateStatus', $calendar->id) }}" method="POST">
                                                                                        @csrf
                                                                                        @method('PATCH')
                                                                                        @php
                                                                                            // Lấy các trạng thái hợp lệ mà calendar có thể chuyển sang dựa trên trạng thái hiện tại
                                                                                            $validTransitions = $statusConfig['transitions'][$calendar->status] ?? [];
                                                                                        @endphp

                                                                                        @if (count($validTransitions) > 0)
                                                                                            <!-- Hiển thị dropdown nếu có trạng thái hợp lệ để chuyển -->
                                                                                            <select name="status" class="form-control" style="width: 120px;" onchange="this.form.submit()">
                                                                                                @foreach ($validTransitions as $statusId)
                                                                                                    <option value="{{ $statusId }}" {{ $calendar->status == $statusId ? 'selected' : '' }}>
                                                                                                        {{ $statusConfig['labels'][$statusId] ?? 'Không xác định' }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        @else
                                                                                            @php
                                                                                                // Lấy label của trạng thái hiện tại
                                                                                                $currentStatusLabel = $statusConfig['labels'][$calendar->status] ?? 'Không xác định';

                                                                                                // Xác định màu nền dựa trên trạng thái
                                                                                                $bgColorClass = '';
                                                                                                if ($calendar->status == 4) {
                                                                                                    $bgColorClass = 'bg-danger text-white';
                                                                                                } elseif ($calendar->status == 10) {
                                                                                                    $bgColorClass = 'bg-success text-white';
                                                                                                }
                                                                                            @endphp

                                                                                            <!-- Hiển thị trạng thái hiện tại với màu nền -->
                                                                                            <p class="{{ $bgColorClass }} text-center m-0 p-1" style="border-radius: 5px;">
                                                                                                {{ $currentStatusLabel }}
                                                                                            </p>
                                                                                        @endif
                                                                                    </form>
                                                                                </td>
                                                                                <td>
                                                                                    @if ($calendar->status == 3)
                                                                                        <form action="{{ route('calendar.approve', $calendar->id) }}" method="POST">
                                                                                            @csrf
                                                                                            @method('PATCH')
                                                                                            <button type="submit" class="btn btn-primary">Duyệt ngay</button>
                                                                                        </form>
                                                                                    @elseif ($calendar->status == 10)
                                                                                        Đã duyệt
                                                                                    @elseif ($calendar->status == 4)
                                                                                        Đã hủy
                                                                                    @else
                                                                                        Chưa có kết quả
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    @if($calendar->priority == 'Low')
                                                                                        Thấp
                                                                                    @elseif($calendar->priority == 'Normal')
                                                                                        Bình thường
                                                                                    @elseif($calendar->priority == 'High')
                                                                                        Cao
                                                                                    @elseif($calendar->priority == 'Urgent')
                                                                                        Khẩn cấp
                                                                                    @else
                                                                                        {{ $calendar->priority }}
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    <a href="{{ $calendar->examSchedule?->stadium?->google_maps_url }}" target="_blank" rel="noopener noreferrer">
                                                                                        {{ $calendar->examSchedule?->stadium?->location }}
                                                                                    </a>
                                                                                </td>
                                                                                <td>{{ $calendar->location }}</td>
                                                                                <td>
                                                                                    @foreach($calendar->courses as $course)
                                                                                        {{ $course->code }}
                                                                                        @if (!$loop->last), @endif
                                                                                    @endforeach
                                                                                </td>
                                                                                <td>
                                                                                    @if($calendar->exam_fields_of_calendar && $calendar->exam_fields_of_calendar->isNotEmpty())
                                                                                        @foreach($calendar->exam_fields_of_calendar as $field)
                                                                                            <span class="badge bg-success">
                                                                                                {{ $field->name ?? 'Không có tên field' }}
                                                                                            </span>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <p>Không có dữ liệu môn thi</p>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    @foreach($calendar->students as $field)
                                                                                        <span class="badge bg-success">
                                                                                            {{ $field->name }} - {{ $field->student_code }}
                                                                                        </span>
                                                                                    @endforeach
                                                                                </td>
                                                                                <td>
                                                                                    @foreach($calendar->users as $user)
                                                                                        <span class="badge bg-success">
                                                                                            {{ $user->name }}
                                                                                        </span>
                                                                                    @endforeach
                                                                                </td>
                                                                                <td>{{ $calendar->description }}</td>
                                                                                <td>
                                                                                    <div class="d-flex gap-2 align-items-center">
                                                                                        @if (!in_array($calendar->status, [4, 10]))
                                                                                            @if (now()->lt(\Carbon\Carbon::parse($calendar->date_start)))
                                                                                                <a href="{{ route('calendars.edit', $calendar->id) }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-user-pen" title="Chỉnh sửa"></i></a>
                                                                                            @endif
                                                                                            <button class="btn btn-sm btn-info openResultModalBtn" 
                                                                                                data-calendar-id="{{ $calendar->id }}" 
                                                                                                data-type="{{ $calendar->type }}">
                                                                                                <i class="fa-solid fa-file-pen" title="Nhập kết quả"></i>
                                                                                            </button>
                                                                                        @endif
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{-- Phân trang riêng cho từng bảng --}}
                            <div class="mb-8">
                                @if($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $paginator->appends(request()->except("page_{$level}"))->links('pagination::bootstrap-5') }}
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
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
@endsection

@section('js')
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
                document.querySelectorAll('#study-fields, #exam-fields, #work-fields, #meeting-fields, #call-fields').forEach(function(field) {
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
                    const type = this.getAttribute('data-type');
                    const resultForm = document.getElementById('resultForm');
                    resultForm.setAttribute('data-calendar-id', calendarId);

                    fetch(`/calendars/${calendarId}/students`).then(response => response.json()).then(data => {
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
                                <label>Giờ học: <input type="text" name="students[${student.id}][hours]" class="form-control" value="${student.pivot.hours ?? ''}"></label>
                                <label>Số km: <input type="text" step="0.001" name="students[${student.id}][km]" class="form-control currency-input" value="${student.pivot.km ?? ''}"></label>
                                <label>Giờ ban đêm: <input type="text" step="0.001" name="students[${student.id}][night_hours]" class="form-control currency-input" value="${student.pivot.night_hours ?? ''}"></label>
                                <label>Giờ xe tự động: <input type="text" step="0.001" name="students[${student.id}][auto_hours]" class="form-control currency-input" value="${student.pivot.auto_hours ?? ''}"></label>
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
                                                        <select name="students[${student.id}][${field.id}][exam_status]" class="form-control">
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
                                                    <input type="text" name="students[${student.id}][${field.id}][remarks]" class="form-control" value="${remarks}">
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
                                                    <input type="text" name="students[${student.id}][remarks]" class="form-control" value="${student.pivot.remarks ?? ''}">
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
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const formData = new FormData(this);

                fetch(`/calendars/${calendarId}/results`, {
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
