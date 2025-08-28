@extends('layouts.admin')

@section('title', 'Danh sách học viên')
@section('css')
    <style>
        .alert {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .hidden {
            display: none;
        }
    </style>
@endsection
@section('content')
{{-- card filter --}}
{{-- <div class="card">
    <div class="card-body">
        <a href="{{ route('students.create') }}"
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 40px; height: 40px; font-size: 30px; bottom: 3%; right: 60px; z-index: 99;">
                +
        </a>
        <form method="GET" action="{{ route('students.index') }}" class="row">
            <div class="col-12 col-md-10 mb-2 row">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-2 mb-2 mt-2">
                    <label class="form-label fw-bold">Tìm kiếm khách hàng</label>
                    <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Tên, mã, SĐT, Email" />
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-2 mb-2 mt-2">
                    <label class="form-label fw-bold">Khóa học</label>
                    <select name="course_id" class="form-select">
                        <option value="">-- Khóa học --</option>
                        @foreach($courseAlls as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->code }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-2 mb-2 mt-2">
                    <label class="form-label fw-bold">Nguồn khách</label>
                    <select name="lead_source_id" class="form-select">
                        <option value="">-- Nguồn khách --</option>
                        @foreach($leadSources as $source)
                            <option value="{{ $source->id }}" {{ request('lead_source_id') == $source->id ? 'selected' : '' }}>
                                {{ $source->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-2 mb-2 mt-2">
                    <label class="form-label fw-bold">Nhân viên phụ trách</label>
                    <select name="sale_support_id" class="form-select">
                        <option value="">-- Nhân viên phụ trách --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('sale_support_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-2 mb-2 mt-2">
                    <label class="form-label fw-bold">Trạng thái học</label>
                    <select name="status" class="form-select">
                        <option value="">-- Trạng thái học --</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Chưa hoàn thành</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Đã bỏ</option>
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-2 mb-2 mt-2">
                    <label class="form-label fw-bold">Từ ngày</label>
                    <input type="date" placeholder="dd/mm/yyyy"class="form-control mb-2" name="created_from" value="{{ request('created_from') }}">
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-2 mb-2 mt-2">
                    <label class="form-label fw-bold">Đến ngày</label>
                    <input type="date" placeholder="dd/mm/yyyy"class="form-control" name="created_to" value="{{ request('created_to') }}">
                </div>
            </div>

            <div class="col-12 col-md-2 mb-2 d-flex align-items-center justify-content-center">
                <button type="submit" class="btn btn-primary w-48 me-2">Lọc</button>
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary w-48 mb-0">Bỏ lọc</a>
            </div>
        </form>
    </div>
</div> --}}
{{-- card content --}}
{{-- <div class="card">
    <div class="card-body">
        <div class="alert alert-primary d-flex align-items-center" role="alert">
            <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-20 me-1"></iconify-icon>
            <div class="lh-1"><strong>Tổng số học viên: </strong><strong>{{ $totalStudents }}</strong></div>
        </div>
        <div class="table-responsive">
            <table class="table mt-3 table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th class="text-nowrap">Mã học viên</th>
                        <th class="text-nowrap">Họ và tên</th>
                        <th class="text-nowrap">Ngày sinh</th>
                        <th class="text-nowrap">Giới tính</th>
                        <th class="text-nowrap">Số điện thoại</th>
                        <th>Hạng</th>
                        <th class="text-nowrap">CMT/CCCD</th>
                        <th class="text-nowrap">Địa chỉ</th>
                        <th class="text-nowrap">Số thẻ</th>
                        <th class="text-nowrap">Khóa học</th>
                        <th class="text-nowrap">Trạng thái</th>
                        <th class="text-nowrap">Ngày hoạt động</th>
                        <th class="text-nowrap text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $key = ($students->currentPage() - 1) * $students->perPage();
                    @endphp
                    @foreach ($students as $student)
                        <tr class="student-main-row" data-student-id="{{ $student->id }}">
                            <td>{{ ++$key }}</td>
                            <td>
                                <a style="font-weight: 600; color: #4C9AFF" href="#" data-bs-toggle="modal" data-bs-target="#showStudentModal" onclick="showStudent({{ $student->id }})">
                                    {{ $student->student_code }}
                                </a>
                            </td>
                            <td class="text-nowrap text-start">
                                <a style="font-weight: 600; color: #4C9AFF" href="#" class="toggle-detail" data-student-id="{{ $student->id }}">
                                    {{ $student->name }}
                                </a>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($student->dob)->format('d/m/Y') }}</td>
                            <td>
                                @if($student->gender == 'male')
                                    Nam
                                @elseif($student->gender == 'female')
                                    Nữ
                                @else
                                    Khác
                                @endif
                            </td>
                            <td>{{ $student->phone }}</td>
                            <td>
                                @if (!empty($student->courses) && $student->courses->count())
                                    @foreach ($student->courses as $item)
                                        <span class="badge bg-success">
                                            {{ ucfirst($item->ranking->name) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">
                                        Chưa tham gia học
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">{{ $student->identity_card }}</td>
                            <td class="text-start">{{ $student->address }}</td>
                            <td>
                                @if ($student->card_id)
                                    {{ $student->card_id }}
                                @else
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateCardModal"
                                        onclick='setStudentId({{ $student->id }}, {!! json_encode($student->name) !!}, {!! json_encode($student->student_code) !!})'>
                                        Gán
                                    </button>
                                @endif
                            </td>
                            <td>
                                @if (!empty($student->courses) && $student->courses->count())
                                    @foreach ($student->courses as $item)
                                        <a href="{{ route('student.course.action', ['student' => $student->id, 'course' => $item->id]) }}">
                                            <span class="badge bg-success">
                                                {{ ucfirst($item->code) }}
                                            </span>
                                        </a>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">
                                        Chưa tham gia học
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $student->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $student->created_at->format('d/m/Y') }}
                            </td>
                            <td class="text-nowrap">
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-warning m-1"><i class="fa-solid fa-user-pen"></i></a>
                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger m-1" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                        <tr id="details-{{ $student->id }}" class="collapse student-detail-row">
                            <td colspan="14">
                                <div>
                                    <div class="d-flex justify-content-between">
                                        <h5>Danh sách khóa học của học viên</h5>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal" data-student-id="{{ $student->id }}">
                                            Thêm Khóa Học
                                        </button>
                                    </div>
                                    @if($student->courses->isNotEmpty())
                                        <table class="table table-bordered mt-1">
                                            <thead>
                                                <tr class="text-center">
                                                    <th rowspan="2" class="align-middle">Mã KH</th>
                                                    <th rowspan="2" class="align-middle">Số BC</th>
                                                    <th rowspan="2" class="align-middle">Khai giảng</th>
                                                    <th rowspan="2" class="align-middle">Bế giảng</th>
                                                    <th rowspan="2" class="align-middle">Hợp đồng</th>
                                                    <th colspan="{{ count($exams) }}" class="align-middle">Tình trạng thi</th>
                                                    <th colspan="2" class="align-middle">Phiên học</th>
                                                    <th colspan="3" class="align-middle">Học phí</th>
                                                    <th rowspan="2" class="align-middle">Trạng thái</th>
                                                    <th rowspan="2" class="align-middle">Hành động</th>
                                                </tr>
                                                <tr>
                                                @foreach($exams as $exam)
                                                    <th class="align-middle">{{ $exam->name }}</th>
                                                @endforeach
                                                    <th class="align-middle">Giờ</th>
                                                    <th class="align-middle">Km</th>
                                                    <th class="align-middle">Tổng</th>
                                                    <th class="align-middle">Đã nạp</th>
                                                    <th class="align-middle">Còn thiếu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($student->courses as $course)
                                                    <tr>
                                                        <td><a href="{{ route('courses.index', ['code' => $course->code]) }}">{{ $course->code }}</a></td>
                                                        <td>{{ $course->number_bc }}</td>
                                                        <td>{{ $course->start_date ? $course->start_date->format('d/m/Y') : 'N/A' }}</td>
                                                        <td>{{ $course->end_date ? $course->end_date->format('d/m/Y') : 'N/A' }}</td>
                                                        <td>
                                                            @if ($course->pivot->contract_image)
                                                                @php
                                                                $filePath = 'storage/' . $course->pivot->contract_image;
                                                                $extension = strtolower(pathinfo($course->pivot->contract_image, PATHINFO_EXTENSION));
                                                                @endphp
                                                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                                    <a href="{{ asset($filePath) }}" target="_blank" class="btn btn-sm btn-outline-primary">Link ảnh</a>
                                                                @elseif($extension === 'pdf')
                                                                    <a href="{{ asset($filePath) }}" target="_blank" class="btn btn-sm btn-outline-primary">Xem file PDF</a>
                                                                @else
                                                                    Không hỗ trợ định dạng: {{ $extension }}
                                                                @endif
                                                            @else
                                                                Chưa có hình ảnh hợp đồng
                                                            @endif
                                                        </td>
                                                        @php
                                                            $requiredExamIds = $courseAlls->firstWhere('id', $course->id)?->examFields->pluck('id')->toArray() ?? [];
                                                            $examResults = $examsResults[$student->id][$course->id] ?? collect();
                                                        @endphp

                                                        @foreach($exams as $exam)
                                                            @if(in_array($exam->id, $requiredExamIds))
                                                                @php
                                                                    $result = $examResults->firstWhere('exam_field_id', $exam->id);
                                                                @endphp
                                                                <td class="text-center align-middle">
                                                                    <button class="btn btn-sm btn-link p-0 m-0 open-exam-modal"
                                                                        data-student-id="{{ $student->id }}"
                                                                        data-course-id="{{ $course->id }}"
                                                                        data-exam-field-id="{{ $exam->id }}"
                                                                        title="Xem chi tiết kết quả thi">
                                                                        {!!
                                                                            match($result->status ?? null) {
                                                                                0 => '<i class="fa-solid fa-hourglass-start text-warning"></i>',
                                                                                1 => '<i class="fa-solid fa-square-check text-success"></i>',
                                                                                default => '<i class="fa-solid fa-hourglass-start text-warning"></i>',
                                                                            }
                                                                        !!}
                                                                    </button>
                                                                </td>
                                                            @else
                                                                <td class="text-center align-middle">-</td>
                                                            @endif
                                                        @endforeach
                                                        <td>
                                                            <button class="btn btn-sm btn-info"
                                                                data-student-id="{{ $student->id }}"
                                                                data-course-id="{{ $course->id }}"
                                                                onclick="showStudyDetails(this)">
                                                                {{ $totalHours[$student->id][$course->id] ?? 0 }}/{{ $course->duration ?? '-' }}
                                                            </button>
                                                        </td>
                                                        <td>{{ $totalKm[$student->id][$course->id] ?? 0 }}/{{ $course->km ?? '-' }}</td>
                                                        <td>
                                                            @if(isset($remainingFees[$student->id][$course->id]))
                                                                {{ number_format($course->pivot->tuition_fee, 0, ',', '.') }} VND</td>
                                                            @else
                                                                0 VND
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($remainingFees[$student->id][$course->id]))
                                                                {{ number_format($courseFees[$student->id][$course->id], 0, ',', '.') }} VND</td>
                                                            @else
                                                                0 VND
                                                            @endif
                                                        <td>
                                                            @if(isset($remainingFees[$student->id][$course->id]))
                                                                {{ number_format($remainingFees[$student->id][$course->id], 0, ',', '.') }} VND
                                                            @else
                                                                0 VND
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge {{ $course->pivot->status == 1 ? 'bg-success' : ($course->pivot->status == 2 ? 'bg-warning' : ($course->pivot->status == 3 ? 'bg-primary' : 'bg-secondary')) }}">
                                                            {{ $course->pivot->status == 0 ? 'Chưa học' : ($course->pivot->status == 1 ? 'Đang học' : ($course->pivot->status == 2 ? 'Bỏ học' : 'Đã tốt nghiệp')) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <form action="{{ route('courses.removeStudent', ['course' => $course->id, 'student' => $student->id]) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-muted">Không có khóa học nào.</p>
                                    @endif
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between">
                                        <h5>Danh sách lịch của học viên</h5>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addActivitieModal" data-student-id="{{ $student->id }}" data-student-name="{{ $student->name }} - {{ $student->student_code }}">
                                            Thêm Lịch Học
                                        </button>
                                    </div>
                                    @if($student->calendars->isNotEmpty())
                                        <table class="table table-bordered mt-1">
                                            <thead>
                                                <tr class="text-center">
                                                    <th class="align-middle">Loại lịch</th>
                                                    <th class="align-middle">Tên lịch</th>
                                                    <th class="align-middle">Trạng thái</th>
                                                    <th class="align-middle">Ngày bắt đầu</th>
                                                    <th class="align-middle">Ngày kết thúc</th>
                                                    <th class="align-middle">Thời lượng</th>
                                                    <th class="align-middle">Địa điểm</th>
                                                    <th class="align-middle">Mô tả</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($student->calendars as $calendar)
                                                    <tr>
                                                        <td>{{ $calendar->type }}</td>
                                                        <td>{{ $calendar->name }}</td>
                                                        <td>
                                                            @php
                                                                $statusText = '';
                                                                if ($calendar->type === 'study') {
                                                                    $statusText = match ((int) $calendar->status) {
                                                                        1 => 'Đang chờ',
                                                                        2 => 'Đang diễn ra',
                                                                        3, 10 => 'Hoàn Thành',
                                                                        4 => 'Đã huỷ',
                                                                        default => 'Không xác định',
                                                                    };
                                                                } elseif ($calendar->type === 'exam') {
                                                                    $statusText = match ((int) $calendar->status) {
                                                                        1 => 'Đang chờ',
                                                                        2 => 'Đang diễn ra',
                                                                        3, 10 => 'Hoàn Thành',
                                                                        4 => 'Thi lại',
                                                                        default => 'Không xác định',
                                                                    };
                                                                }
                                                            @endphp
                                                            {{ $statusText }}
                                                        </td>
                                                        <td>{{ $calendar->date_start ?? 'N/A' }}</td>
                                                        <td>{{ $calendar->date_end ?? 'N/A' }}</td>
                                                        <td>
                                                            @if (in_array($calendar->status, [3, 10]) && $calendar->type === 'study' && isset($calendar->pivot->hours))
                                                                {{ $calendar->pivot->hours * 60 }} Phút
                                                            @else
                                                                {{ $calendar->duration }} Phút
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($calendar->type == 'exam')
                                                                <a href="{{ $calendar->examSchedule->stadium->google_maps_url ?? '#' }}" target="_blank">
                                                                    {{ $calendar->examSchedule->stadium->location ?? 'N/A' }}
                                                                </a>
                                                            @else
                                                                <a href="{{ $calendar->stadium->google_maps_url ?? '#' }}" target="_blank">
                                                                    {{ $calendar->stadium->location ?? 'N/A' }}
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td>{{ $calendar->description }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-muted">Không có lịch nào.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-2">
            {{ $students->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div> --}}

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a
            href="{{ route('students.create') }}"
            type="button"
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;"
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Thêm học viên</span>
        </a>
    </div>
@endsection

{{-- card filter --}}
<div class="card">
    <div class="card-body">
        <a href="{{ route('students.create') }}"
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 40px; height: 40px; font-size: 30px; bottom: 3%; right: 60px; z-index: 99;">
                +
        </a>
        <form method="GET" action="{{ route('students.index') }}" class="row">
            <div class="mb-2 row">
                <div class="col-12 col-md-4 col-lg-3 mb-2 mt-2">
                    <label class="form-label fw-bold">Tên học viên</label>
                    <select name="q" id="name_student" class="form-select">
                        <option value="">-- Chọn học viên --</option>
                        @foreach ($students as $student)
                            <option value="{{ request('q') ? request('q') == $student->id : $student->id }}">
                                {{ $student->name }} - {{ $student->student_code }}
                            </option>
                        @endforeach
                    </select>
                    {{-- <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Nhập tên học viên" /> --}}
                </div>

                <div class="col-6 col-lg-3 col-md-4 mb-2 mt-2 position-relative">
                    <label class="form-label fw-bold">Ngày bắt đầu</label>
                    <input
                        type="text"
                        placeholder="dd/mm/yyyy"
                        class="form-control real-date mb-2"
                        name="created_from"
                        autocomplete="off"
                        value="{{ request('created_from') ? \Carbon\Carbon::parse(request('created_from'))->format('d/m/Y') : '' }}"
                    >
                </div>
                <div class="col-6 col-lg-3 col-md-4 mb-2 mt-2 position-relative">
                    <label class="form-label fw-bold">Ngày kết thúc</label>
                    <input
                        type="text"
                        placeholder="dd/mm/yyyy"
                        class="form-control real-date" autocomplete="off"
                        name="created_to"
                        value="{{ request('created_to') ? \Carbon\Carbon::parse(request('created_to'))->format('d/m/Y') : '' }}"
                    >
                </div>
                <div class="col-12 col-lg-3 col-md-12 mb-2 mt-2">
                    <label for="">&nbsp;</label>
                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mb-1">
                            <b>Tìm Kiếm</b>
                        </button>
                        <div class="ms-2">
                            <a href="{{ route('students.index') }}" class="btn btn-outline-danger mb-1" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- card content --}}
<div class="card">
    <div class="card-body">
        <div class="alert alert-primary d-flex align-items-center" role="alert">
            <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-20 me-1"></iconify-icon>
            <div class="lh-1"><strong>Tổng số học viên: </strong><strong>{{ $totalStudents }}</strong></div>
        </div>
        <div class="table-responsive">
            @php
                $lyThuyetExams = $exams->where('type_label', 'Lý thuyết');
                $thucHanhExams = $exams->where('type_label', 'Thực hành');
                $totNghiepExams = $exams->where('type_label', 'Tốt nghiệp');
                $totalExamColumns = max($lyThuyetExams->count(), 1)
                      + max($thucHanhExams->count(), 1)
                      + max($totNghiepExams->count(), 1);
            @endphp

            <table class="table mt-3 table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle">STT</th>
                        {{-- <th rowspan="2" class="text-nowrap align-middle">Mã học viên</th> --}}
                        <th rowspan="2" class="text-nowrap align-middle">Họ và tên</th>
                        <th rowspan="2" class="text-nowrap align-middle">Ngày sinh</th>
                        <th rowspan="2" class="text-nowrap align-middle">Giới tính</th>
                        <th rowspan="2" class="text-nowrap align-middle">SĐT</th>
                        <th rowspan="2" class="text-nowrap align-middle">CMT/CCCD</th>
                        <th rowspan="2" class="text-nowrap align-middle">Địa chỉ</th>
                        <th rowspan="2" class="text-nowrap align-middle">Khám sức khoẻ</th>
                        <th rowspan="2" class="text-nowrap align-middle">Số thẻ</th>
                        <th rowspan="2" class="text-nowrap align-middle">Khóa học</th>
                        <th rowspan="2" class="align-middle">Hạng</th>
                        <th rowspan="2" class="text-nowrap align-middle">Trạng thái</th>
                        <th rowspan="2" class="text-nowrap align-middle">Ngày hoạt động</th>
                        <th rowspan="3" class="align-middle">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php $key = ($students->currentPage() - 1) * $students->perPage(); @endphp
                    @foreach ($students as $student)
                        <tr class="student-main-row" data-student-id="{{ $student->id }}">
                            <td>{{ ++$key }}</td>
                            <td class="text-nowrap text-start">
                                <a href="{{ route('students.show', $student->id) }}">
                                    {{ $student->name }}
                                </a>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($student->dob)->format('d/m/Y') }}</td>
                            <td>
                                @if($student->gender == 'male') Nam
                                @elseif($student->gender == 'female') Nữ
                                @else Khác @endif
                            </td>
                            <td>{{ $student->phone }}</td>
                            <td>{{ $student->identity_card ?? '--' }}</td>
                            <td class="text-start" style="min-width: 150px;">{{ $student->address }}</td>

                            {{-- Ngày khám sức khỏe: lấy từ khóa học đầu tiên nếu có --}}
                            <td>
                                @if($student->courses->isNotEmpty())
                                    {{ \Carbon\Carbon::parse($student->courses->first()->pivot->health_check_date)->format('d/m/Y') }}
                                @else
                                    <span class="badge bg-secondary">Chưa tham gia học</span>
                                @endif
                            </td>

                            {{-- Thẻ học viên --}}
                            <td>
                                @if ($student->card_id)
                                    {{ $student->card_id }}
                                @else
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateCardModal"
                                        onclick='setStudentId({{ $student->id }}, {!! json_encode($student->name) !!}, {!! json_encode($student->student_code) !!})'>
                                        Gán
                                    </button>
                                @endif
                            </td>

                            {{-- Danh sách khóa học --}}
                            <td>
                                @if($student->courses->isNotEmpty())
                                    @foreach($student->courses as $course)
                                        @if ($course->id == 99999999)
                                            <a href="{{ route('student.course.action', ['student' => $student->id, 'course' => 99999999]) }}">
                                                <span class="badge bg-success">
                                                    @if (!empty($student->ranking_id) && $student->ranking)
                                                        {{ $student->ranking->name . ' chưa xếp' }}
                                                    @else
                                                        {{ ucfirst($course->code) }}
                                                    @endif
                                                </span>
                                            </a>
                                        @else
                                            <a href="{{ route('student.course.action', ['student' => $student->id, 'course' => $course->id]) }}">
                                                <span class="badge bg-success">{{ ucfirst($course->code) }}</span>
                                            </a>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">Chưa tham gia học</span>
                                @endif
                            </td>

                            {{-- Xếp hạng khóa học --}}
                            <td>
                                @if($student->courses->isNotEmpty())
                                    @foreach($student->courses->unique(fn($course) => $course->ranking?->name)->filter() as $course)
                                        <span class="badge bg-success">
                                            @if (isset($course->ranking))
                                                {{ ucfirst($course->ranking?->name) }}
                                            @else
                                                {{ ucfirst($student->ranking?->name ?? '') }}
                                            @endif
                                        </span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">Chưa tham gia học</span>
                                @endif
                            </td>


                            {{-- Trạng thái --}}
                            <td>
                                @if ($student->status == 'active')
                                    <span class="badge bg-success">Đang học</span>
                                @elseif ($student->status == 'inactive')
                                    <span class="badge bg-success">Nghỉ</span>
                                @endif
                            </td>

                            {{-- Ngày tạo --}}
                            <td>{{ $student->created_at->format('d/m/Y') }}</td>

                            {{-- Hành động --}}
                            <td class="text-nowrap">
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-info m-1" style="padding: 2px 12px;">
                                    <i class="mdi mdi-eye-outline"></i>
                                </a>
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-warning m-1"><i class="fa-solid fa-user-pen"></i></a>
                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger m-1" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-2">
            {{ $students->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Thêm khóa học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('courses.addStudent') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="modal_name" value="addStudentModal">
                    <input type="hidden" name="student_id[]" value="">
                        <div class="row" id="student-fields-container">
                            <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 col-xxl-2">
                                <label for="course_id" class="form-label">Chọn Khóa Học</label>
                                <select name="course_id" class="form-control student-select">
                                    <option value="">Chọn khóa học</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 col-xxl-2 position-relative">
                                <label for="contract_date" class="form-label">Ngày ký hợp đồng</label>
                                <input type="text" placeholder="dd/mm/yyyy"name="contract_date[]" class="form-control real-date" autocomplete="off">
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 col-xxl-2">
                                <label for="contract_image">Ảnh hợp đồng</label>
                                <input type="file" name="contract_image[]" class="form-control @error('contract_image') is-invalid @enderror">
                                @error('contract_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 col-xxl-2 position-relative">
                                <label for="health_check_date" class="form-label">Ngày khám sức khỏe</label>
                                <input type="text" placeholder="dd/mm/yyyy"name="health_check_date[]" class="form-control real-date" autocomplete="off">
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 col-xxl-2">
                                <label for="teacher_id" class="form-label">Chọn giáo viên</label>
                                <select name="teacher_id[]" class="form-control">
                                    <option value="">Chọn giáo viên</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 col-xxl-2">
                                <label for="stadium_id" class="form-label">Chọn sân tập</label>
                                <select name="stadium_id[]" class="form-control">
                                    <option value="">Chọn sân tập</option>
                                    @foreach($stadiums as $stadium)
                                        <option value="{{ $stadium->id }}">{{ $stadium->location }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    <button type="submit" class="btn btn-success mt-3">Thêm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addActivitieModal" tabindex="-1" aria-labelledby="addActivitieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addActivitieModalLabel">Thêm Mới Lịch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('calendars.store') }}" method="POST">
                @csrf
                <input type="hidden" name="modal_name" value="addActivitieModal">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="row">
                                <!-- Loại Lịch -->
                                <div class="form-group mb-3">
                                    <label class="form-label">Loại Lịch</label>
                                    <div>
                                        <label class="me-3">
                                            <input type="radio" name="type" value="study" checked required> Học
                                        </label>
                                        <label class="me-3">
                                            <input type="radio" name="type" value="exam" required> Thi
                                        </label>
                                    </div>
                                    @error('type')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Trường Tên Lịch -->
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Tên Lịch</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" required />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Mức Độ Ưu Tiên -->
                                <div class="form-group mb-3 col-12 col-md-6">
                                    <label for="priority" class="form-label">Mức Độ Ưu Tiên</label>
                                    <select name="priority" id="priority" class="form-control @error('priority') is-invalid @enderror" required>
                                        <option value="Low">Thấp</option>
                                        <option value="Normal">Bình Thường</option>
                                        <option value="High">Cao</option>
                                        <option value="Urgent">Khẩn Cấp</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Trường Địa Điểm -->
                                <div class="form-group mb-3 col-12 col-md-6">
                                    <label for="location" class="form-label">Địa Điểm:</label>
                                    <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror"/>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Ngày Bắt Đầu -->
                                <div class="form-group mb-3 col-12 col-md-6" id="date-start-field">
                                    <label for="date_start" class="form-label">Ngày Bắt Đầu</label>
                                    <input type="datetime-local" name="date_start" id="date_start" class="form-control @error('date_start') is-invalid @enderror" required />
                                    @error('date_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Ngày Kết Thúc -->
                                <div class="form-group mb-3 col-12 col-md-6" id="date-end-field">
                                    <label for="date_end" class="form-label">Ngày Kết Thúc</label>
                                    <input type="datetime-local" name="date_end" id="date_end" class="form-control @error('date_end') is-invalid @enderror" required />
                                    @error('date_end')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-12 col-md-6 position-relative" id="exam-date-time" style="display: none;">
                                    <div class="mb-3">
                                        <label for="date" class="form-label">Ngày</label>
                                        <input type="text" placeholder="dd/mm/yyyy"name="date" autocomplete="off" id="date" class="form-control real-date @error('date') is-invalid @enderror" />
                                        @error('date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="time" class="form-label">Buổi thi</label>
                                        <select name="time" id="time" class="form-control @error('time') is-invalid @enderror">
                                            <option value="">-- Chọn buổi thi --</option>
                                            <option value="1" {{ old('time') == '1' ? 'selected' : '' }}>Buổi sáng</option>
                                            <option value="2" {{ old('time') == '2' ? 'selected' : '' }}>Buổi chiều</option>
                                            <option value="3" {{ old('time') == '3' ? 'selected' : '' }}>Cả ngày</option>
                                        </select>
                                        @error('time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Mô Tả -->
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Mô Tả</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <!-- Trường riêng cho lịch học -->
                            <div id="study-fields" class="mb-3" style="display: none;">
                                <label for="learn_course_id" class="form-label">Khóa học</label>
                                <select name="learn_course_id" id="learn_course_id" class="form-control @error('learn_course_id') is-invalid @enderror">
                                    <option value="">Chọn khóa học</option>
                                </select>
                                @error('learn_course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="learning_id" class="form-label mt-2">Môn học</label>
                                <select name="learning_id" id="learning_id" class="form-control @error('learning_id') is-invalid @enderror">
                                    <option value="">-- Vui lòng chọn khóa học trước --</option>
                                </select>
                                @error('learning_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="learn_teacher_id" class="form-label mt-2">Giáo viên</label>
                                <select name="learn_teacher_id" id="learn_teacher_id" class="form-control @error('learn_teacher_id') is-invalid @enderror">
                                    <option value="">-- Vui lòng chọn thời gian trước --</option>
                                </select>
                                @error('learn_teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="learn_student_id" class="form-label mt-2">Học viên</label>
                                <select name="learn_student_id[]" id="learn_student_id_select" class="w-full form-control @error('learn_student_id') is-invalid @enderror" multiple>
                                    <option value="">-- Vui lòng chọn khóa học, thời gian của lịch trước --</option>
                                </select>
                                @error('learn_student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="alert-message" class="hidden alert alert-danger"></div>

                                <div class="mb-3">
                                    <label for="stadium_id" class="form-label">Sân tập</label>
                                    <select name="stadium_id" id="stadium_id" class="form-select">
                                        <option value="">Chọn sân</option>
                                        @foreach ($stadiums as $stadium)
                                            <option value="{{ $stadium->id }}" {{ old('stadium_id') == $stadium->id ? 'selected' : '' }}>
                                                {{ $stadium->location }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="vehicle_select" class="form-label">Xe Học</label>
                                    <select name="vehicle_select" id="vehicle_select" class="form-select">
                                        <option value="">-- Vui lòng chọn thời gian bắt đầu, kết thúc trước --</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Trường riêng cho lịch thi -->
                            <div id="exam-fields" class="mb-3" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="exam_course_type" class="form-label">Kỳ thi</label>
                                    <select name="exam_course_type" id="exam_course_type" class="form-control @error('exam_course_type') is-invalid @enderror">
                                        <option value="">Chọn kỳ thi</option>
                                        <option value="1">Thi tốt nghiệp</option>
                                        <option value="2">Thi sát hạch</option>
                                    </select>
                                    @error('exam_course_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="exam_fee" class="form-label">Lệ phí thi</label>
                                    <input type="text" name="exam_fee" id="exam_fee" class="form-control currency-input @error('exam_fee') is-invalid @enderror"/>
                                    @error('exam_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3 position-relative">
                                    <label for="exam_fee_deadline" class="form-label">Hạn nộp</label>
                                    <input type="text" placeholder="dd/mm/yyyy"name="exam_fee_deadline" autocomplete="off" id="exam_fee_deadline" class="form-control real-date @error('exam_fee_deadline') is-invalid @enderror"/>
                                    @error('exam_fee_deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exam_course_id" class="form-label">Khóa học</label>
                                    <select name="exam_course_id" id="exam_course_id" class="form-control @error('exam_course_id') is-invalid @enderror">
                                        <option value="">Chọn khóa học</option>
                                    </select>
                                    @error('exam_course_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="exam_id" class="form-label">Môn thi</label>
                                    <select name="exam_id[]" id="exam_id" class="form-control @error('exam_id') is-invalid @enderror" multiple>
                                        <option value="">-- Vui lòng chọn khóa học trước --</option>
                                    </select>
                                    @error('exam_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="exam_teacher_id" class="form-label">Giáo viên</label>
                                    <select name="exam_teacher_id" id="exam_teacher_id" class="form-control @error('exam_teacher_id') is-invalid @enderror">
                                        <option value="">-- Vui lòng chọn thời gian trước --</option>
                                    </select>
                                    @error('exam_teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="exam_student_id" class="form-label">Học viên</label>
                                    <select name="exam_student_id[]" id="exam_student_id_select" class="form-control @error('exam_student_id') is-invalid @enderror" multiple>
                                        <option value="" >-- Vui lòng chọn khóa học, thời gian của lịch trước --</option>
                                    </select>
                                    @error('exam_student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="alert-message" class="hidden alert alert-danger"></div>

                                <div id="student-inputs" class="mb-3 space-y-4"></div>

                                <div class="mb-3">
                                    <label for="exam_schedule_id" class="form-label">Sân thi</label>
                                    <select name="exam_schedule_id" id="exam_schedule_id" class="form-select">
                                        <option value="">-- Vui lòng chọn thời gian bắt đầu, kết thúc, khóa học và môn học trước --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Nút Submit -->
                        <button type="submit" class="btn btn-primary mt-3">Thêm Lịch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal add cardID -->
<div class="modal fade" id="updateCardModal" tabindex="-1" aria-labelledby="updateCardLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateCardLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateCardForm">
                    @csrf
                    <input type="hidden" id="student_id" name="student_id">
                    <div class="mb-3">
                        <label for="card_id" class="form-label">Nhập mã thẻ:</label>
                        <input type="text" class="form-control" id="card_id" name="card_id">
                        <div id="card_id_error" class="text-danger mt-1"></div>
                    </div>
                    <button type="submit" class="btn btn-success">Lưu</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal show student -->
<div class="modal fade" id="showStudentModal" tabindex="-1" aria-labelledby="showStudentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showStudentLabel">Thông tin học viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> <span id="show_student_id"></span></p>
                <p><strong>Mã HV:</strong> <span id="student_student_code"></span></p>
                <p><strong>Mã thẻ:</strong> <span id="student_card_id"></span></p>
                <p><strong>ID sát hạch:</strong> <span id="student_trainee_id"></span></p>
                <p><strong>Họ tên:</strong> <span id="student_name"></span></p>
                <p><strong>Email:</strong> <span id="student_email"></span></p>
                <p><strong>Số điện thoại:</strong> <span id="student_phone"></span></p>
                <p><strong>Địa chỉ:</strong> <span id="student_address"></span></p>
                <p><strong>Giới tính:</strong> <span id="student_gender"></span></p>
                <p><strong>Ngày sinh:</strong> <span id="student_dob"></span></p>
                <p><strong>CCCD/CMND:</strong> <span id="student_identity_card"></span></p>
                <p><strong>Mô tả:</strong> <span id="student_description"></span></p>
                <p><strong>Ngày trở thành HV:</strong> <span id="student_became_student_at"></span></p>
                <p><strong>Tên người hỗ trợ:</strong> <span id="student_sale_support"></span></p>
                <p><strong>Tên nguồn khách hàng:</strong> <span id="student_lead_source"></span></p>
                <p><strong>Người xác nhận:</strong> <span id="student_converted_by"></span></p>
            </div>
        </div>
    </div>
</div>

<!-- Modal chi tiết kết quả thi -->
<div class="modal fade" id="examDetailModal" tabindex="-1" role="dialog" aria-labelledby="examDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Chi tiết kết quả thi</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Đóng">
            <span aria-hidden="true"></span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered" id="examDetailTable">
            <thead>
                <tr>
                    <th>Lần thi</th>
                    <th>Tên lịch</th>
                    <th>Ngày thi</th>
                    <th>Điểm</th>
                    <th>Ghi chú</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <!-- sẽ được đổ bằng JS -->
            </tbody>
            </table>
        </div>
        </div>
    </div>
</div>

<!-- Modal chi tiết kết quả học -->
<div class="modal fade" id="studyDetailsModal" tabindex="-1" aria-labelledby="studyDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studyDetailsLabel">Chi tiết lịch học theo môn học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <div id="study-details-content">
                    <!-- Nội dung sẽ được render bằng JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Js của Modal show details of exam -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.open-exam-modal').forEach(function (button) {
            button.addEventListener('click', function () {
                const studentId = this.dataset.studentId;
                const courseId = this.dataset.courseId;
                const examFieldId = this.dataset.examFieldId;

                const url = `/students/${studentId}/exam-details/${courseId}/${examFieldId}`;

                fetch(url)
                    .then(response => response.json())
                    .then(res => {
                        const tbody = document.querySelector('#examDetailTable tbody');
                        tbody.innerHTML = '';

                        if (res.data.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="7" class="text-center">Chưa có dữ liệu</td></tr>';
                        } else {
                            res.data.forEach(item => {
                                tbody.innerHTML += `
                                    <tr>
                                        <td>${item.attempt_number}</td>
                                        <td>${item.calendar_name}</td>
                                        <td>${item.date}</td>
                                        <td>${item.correct_answers ?? '-'}</td>
                                        <td>${item.remarks ?? '-'}</td>
                                        <td>${item.exam_status}</td>
                                    </tr>
                                `;
                            });
                        }
                        const modal = new bootstrap.Modal(document.getElementById('examDetailModal'));
                        modal.show();
                    });
            });
        });
    });
</script>

<!-- Js của Modal show details of study -->
<script>
    function showStudyDetails(button) {
        const studentId = button.dataset.studentId;
        const courseId = button.dataset.courseId;
        const modal = new bootstrap.Modal(document.getElementById('studyDetailsModal'));
        const content = document.getElementById('study-details-content');

        content.innerHTML = '<p>Đang tải dữ liệu...</p>';

        fetch(`/students/${studentId}/study-details/${courseId}`)
            .then(response => response.json())
            .then(data => {
                renderStudyDetails(data.data);
            })
            .catch(error => {
                content.innerHTML = '<p class="text-danger">Lỗi tải dữ liệu.</p>';
                console.error(error);
            });

        modal.show();
    }

    function renderStudyDetails(groups) {
        const container = document.getElementById('study-details-content');
        container.innerHTML = '';

        if (Object.keys(groups).length === 0) {
            container.innerHTML = '<p>Không có dữ liệu lịch học.</p>';
            return;
        }

        Object.values(groups).forEach(group => {
            const groupEl = document.createElement('div');
            groupEl.classList.add('mb-4');

            groupEl.innerHTML = `
                <h5>${group.learning_field_name || 'Chưa xác định'}</h5>
                <p><strong>Tổng giờ:</strong> ${group.total_hours} giờ | <strong>Tổng km:</strong> ${group.total_km} km</p>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Giờ</th>
                            <th>Km</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${group.items.map(item => {
                            const start = new Date(item.date_start);
                            const end = new Date(item.date_end);
                            return `
                            <tr>
                                <td>${start.toLocaleString('vi-VN', { timeZone: 'Asia/Ho_Chi_Minh' })}</td>
                                <td>${end.toLocaleString('vi-VN', { timeZone: 'Asia/Ho_Chi_Minh' })}</td>
                                <td>${item.hours}</td>
                                <td>${item.km}</td>
                                <td>${item.remarks || '-'}</td>
                            </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            `;

            container.appendChild(groupEl);
        });
    }

    function getStatusText(status) {
        switch (status) {
            case 2:
                return 'Đã bỏ';
            case 1:
                return 'Hoàn thành';
            case 0:
                return 'Chưa hoàn thành';
            default:
                return 'Chưa xác định';
        }
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('vi-VN', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    }
</script>

<!-- Js của Modal add cardID -->
<script>
    function setStudentId(studentId,studentName,studentCode) {
        document.getElementById("student_id").value = studentId;
        document.getElementById("updateCardLabel").innerText = `Cập nhật mã thẻ ${studentName} - ${studentCode}`;
    }

    document.getElementById("updateCardForm").addEventListener("submit", function(event) {
        event.preventDefault();

        let studentId = document.getElementById("student_id").value;
        let cardId = document.getElementById("card_id").value;
        let errorDiv = document.getElementById("card_id_error");
        errorDiv.textContent = "";

        fetch(`/students/${studentId}/update-card`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ card_id: cardId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Cập nhật thành công!");
                location.reload();
            } else {
                if (data.errors && data.errors.card_id) {
                    errorDiv.textContent = data.errors.card_id[0];
                } else {
                    alert(data.message || "Lỗi khi cập nhật!");
                }
            }
        });
    });

    document.getElementById("updateCardModal").addEventListener("hidden.bs.modal", function () {
        document.getElementById("updateCardForm").reset();
        document.getElementById("card_id_error").textContent = "";
    });
</script>

<script>
    function showStudent(studentId) {
        fetch(`/students/${studentId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById("show_student_id").textContent = data.id;
                document.getElementById("student_student_code").textContent = data.student_code || 'N/A';
                document.getElementById("student_card_id").textContent = data.card_id || 'N/A';
                document.getElementById("student_trainee_id").textContent = data.trainee_id || 'N/A';
                document.getElementById("student_name").textContent = data.name || 'N/A';
                document.getElementById("student_email").textContent = data.email || 'N/A';
                document.getElementById("student_phone").textContent = data.phone || 'N/A';
                document.getElementById("student_address").textContent = data.address || 'N/A';
                document.getElementById("student_gender").textContent = data.gender || 'N/A';
                document.getElementById("student_dob").textContent = data.dob || 'N/A';
                document.getElementById("student_identity_card").textContent = data.identity_card || 'N/A';
                document.getElementById("student_description").textContent = data.description || 'N/A';
                document.getElementById("student_became_student_at").textContent = data.became_student_at || 'N/A';
                document.getElementById("student_sale_support").textContent = data.sale_support || 'N/A';
                document.getElementById("student_lead_source").textContent = data.lead_source || 'N/A';
                document.getElementById("student_converted_by").textContent = data.converted_by || 'N/A';
            })
            .catch(error => console.error("Lỗi khi lấy thông tin học viên:", error));
    }
</script>

@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#name_student').select2({
                placeholder: "-- Chọn học viên --",
                allowClear: true,
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let isModalActive = false;
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('show.bs.modal', () => {
                    isModalActive = true;
                });

                modal.addEventListener('hidden.bs.modal', () => {
                    setTimeout(() => {
                        isModalActive = false;
                    }, 300);
                });
            });
            const buttons = document.querySelectorAll('.toggle-detail');

            buttons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.stopPropagation();

                    const studentId = this.getAttribute('data-student-id');
                    const currentDetailRow = document.getElementById('details-' + studentId);
                    const currentMainRow = document.querySelector(`.student-main-row[data-student-id="${studentId}"]`);

                    const allDetailRows = document.querySelectorAll('.student-detail-row');
                    const allMainRows = document.querySelectorAll('.student-main-row');

                    const isOpen = currentDetailRow.classList.contains('show');

                    if (!isOpen) {
                        // Đóng tất cả các chi tiết
                        allDetailRows.forEach(row => {
                            if (row !== currentDetailRow) {
                                bootstrap.Collapse.getOrCreateInstance(row, { toggle: false }).hide();
                            }
                        });
                        // Ẩn tất cả dòng chính
                        allMainRows.forEach(row => {
                            row.style.display = row === currentMainRow ? '' : 'none';
                        });
                        // Hiện dòng chính và chi tiết hiện tại
                        currentMainRow.style.display = '';
                        bootstrap.Collapse.getOrCreateInstance(currentDetailRow, { toggle: false }).show();
                    } else {
                        // Đóng chi tiết hiện tại
                        bootstrap.Collapse.getOrCreateInstance(currentDetailRow, { toggle: false }).hide();
                        // Hiển thị lại tất cả các hàng chính
                        allMainRows.forEach(row => {
                            row.style.display = '';
                        });
                    }
                });
            });

            document.addEventListener('click', function (e) {
                if (isModalActive) return;
                const isInsideToggle = e.target.closest('.toggle-detail');
                const isInsideCardBody = e.target.closest('.card-body');
                const isModalOpen = document.querySelector('.modal.show');

                if (!isInsideToggle && !isInsideCardBody) {
                    document.querySelectorAll('.student-detail-row').forEach(row => {
                        bootstrap.Collapse.getOrCreateInstance(row, { toggle: false }).hide();
                    });
                    document.querySelectorAll('.student-main-row').forEach(row => {
                        row.style.display = '';
                    });
                }
            });
        });
    </script>
    <script>
        $('#addStudentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var studentId = button.data('student-id');
            var courseSelect = $(this).find('select[name="course_id"]');

            $(this).find('input[name="student_id[]"]').val(studentId);
            courseSelect.empty().append('<option value="">Đang tải khóa học...</option>');
            fetch(`/students/${studentId}/available-courses`)
            .then(response => response.json())
            .then(courses => {
                courseSelect.empty().append('<option value="">Chọn khóa học</option>');
                courses.forEach(function(course) {
                    courseSelect.append(`<option value="${course.id}">${course.code}</option>`);
                });
            })
            .catch(error => {
                courseSelect.empty().append('<option value="">Lỗi khi tải dữ liệu</option>');
                console.error('Lỗi khi tải khóa học:', error);
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <script>
        document.addEventListener("DOMContentLoaded", function () {
        let defaultStudentId = '';
        let defaultStudentName = '';

        const radios = document.querySelectorAll('input[name="type"]');
        const startTimeInput = document.getElementById('date_start');
        const endTimeInput = document.getElementById('date_end');
        const dateInput = document.getElementById('date');
        const timeInput = document.getElementById('time');
        const examCourseSelect = document.getElementById('exam_course_id');
        const examSelect = document.getElementById('exam_id');
        const dateStartField = document.getElementById('date-start-field');
        const dateEndField = document.getElementById('date-end-field');
        const examDateTimeField = document.getElementById('exam-date-time');

        // Reset modal khi đóng
        $('#addActivitieModal').on('hidden.bs.modal', function () {
            var modalForm = $(this).find('form');
            modalForm[0].reset();
            $('#learn_course_id').val('').trigger('change');
            $('#exam_course_id').val('').trigger('change');
            $('#vehicle_select').val('').trigger('change');
            $('#learn_student_id_select').val('').trigger('change');
            $('#exam_student_id_select').val('').trigger('change');
            $('#learn_teacher_id').val('').trigger('change');
            $('#exam_teacher_id').val('').trigger('change');
            $('#learn_student_id_select').empty();
            $('#exam_student_id_select').empty();
            $('#learn_course_id').empty().append(new Option('-- Chọn khóa học --', ''));
            $('#exam_course_id').empty().append(new Option('-- Chọn khóa học --', ''));
            $('#learn_teacher_id').empty().append(new Option('Chọn giáo viên', ''));
            $('#exam_teacher_id').empty().append(new Option('Chọn giáo viên', ''));
            $('#student-inputs').empty();
            $('#alert-message').addClass('hidden');
        });

        // Khởi tạo modal khi mở
        $('#addActivitieModal').on('show.bs.modal', function (event) {
            var modal = $(this);
            $('input[name="type"][value="study"]').prop('checked', true).trigger('change');
            updateForm('study');

            var button = $(event.relatedTarget);
            defaultStudentId = button.data('student-id');
            defaultStudentName = button.data('student-name') || `Học viên ID ${defaultStudentId}`;

            // Thêm học viên mặc định vào select
            var selectLearn = modal.find('#learn_student_id_select');
            var selectExam = modal.find('#exam_student_id_select');
            selectLearn.empty();
            selectExam.empty();
            selectLearn.append(new Option(`${defaultStudentName}`, defaultStudentId, true, true));
            selectExam.append(new Option(`${defaultStudentName}`, defaultStudentId, true, true));

            // Lấy danh sách khóa học của học viên
            $.ajax({
                url: `/students/${defaultStudentId}/courses`,
                method: 'GET',
                success: function (courses) {
                    var learnSelect = $('#learn_course_id');
                    var examSelect = $('#exam_course_id');
                    learnSelect.empty().append(new Option('-- Chọn khóa học --', ''));
                    examSelect.empty().append(new Option('-- Chọn khóa học --', ''));
                    courses.forEach(course => {
                        learnSelect.append(new Option(course.code, course.id));
                        examSelect.append(new Option(course.code, course.id));
                    });
                },
                error: function () {
                    showAlert('Không thể lấy danh sách khóa học của học viên.');
                }
            });
        });

        function updateForm(selectedType) {
            document.querySelectorAll('#study-fields, #exam-fields, #work-fields, #meeting-fields, #call-fields').forEach(function(field) {
                field.style.display = 'none';
            });

            startTimeInput.value = '';
            endTimeInput.value = '';
            if (dateInput) dateInput.value = '';
            if (timeInput) timeInput.value = '';

            if (selectedType === 'exam') {
                dateStartField.style.display = 'none';
                dateEndField.style.display = 'none';
                examDateTimeField.style.display = 'block';
                startTimeInput.disabled = true;
                endTimeInput.disabled = true;
                if (dateInput) dateInput.disabled = false;
                if (timeInput) timeInput.disabled = false;
            } else {
                dateStartField.style.display = 'block';
                dateEndField.style.display = 'block';
                examDateTimeField.style.display = 'none';
                startTimeInput.disabled = false;
                endTimeInput.disabled = false;
                if (dateInput) dateInput.disabled = true;
                if (timeInput) timeInput.disabled = true;
            }

            if (selectedType === 'study') {
                document.getElementById('study-fields').style.display = 'block';
                $('#learn_course_id').select2({
                    dropdownParent: $('#addActivitieModal'),
                    placeholder: "Chọn khóa học",
                    allowClear: true,
                    width: '100%'
                }).on('change', function () {
                    const startTime = startTimeInput.value;
                    const endTime = endTimeInput.value;
                    const courseId = $(this).val();
                    fetchAndUpdate(courseId, 'study', startTime, endTime);
                });
                $('#learn_student_id_select').select2({
                    dropdownParent: $('#addActivitieModal'),
                    placeholder: "Chọn học viên",
                    allowClear: true,
                    multiple: true,
                    width: '100%'
                });
            } else if (selectedType === 'exam') {
                document.getElementById('exam-fields').style.display = 'block';
                $('#exam_course_id').select2({
                    dropdownParent: $('#addActivitieModal'),
                    placeholder: "Chọn khóa học",
                    allowClear: true,
                    width: '100%'
                }).on('change', function () {
                    const date = dateInput.value;
                    const time = timeInput.value;
                    const courseId = $(this).val();
                    fetchAndUpdate(courseId, 'exam', date, time);
                });
                $('#exam_student_id_select').select2({
                    dropdownParent: $('#addActivitieModal'),
                    placeholder: "Chọn học viên",
                    allowClear: true,
                    multiple: true,
                    width: '100%'
                }).on('change', function () {
                    const selectedOptions = $(this).val() || [];

                    // Kiểm tra trùng lặp với học viên mặc định
                    if (defaultStudentId && selectedOptions.includes(defaultStudentId)) {
                        showAlert(`Học viên "${defaultStudentName}" đã được chọn mặc định. Vui lòng không chọn lại.`);
                        selectedOptions.splice(selectedOptions.indexOf(defaultStudentId), 1);
                        $(this).val(selectedOptions).trigger('change');
                        return;
                    }

                    // Xóa các input cũ (trừ học viên mặc định)
                    const $container = $('#student-inputs');
                    $container.find('.student-input-group').each(function () {
                        const studentId = $(this).data('student-id');
                        if (studentId != defaultStudentId && !selectedOptions.includes(studentId.toString())) {
                            $(this).remove();
                        }
                    });

                    // Thêm input mới cho các học viên được chọn
                    selectedOptions.forEach(function (studentId) {
                        if ($container.find(`.student-input-group[data-student-id="${studentId}"]`).length === 0) {
                            const studentName = $(`#exam_student_id_select option[value="${studentId}"]`).text();
                            const groupHtml = `
                                <div class="student-input-group border p-4 rounded bg-gray-50" data-student-id="${studentId}">
                                    <div class="mb-2 font-semibold">${studentName}</div>
                                    <div class="mb-2">
                                        <label class="block text-sm mb-1">Số báo danh:</label>
                                        <input type="text" name="students[${studentId}][exam_number]" class="w-full rounded border-gray-300">
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
        }

        // Gán sự kiện cho radios
        radios.forEach(radio => {
            radio.addEventListener("change", function () {
                updateForm(this.value);
            });
        });

        const checkedRadio = document.querySelector('input[name="type"]:checked');
        if (checkedRadio) {
            updateForm(checkedRadio.value);
        }

        // Hàm hiển thị thông báo
        function showAlert(message) {
            const alertDiv = document.getElementById('alert-message');
            alertDiv.textContent = message;
            alertDiv.classList.remove('hidden');
            setTimeout(() => alertDiv.classList.add('hidden'), 5000);
        }

        // Xử lý AJAX khi chọn khóa học
        function fetchAndUpdate(courseId, type, dateStart, dateEnd) {
            if (!courseId) return;

            let url = `/course-data/${type}/${courseId}`;
            if (type === 'exam') {
                url += `?date=${encodeURIComponent(dateStart)}&time=${encodeURIComponent(dateEnd)}`;
            } else {
                url += `?date_start=${encodeURIComponent(dateStart)}&date_end=${encodeURIComponent(dateEnd)}`;
            }

            fetch(url).then(response => response.json()).then(data => {
                if (type === 'study' || type === 'exam') {
                    const subjectSelect = document.getElementById(type === 'study' ? 'learning_id' : 'exam_id');
                    subjectSelect.innerHTML = (type === 'study')
                        ? '<option value="">Chọn môn học</option>'
                        : '<option value="">Chọn môn thi</option>';

                    const subjects = type === 'study' ? data.course.learning_fields : data.course.exam_fields;
                    if (type === 'exam') {
                        subjectSelect.multiple = true;
                        if ($.fn.select2 && $('#exam_id').hasClass('select2-hidden-accessible')) {
                            $('#exam_id').select2('destroy');
                        }
                        $('#exam_id').select2({
                            dropdownParent: $('#addActivitieModal'),
                            placeholder: "Chọn môn thi",
                            allowClear: true
                        });
                    } else {
                        subjectSelect.multiple = false;
                    }
                    subjects.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name;
                        subjectSelect.appendChild(option);
                    });

                    // Xử lý danh sách học viên
                    const studentSelect = document.getElementById(type === 'study' ? 'learn_student_id_select' : 'exam_student_id_select');
                    studentSelect.innerHTML = '';

                    if (data.option_message) {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = data.option_message;
                        option.disabled = true;
                        option.selected = true;
                        studentSelect.appendChild(option);
                    } else {
                        // Thêm tất cả học viên từ API (không có lịch trùng)
                        const availableStudents = data.available_students || [];
                        availableStudents.forEach(student => {
                            const option = document.createElement('option');
                            option.value = student.id;
                            option.textContent = `${student.name} - ${student.student_code}`;
                            studentSelect.appendChild(option);
                        });

                        // Kiểm tra xem học viên mặc định có trong danh sách khả dụng không
                        let defaultStudents = [];
                        if (defaultStudentId) {
                            const isDefaultStudentAvailable = availableStudents.some(student => student.id == defaultStudentId);
                            if (!isDefaultStudentAvailable) {
                                showAlert(`Học viên "${defaultStudentName}" đã có lịch trùng vào thời gian này. Vui lòng chọn thời gian khác.`);
                            } else {
                                defaultStudents = [defaultStudentId];
                                // Thêm học viên mặc định vào container (nếu type là exam)
                                if (type === 'exam') {
                                    const $container = $('#student-inputs');
                                    if ($container.find(`.student-input-group[data-student-id="${defaultStudentId}"]`).length === 0) {
                                        const groupHtml = `
                                            <div class="student-input-group border p-4 rounded bg-gray-50" data-student-id="${defaultStudentId}">
                                                <div class="mb-2 font-semibold">${defaultStudentName} (Mặc định)</div>
                                                <div class="mb-2">
                                                    <label class="block text-sm mb-1">Số báo danh:</label>
                                                    <input type="text" name="students[${defaultStudentId}][exam_number]" class="w-full rounded border-gray-300">
                                                </div>
                                                <div>
                                                    <label class="block text-sm mb-1">
                                                        <input type="checkbox" name="students[${defaultStudentId}][pickup]" value="1" class="mr-1">
                                                        Đăng ký đưa đón
                                                    </label>
                                                </div>
                                            </div>
                                        `;
                                        $container.append(groupHtml);
                                    }
                                }
                            }
                        }

                        // Khởi tạo Select2 với học viên mặc định
                        if ($.fn.select2 && $(studentSelect).hasClass('select2-hidden-accessible')) {
                            $(studentSelect).select2('destroy');
                        }
                        $(studentSelect).select2({
                            dropdownParent: $('#addActivitieModal'),
                            placeholder: "Chọn học viên",
                            allowClear: true,
                            multiple: true
                        }).val(defaultStudents).trigger('change');
                    }
                }
            }).catch(error => console.error('Error:', error));
        }

        function fetchAndUpdateVehicles(startTime, endTime) {
            if (!startTime || !endTime) return;
            const params = new URLSearchParams({ start_time: startTime, end_time: endTime });
            fetch(`/vehicles-available?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    const vehicleSelect = document.getElementById('vehicle_select');
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
                    if ($.fn.select2 && $('#vehicle_select').hasClass('select2-hidden-accessible')) {
                        $('#vehicle_select').select2('destroy');
                    }
                    $('#vehicle_select').select2({
                        dropdownParent: $('#addActivitieModal')
                    });
                })
                .catch(error => console.error('Error fetching vehicles:', error));
        }

        function fetchAndUpdateUser(dateOrStartTime, timeOrEndTime, type) {
            if (!dateOrStartTime || !timeOrEndTime) return;
            const params = new URLSearchParams({
                type: type
            });
            if (type === 'exam') {
                params.append('date', dateOrStartTime);
                params.append('time', timeOrEndTime);
            } else {
                params.append('start_time', dateOrStartTime);
                params.append('end_time', timeOrEndTime);
            }

            fetch(`/users-available?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    const teacherSelect = document.getElementById(type === 'study' ? 'learn_teacher_id' : 'exam_teacher_id');
                    if (teacherSelect) {
                        teacherSelect.innerHTML = '<option value="">Chọn giáo viên</option>';
                        (data.teachers || []).forEach(user => {
                            const option = document.createElement('option');
                            option.value = user.id;
                            option.textContent = user.name;
                            teacherSelect.appendChild(option);
                        });
                        $(`#${type === 'study' ? 'learn_teacher_id' : 'exam_teacher_id'}`).select2({
                            dropdownParent: $('#addActivitieModal'),
                            placeholder: "Chọn giáo viên",
                            allowClear: true,
                            width: '100%'
                        });
                    }
                })
                .catch(error => console.error('Error fetching users:', error));
        }

        function fetchExamSchedules(courseId, examFieldIds, dateOrStartTime, timeOrEndTime) {
            const params = new URLSearchParams({
                course_id: courseId,
                date: dateOrStartTime,
                time: timeOrEndTime
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
                        option.value = schedule.id;
                        option.textContent = `${location} (${schedule.start_time} → ${schedule.end_time})`;
                        examScheduleSelect.appendChild(option);
                    });
                    $('#exam_schedule_id').select2({
                        dropdownParent: $('#addActivitieModal')
                    });
                })
                .catch(error => console.error('Lỗi khi lấy kế hoạch sân thi:', error));
        }

        function handleTimeChange() {
            const selectedType = document.querySelector('input[name="type"]:checked')?.value;
            if (selectedType === 'exam') {
                const date = dateInput.value;
                const time = timeInput.value;
                if (!date || !time) return;
                const courseId = $('#exam_course_id').val();
                fetchAndUpdate(courseId, 'exam', date, time);
                fetchAndUpdateUser(date, time, 'exam');
            } else {
                const startTime = startTimeInput.value;
                const endTime = endTimeInput.value;
                if (!startTime || !endTime) return;

                if (selectedType === 'study') {
                    const courseId = $('#learn_course_id').val();
                    fetchAndUpdateVehicles(startTime, endTime);
                    fetchAndUpdateUser(startTime, endTime, 'study');
                    fetchAndUpdate(courseId, 'study', startTime, endTime);
                }
            }
        }

        function handleTimeChangeSchedules() {
            const selectedType = document.querySelector('input[name="type"]:checked')?.value;
            if (selectedType === 'exam') {
                const date = dateInput.value;
                const time = timeInput.value;
                if (!date || !time) return;
                const courseId = $('#exam_course_id').val();
                const examFieldIds = $('#exam_id').val();
                if (examFieldIds && courseId) {
                    fetchExamSchedules(courseId, examFieldIds, date, time);
                }
            }
        }

        if (dateInput) dateInput.addEventListener('change', handleTimeChange);
        if (timeInput) timeInput.addEventListener('change', handleTimeChange);
        startTimeInput.addEventListener('change', handleTimeChange);
        endTimeInput.addEventListener('change', handleTimeChange);
        examCourseSelect.addEventListener('change', handleTimeChange);
        $('#exam_id').on('select2:select select2:unselect', handleTimeChangeSchedules);
    });
    </script>
@endsection
