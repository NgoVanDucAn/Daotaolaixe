@extends('layouts.admin')

@section('content')
    <h2>Danh sách giáo trình</h2>
    <a href="{{ route('curriculums.create') }}" class="btn btn-primary mb-3">Thêm giáo trình</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>STT</th>
                <th>Tên giáo trình</th>
                <th>Loại bằng</th>
                <th>Loại giáo trình</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @php
                $key = ($curriculums->currentPage() - 1) * $curriculums->perPage();
            @endphp
            @foreach ($curriculums as $curriculum)
                <tr>
                    <td>
                        <button class="btn btn-info toggle-details" data-curriculum-id="{{ $curriculum->id }}">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </td>
                    <td>{{ ++$key }}</td>
                    <td>{{ $curriculum->name }}</td>
                    <td>{{ $curriculum->rank_name }}</td>
                    <td>{{ $curriculum->title }}</td>
                    <td>{{ $curriculum->description }}</td>
                    <td>
                        <a href="{{ route('curriculums.edit', $curriculum) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="{{ route('curriculums.show', $curriculum) }}" class="btn btn-info btn-sm">Xem</a>
                        <form action="{{ route('curriculums.destroy', $curriculum) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                <tr class="details-row" id="details-{{ $curriculum->id }}" style="display:none;">
                    <td colspan="7">
                        <div>
                            <h5>Bài học (Lessons)</h5>
                            @if($curriculum->lessons->isNotEmpty())
                                <table class="table mt-3 table-dark table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tên bài học</th>
                                            <th>Trình tự bài học</th>
                                            <th>Mô tả</th>
                                            <th>Trạng thái hiển thị</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($curriculum->lessons as $lesson)
                                            <tr>
                                                <td>{{ $lesson->title }}</td>
                                                <td>{{ $lesson->sequence }}</td>
                                                <td>{{ $lesson->description }}</td>
                                                <td>{{ $lesson->visibility }}</td>
                                                <td>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">Không có bài học nào.</p>
                            @endif
                        </div>

                        <div>
                            <h5>Quiz Sets</h5>
                            @php
                                $quizSets = $curriculum->lessons->pluck('quizSets')->flatten();
                            @endphp
                            @if($quizSets->isNotEmpty())
                                <table class="table mt-3 table-dark table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tên bộ câu hỏi</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($quizSets as $quizSet)
                                            <tr>
                                                <td>{{ $quizSet->name }}</td>
                                                <td>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">Không có bộ câu hỏi nào.</p>
                            @endif
                        </div>

                        <div>
                            <h5>Assignments</h5>
                            @php
                                $assignments = $quizSets->pluck('assignments')->flatten();
                            @endphp
                            @if($assignments->isNotEmpty())
                                <table class="table mt-3 table-dark table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tên bộ câu hỏi</th>
                                            <th>Mô tả</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($assignments as $assignment)
                                            <tr>
                                                <td>{{ $assignment->title }}</td>
                                                <td>{{ $assignment->description }}</td>
                                                <td>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">Không có bài tập nào.</p>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $curriculums->links('pagination::bootstrap-5') }}

    <script>
        document.querySelectorAll('.toggle-details').forEach(button => {
            button.addEventListener('click', function() {
                const curriculumId = this.getAttribute('data-curriculum-id');
                const detailsRow = document.getElementById(`details-${curriculumId}`);
                
                if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                    detailsRow.style.display = 'table-row';
                } else {
                    detailsRow.style.display = 'none';
                }

                const icon = this.querySelector('i');
                icon.classList.toggle('fa-chevron-down');
                icon.classList.toggle('fa-chevron-up');
            });
        });
    </script>
@endsection
@extends('layouts.admin')

@section('title', 'Danh sách khóa học')
@section('css')
    <link href="{{ asset('assets/css/nouislider.min.css') }}" rel="stylesheet">
    <style>
        .table-container {
            max-width: 100%;
            overflow-x: auto;
        }

        .table-container {
            overflow-x: auto;
            width: 100%;
            max-height: 70vh;
        }

        .scroll-container::-webkit-scrollbar, 
        .table-container::-webkit-scrollbar {
            height: 8px;
        }

        .scroll-container::-webkit-scrollbar-track, 
        .table-container::-webkit-scrollbar-track {
            background: #e9ecef;
            border-radius: 4px;
        }

        .scroll-container::-webkit-scrollbar-thumb, 
        .table-container::-webkit-scrollbar-thumb {
            background: #6c757d;
            border-radius: 4px;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover, 
        .table-container::-webkit-scrollbar-thumb:hover {
            background: #495057;
        }

        .noUi-horizontal {
            height: 8px;
        }

        .noUi-connect {
            background: #3b82f6;
            height: 100%;
        }

        .noUi-horizontal .noUi-handle {
            width: 14px;
            height: 14px;
            top: -4px;
            transform: translateX(-70%);
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #3b82f6;
            box-shadow: 0 0 2px rgba(0, 0, 0, 0.3);
            cursor: pointer;
        }

        .noUi-handle::before,
        .noUi-handle::after {
            display: none;
        }

        .noUi-handle:hover,
        .noUi-handle:focus {
            transform: translateX(-70%) scale(1.1);
        }
    </style>
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('courses.index') }}">
            <div class="row g-3">
                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mã khóa học</label>
                        <input type="text" class="form-control" name="code" value="{{ request('code') }}">
                    </div>
                    {{-- <div class="">
                        <label class="form-label fw-bold">Hạng bằng</label>
                        <select name="ranking_id" class="form-select">
                            <option value="">-- Chọn giáo trình --</option>
                            @foreach ($rankings as $ranking)
                                <option value="{{ $ranking->id }}" {{ request('ranking_id') == $ranking->id ? 'selected' : '' }}>
                                    {{ $ranking->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}
                    {{-- <div class="col-6 col-md-4 mb-3">
                        <label for="teacher_id" class="form-label">Chọn giáo viên</label>
                        <select name="teacher_id[]" class="form-control">
                            <option value="">Chọn giáo viên</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-4 mb-3">
                        <label for="stadium_id" class="form-label">Chọn sân tập</label>
                        <select name="stadium_id[]" class="form-control">
                            <option value="">Chọn sân tập</option>
                            @foreach($stadiums as $stadium)
                                <option value="{{ $stadium->id }}">{{ $stadium->location }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                </div>

                {{-- <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tháng/Năm diễn ra khóa học</label>
                        <input type="month" name="month_year" id="month_year" class="form-control" value="{{ request('month_year') }}">
                    </div>
                    <div class="">
                        <label class="form-label fw-bold">Trạng thái khóa học</label>
                        <select name="status" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Ngừng hoạt động</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3 col-xl-2 pe-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Học phí</label>
                        <div id="tuition-slider"></div>
                        <input type="hidden" name="tuition_fee_min" id="tuition_fee_min" value="{{ request('tuition_fee_min') }}">
                        <input type="hidden" name="tuition_fee_max" id="tuition_fee_max" value="{{ request('tuition_fee_max') }}">
                        <div class="mt-2">
                            <span id="tuition-slider-value"></span>
                        </div>
                    </div>
                    <div class="">
                        <label class="form-label fw-bold">Số lượng học viên</label>
                        <div id="student-slider"></div>
                        <input type="hidden" name="student_count_min" id="student_count_min" value="{{ request('student_count_min') }}">
                        <input type="hidden" name="student_count_max" id="student_count_max" value="{{ request('student_count_max') }}">
                        <div class="mt-2">
                            <span id="student-slider-value"></span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mã học viên</label>
                        <input type="text" name="student_code" class="form-control" value="{{ request('student_code') }}">
                    </div>
                    <div class="">
                        <label class="form-label fw-bold">Tên học viên</label>
                        <input type="text" name="student_name" class="form-control" value="{{ request('student_name') }}">
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Trạng thái</label>
                        <select name="student_status" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="active" {{ request('student_status') == 'active' ? 'selected' : '' }}>Đang học</option>
                            <option value="inactive" {{ request('student_status') == 'inactive' ? 'selected' : '' }}>Nghỉ</option>
                        </select>
                    </div>
                    <div class="">
                        <label class="form-label fw-bold">Tình trạng học</label>
                        <select name="learning_status" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="in_progress" {{ request('learning_status') == 'in_progress' ? 'selected' : '' }}>Đang học</option>
                            <option value="completed" {{ request('learning_status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3 col-xl-2 mt-4">
                    <div class="w-100 d-flex flex-column">
                        <div class="form-check mt-4 mb-1">
                            <input class="form-check-input" type="checkbox" name="only_debt" value="1"
                                {{ request('only_debt') ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold">Chỉ còn nợ học phí</label>
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel-fill"></i> Lọc
                            </button>
                            <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                                Đặt lại
                            </a>
                        </div>
                    </div>
                </div> --}}
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 ">
                    <label for="">
                        <b>&nbsp</b>
                    </label>
                    <div class="d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mb-1">
                            <b>Tìm Kiếm</b>
                        </button>
                        <div class="ms-2">
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-danger mb-1" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a
            {{-- href="{{ route('courses.create') }}" --}}
            type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
            data-bs-toggle="modal"
            data-bs-target="#courseCreateModal"
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Tạo khóa học</span>
        </a>
    </div>
@endsection

<a 
    {{-- href="{{ route('courses.create') }}"  --}}
    class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
    style="width: 40px; height: 40px; font-size: 30px; bottom: 3%; right: 60px; z-index: 99;"
    data-bs-toggle="modal"
    data-bs-target="#courseCreateModal"
>
    +
</a>
<div class="card">
    <div class="card-body">
        {{-- <div class="alert alert-primary d-flex align-items-center" role="alert">
            <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-20 me-1"></iconify-icon>
            <div class="lh-1"><strong>Tổng số lượng học viên: </strong><strong>{{ '0 new' }}</strong></div>
        </div> --}}
        @if ($errors->has('curriculum_id'))
            <div class="alert alert-danger">
                {{ $errors->first('curriculum_id') }}
            </div>
        @endif
        <div class="table-container" id="bottom-scroll top-scroll">
            <table class="table mt-3 table-bordered">
                <thead>
                    <tr>
                        {{-- <th></th> --}}
                        <th>STT</th>
                        <th>Mã</th>
                        <th>Hạng</th>
                        <th>Ngày học Cabin</th>
                        <th>Ngày học DAT</th>
                        <th>Khai giảng</th>
                        <th>Bế giảng</th>
                        {{-- <th>Học phí</th> --}}
                        <th>Số HS</th>
                        <th>Thời gian</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $key = ($courses->currentPage() - 1) * $courses->perPage();
                    @endphp
                    @foreach ($courses as $course)
                        <tr class="course-main-row" data-course-id="{{ $course->id }}">
                            {{-- <td>
                                <button class="btn btn-sm btn-info toggle-detail" data-bs-toggle="collapse" data-bs-target="#details-{{ $course->id }}" data-course-id="{{ $course->id }}">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </td> --}}
                            <td>{{ ++$key }}</td>
                            <td><a style="font-weight: 600; color: #4C9AFF" href="{{ $course->ranking->vehicle_type == 1 ? route('students.index-car', ['course_id' => $course->id]) : route('students.index-moto', ['course_id' => $course->id]) }}">{{ $course->code }}</a></td>
                            <td>
                                <span class="badge bg-secondary">{{ $course->ranking->name }}</span>
                            </td>
                            <td>{{ $course->cabin_date ? $course->cabin_date->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ $course->dat_date ? $course->dat_date->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ $course->start_date ? $course->start_date->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ $course->end_date ? $course->end_date->format('d/m/Y') : 'N/A' }}</td>
                            
                            {{-- <td>{{ number_format($course->tuition_fee) }} VND</td> --}}
                            <td>{{ number_format($course->number_students) ?? 0 }}</td>
                            {{-- <td>{{ $course->status == 1 ? 'Hoạt động' : 'Ngừng hoạt động' }}</td> --}}
                            <td>{{ number_format($course->duration_days) ?? 0 }}</td>
                            <td>
                                <a 
                                    {{-- href="{{ route('courses.edit', $course->id) }}"  --}}
                                    href="javascript:void(0)"
                                    class="btn btn-sm btn-warning btn-edit-course"
                                    data-bs-toggle="modal"
                                    data-bs-target="#courseEditModal"
                                    data-id="{{ $course->id }}"
                                    data-code="{{ $course->code }}"
                                    data-ranking_id="{{ $course->ranking_id }}"
                                    data-cabin_date="{{ \Carbon\Carbon::parse($course->cabin_date)->format('d/m/Y') }}"
                                    data-dat_date="{{ \Carbon\Carbon::parse($course->dat_date)->format('d/m/Y') }}"
                                    data-date_bci="{{ \Carbon\Carbon::parse($course->date_bci)->format('d/m/Y') }}"
                                    data-start_date="{{ \Carbon\Carbon::parse($course->start_date)->format('d/m/Y') }}"
                                    data-end_date="{{ \Carbon\Carbon::parse($course->end_date)->format('d/m/Y') }}"
                                    data-tuition_fee="{{ $course->tuition_fee }}"
                                    data-decision_kg="{{ $course->decision_kg }}"
                                    data-duration="{{ $course->duration }}"
                                    data-learning_fields='@json($course->learningFields)'
                                    data-exam_fields='@json($course->examFields)'
                                >
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                                <form action="{{ route('courses.destroy', $course->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa không? Hãy cân nhắc vì việc xóa sẽ ảnh hưởng đến tất cả những thứ liên quan đến khóa học đó!')"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                        <tr class="collapse course-detail-row" id="details-row-{{ $course->id }}">
                            <td colspan="14">
                                <div>
                                    <div class="d-flex justify-content-between">
                                        <h5>Danh sách sinh viên của khóa học</h5>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal" data-course-id="{{ $course->id }}">
                                            Thêm Học Viên
                                        </button>
                                    </div>
                                    @if($course->students->isNotEmpty())
                                        <table class="table table-bordered mt-1">
                                            <thead>
                                                <tr class="text-center">
                                                    <th rowspan="2"></th>
                                                    <th rowspan="2" class="align-middle">Mã học viên</th>
                                                    <th rowspan="2" class="align-middle">Họ và Tên</th>
                                                    <th rowspan="2" class="align-middle">Trạng thái HĐ</th>
                                                    <th rowspan="2" class="align-middle">Ảnh hợp đồng</th>
                                                    <th rowspan="2" class="align-middle">Người xác nhận</th>
                                                    <th rowspan="2" class="align-middle">Người hỗ trợ</th>
                                                    <th rowspan="2" class="align-middle">Mô tả</th>
                                                    @if ($course->examFields->isNotEmpty())
                                                        <th colspan="{{ count($course->examFields) }}" class="align-middle">Tình trạng học</th>
                                                    @endif
                                                    <th colspan="2" class="align-middle">Phiên học</th>
                                                    <th colspan="3" class="align-middle">Học phí</th>
                                                    <th rowspan="2" class="align-middle">Trạng thái học</th>
                                                    <th rowspan="2" class="align-middle">Hành động</th>
                                                </tr>
                                                <tr>
                                                @foreach($course->examFields as $exam)
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
                                                @foreach($course->students as $student)
                                                    <tr>
                                                        <td>
                                                            <button class="btn btn-sm btn-info" data-bs-toggle="collapse" data-bs-target="#details-student-{{ $student->id }}">
                                                                <i class="fas fa-chevron-down"></i>
                                                            </button>
                                                        </td>
                                                        <td><a href="{{ route('students.index', ['q' => $student->student_code]) }}">{{ $student->student_code }}</a></td>
                                                        <td><a href="{{ route('students.index', ['q' => $student->name]) }}">{{ $student->name }}</a></td>
                                                        <td>
                                                            <span class="badge {{ $student->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                                {{ ucfirst($student->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if ($student->pivot->contract_image)
                                                                @php
                                                                $filePath = 'storage/' . $student->pivot->contract_image;
                                                                $extension = strtolower(pathinfo($student->pivot->contract_image, PATHINFO_EXTENSION));
                                                                @endphp
                                                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                                    <a href="{{ asset($filePath) }}" target="_blank">
                                                                        <img src="{{ asset($filePath) }}" alt="Contract Image" width="200" class="img-thumbnail">
                                                                    </a>
                                                                @elseif($extension === 'pdf')
                                                                    <a href="{{ asset($filePath) }}" target="_blank" class="btn btn-outline-primary">
                                                                        📄 Xem file PDF
                                                                    </a>
                                                                @else
                                                                    <p>Không hỗ trợ định dạng: {{ $extension }}</p>
                                                                @endif
                                                            @else
                                                                <p>Chưa có hình ảnh hợp đồng</p>
                                                            @endif
                                                        </td>
                                                        <td>{{ $student->convertedBy->name ?? '-' }}</td>
                                                        <td>{{ $student->saleSupport->name ?? '-' }}</td>
                                                        <td>
                                                            <textarea value="{{ ucfirst($student->description) }}"></textarea>
                                                        </td>
                                                        @foreach($course->examFields as $exam)
                                                            @php
                                                                $result = $student->studentExamFields->where('exam_field_id', $exam->id)->where('course_id', $course->id)->first();
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
                                                        @endforeach
                                                        <td>
                                                            <button class="btn btn-sm btn-info"
                                                                data-student-id="{{ $student->id }}"
                                                                data-course-id="{{ $course->id }}"
                                                                onclick="showStudyDetails(this)">
                                                                {{ $student->total_hours ?? 0}}/{{ $course->duration ?? '-' }}
                                                            </button>
                                                        </td>
                                                        <td>{{ $student->total_km ?? 0}}/{{ $course->km ?? '-' }}</td>
                                                        <td>
                                                            @if(isset($student->pivot->tuition_fee))
                                                                {{ number_format($student->pivot->tuition_fee, 0, ',', '.') }} VND</td>
                                                            @else
                                                                0 VND
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($student->total_paid))
                                                                {{ number_format($student->total_paid, 0, ',', '.') }} VND</td>
                                                            @else
                                                                0 VND
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($student->remaining_fee))
                                                                {{ number_format($student->remaining_fee, 0, ',', '.') }} VND</td>
                                                            @else
                                                                0 VND
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge {{ $student->pivot->status == 1 ? 'bg-success' : ($student->pivot->status == 2 ? 'bg-warning' : ($student->pivot->status == 3 ? 'bg-primary' : 'bg-secondary')) }}">
                                                            {{ $student->pivot->status == 0 ? 'Chưa học' : ($student->pivot->status == 1 ? 'Đang học' : ($student->pivot->status == 2 ? 'Bỏ học' : 'Đã tốt nghiệp')) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <form action="{{ route('courses.removeStudent', ['course' => $course->id, 'student' => $student->id]) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')"><i class="fa-solid fa-trash-can"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <tr id="details-student-{{ $student->id }}" class="collapse">
                                                        <td id="dynamic-colspan">
                                                            <div class="card p-6 rounded-2xl shadow-lg">
                                                                <div class="d-flex justify-content-between gap-4 p-4 bg-gray-100 rounded-2xl shadow-lg">
                                                                    <div class="bg-white p-4 rounded-lg shadow">
                                                                        <p class=""><strong>Email:</strong> {{ $student->email }}</p>
                                                                        <p class=""><strong>Điện thoại:</strong> {{ $student->phone }}</p>
                                                                    </div>
                                                                    <div class="bg-white p-4 rounded-lg shadow">
                                                                        <p class=""><strong>Ngày xác nhận:</strong> {{ $student->became_student_at ?? '-' }}</p>
                                                                        <p class=""><strong>Địa chỉ:</strong> {{ $student->address ?? '-' }}</p>
                                                                    </div>
                                                                    <div class="bg-white p-4 rounded-lg shadow">
                                                                        <p class=""><strong>Giới tính:</strong> {{ ucfirst($student->gender) }}</p>
                                                                        <p class=""><strong>Nguồn:</strong> {{ $student->leadSource->name ?? '-' }}</p>
                                                                    </div>
                                                                    <div class="bg-white p-4 rounded-lg shadow">
                                                                        <p class=""><strong>Ngày sinh:</strong> {{ \Carbon\Carbon::parse($student->dob)->format('d/m/Y') }}</p>
                                                                        <p class=""><strong>Số CMND/CCCD:</strong> {{ $student->identity_card ?? '-' }}</p>
                                                                    </div>
                                                                    <div class="bg-white p-4 rounded-lg shadow">
                                                                        <p class=""><strong>Email:</strong> {{ $student->email }}</p>
                                                                        <p class=""><strong>Điện thoại:</strong> {{ $student->phone }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-muted">Không có bài học nào.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- modal add khóa học --}}
<div class="modal fade" id="courseCreateModal" tabindex="-1" aria-labelledby="courseCreateModalLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="courseCreateModalLabel">Thêm khóa học mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <form id="formCourseCreateModal" action="{{ route('courses.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="modal_name" value="courseCreateModal">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="code" class="form-label">Mã khóa học</label>
                                <input type="text" name="code" class="form-control" value="{{ old('code', $course->code) }}" required>
                                @error('code') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="ranking_id" class="form-label">Hạng</label>
                                <select name="ranking_id" class="form-control" required>
                                    <option value="">-- Chọn hạng GPLX --</option>
                                    @foreach($rankings as $ranking)
                                        <option value="{{ $ranking->id }}" {{ $course->ranking_id == $ranking->id ? 'selected' : '' }}>
                                            {{ $ranking->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ranking_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative">
                                <label for="cabin_date" class="form-label">Ngày học Cabin</label>
                                <input type="text" placeholder="dd/mm/yyyy"name="cabin_date" class="form-control real-date" autocomplete="off" value="{{ old('cabin_date', \Carbon\Carbon::parse($course->cabin_date)->format('Y-m-d')) }}" required>
                                @error('cabin_date') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative">
                                <label for="dat_date" class="form-label">Ngày học DAT</label>
                                <input type="text" placeholder="dd/mm/yyyy"name="dat_date" class="form-control real-date" autocomplete="off" value="{{ old('dat_date', \Carbon\Carbon::parse($course->dat_date)->format('Y-m-d')) }}" required>
                                @error('dat_date') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative">
                                <label for="date_bci" class="form-label">Ngày BCI</label>
                                <input type="text" placeholder="dd/mm/yyyy" name="date_bci" class="form-control real-date" autocomplete="off" value="{{ old('date_bci', \Carbon\Carbon::parse($course->date_bci)->format('d/m/Y')) }}" required>
                                @error('date_bci') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative">
                                <label for="start_date" class="form-label">Ngày bắt đầu</label>
                                <input type="text" placeholder="dd/mm/yyyy" name="start_date" class="form-control real-date" autocomplete="off" value="{{ old('start_date', \Carbon\Carbon::parse($course->start_date)->format('d/m/Y')) }}" required>
                                @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative">
                                <label for="end_date" class="form-label">Ngày kết thúc</label>
                                <input type="text" placeholder="dd/mm/yyyy"name="end_date" class="form-control real-date" autocomplete="off" value="{{ old('end_date', \Carbon\Carbon::parse($course->end_date)->format('d/m/Y')) }}" required>
                                @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="tuition_fee" class="form-label">Học phí</label>
                                <input type="texy" name="tuition_fee" class="form-control currency-input" value="{{ old('tuition_fee', $course->tuition_fee) }}" required>
                                @error('tuition_fee') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="decision_kg" class="form-label">Quyết định KG</label>
                                <input type="text" name="decision_kg" class="form-control" value="{{ old('decision_kg', $course->decision_kg) }}" required>
                                @error('decision_kg') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                            {{-- <div>
                                <label class="form-label">Lĩnh vực thi</label>
                                <div>
                                    <input type="checkbox" id="edit_selectAllExamFields" onclick="toggleCheckboxes(this, 'exam_fields')" />
                                    Chọn tất cả
                                </div>
                                <div class="row">
                                    @foreach($examFields as $field)
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input 
                                                    type="checkbox" 
                                                    class="form-check-input" 
                                                    name="exam_fields[]" 
                                                    value="{{ $field->id }}" 
                                                    id="exam_field_{{ $field->id }}"
                                                    {{ 
                                                        (is_array(old('exam_fields')) 
                                                            ? in_array($field->id, old('exam_fields')) 
                                                            : ($course->learning_fields && $course->learning_fields->contains($field->id))) 
                                                        ? 'checked' : '' 
                                                    }}
                                                >
                                                <label class="form-check-label" for="exam_field_{{ $field->id }}">{{ $field->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                                @error('exam_fields') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label">Môn học</label>
                                <div>
                                    <input type="checkbox" id="edit_selectAllLearningFields" onclick="toggleCheckboxes(this, 'learning_fields')" />
                                    Chọn tất cả
                                </div>
                                <div class="row">
                                    @foreach($learningFields as $field)
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="learning_fields[]" value="{{ $field->id }}" id="learning_field_{{ $field->id }}"
                                                    {{ (is_array(old('learning_fields')) && in_array($field->id, old('learning_fields'))) ? 'checked' : '' }}
                                                    onclick="toggleHourKmFields(this, 'edit')">
                                                <label class="form-check-label" for="learning_field_{{ $field->id }}">{{ $field->name }}</label>
                                                <div id="edit_learning_field_{{ $field->id }}_details" class="extra-fields" style="display:none;">
                                                    <label for="hours_{{ $field->id }}">Số giờ:</label>
                                                    <input type="text" name="hours[{{ $field->id }}]" id="hours_{{ $field->id }}" class="form-control currency-input" placeholder="Số giờ" value="{{ old('hours.' . $field->id) }}">
                                                    @error('hours.' . $field->id)
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                    <label for="km_{{ $field->id }}">Số km:</label>
                                                    <input type="text" name="km[{{ $field->id }}]" id="km_{{ $field->id }}" class="form-control currency-input" placeholder="Số km" value="{{ old('km.' . $field->id) }}">
                                                    @error('km.' . $field->id)
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('learning_fields') <div class="text-danger">{{ $message }}</div> @enderror
                            </div> --}}
                        </div>
                        {{-- @dd($course) --}}
                        {{-- <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="ranking_id" class="form-label">Hạng GP</label>
                                <select name="ranking_id" class="form-control" required>
                                    <option value="">-- Chọn hạng GPLX --</option>
                                    @foreach($rankings as $ranking)
                                        <option value="{{ $ranking->id }}" {{ old('ranking_id') == $ranking->id ? 'selected' : '' }}>
                                            {{ $ranking->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ranking_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="duration" class="form-label">Thời gian</label>
                                <input type="number" name="duration" class="form-control" value="{{ old('duration', $course->duration) }}" required>
                                @error('duration') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div> --}}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-2"></i> <span>Hủy</span>
                </button>
                <button type="submit" class="btn btn-primary" form="formCourseCreateModal">
                    <i class="mdi mdi-content-save-outline me-2"></i> <span>Lưu</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- modal edit khóa học --}}
@if(isset($course))
<div class="modal fade" id="courseEditModal" tabindex="-1" aria-labelledby="courseEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="courseEditModalLabel">Sửa khóa học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
            <form id="formCourseEditModal" action="{{ route('courses.update', $course->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" value="courseEditModal">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="code" class="form-label">Mã khóa học</label>
                            <input type="text" name="code" class="form-control" value="{{ old('code', $course->code) }}" required>
                            @error('code') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="ranking_id" class="form-label">Hạng</label>
                            <select name="ranking_id" class="form-control" required>
                                <option value="">-- Chọn hạng GPLX --</option>
                                @foreach($rankings as $ranking)
                                    <option value="{{ $ranking->id }}" {{ $course->ranking_id == $ranking->id ? 'selected' : '' }}>
                                        {{ $ranking->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ranking_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="cabin_date" class="form-label">Ngày học Cabin</label>
                            <input type="text" placeholder="dd/mm/yyyy"name="cabin_date" class="form-control real-date" autocomplete="off" value="{{ old('cabin_date', \Carbon\Carbon::parse($course->cabin_date)->format('Y-m-d')) }}" required>
                            @error('cabin_date') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="dat_date" class="form-label">Ngày học DAT</label>
                            <input type="text" placeholder="dd/mm/yyyy"name="dat_date" class="form-control real-date" autocomplete="off" value="{{ old('dat_date', \Carbon\Carbon::parse($course->dat_date)->format('Y-m-d')) }}" required>
                            @error('dat_date') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="date_bci" class="form-label">Ngày BCI</label>
                            <input type="text" placeholder="dd/mm/yyyy" name="date_bci" class="form-control real-date" autocomplete="off" value="{{ old('date_bci', \Carbon\Carbon::parse($course->date_bci)->format('d/m/Y')) }}" required>
                            @error('date_bci') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="start_date" class="form-label">Ngày bắt đầu</label>
                            <input type="text" placeholder="dd/mm/yyyy" name="start_date" class="form-control real-date" autocomplete="off" value="{{ old('start_date', \Carbon\Carbon::parse($course->start_date)->format('d/m/Y')) }}" required>
                            @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="end_date" class="form-label">Ngày kết thúc</label>
                            <input type="text" placeholder="dd/mm/yyyy"name="end_date" class="form-control real-date" autocomplete="off" value="{{ old('end_date', \Carbon\Carbon::parse($course->end_date)->format('d/m/Y')) }}" required>
                            @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="tuition_fee" class="form-label">Học phí</label>
                            <input type="texy" name="tuition_fee" class="form-control currency-input" value="{{ old('tuition_fee', $course->tuition_fee) }}" required>
                            @error('tuition_fee') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="decision_kg" class="form-label">Quyết định KG</label>
                            <input type="text" name="decision_kg" class="form-control" value="{{ old('decision_kg', $course->decision_kg) }}" required>
                            @error('decision_kg') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label">Lĩnh vực thi</label>
                            <div>
                                <input type="checkbox" id="edit_selectAllExamFields" onclick="toggleCheckboxes(this, 'exam_fields')" />
                                Chọn tất cả
                            </div>
                            <div class="row">
                                @foreach($examFields as $field)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input 
                                                type="checkbox" 
                                                class="form-check-input" 
                                                name="exam_fields[]" 
                                                value="{{ $field->id }}" 
                                                id="exam_field_{{ $field->id }}"
                                                {{ 
                                                    (is_array(old('exam_fields')) 
                                                        ? in_array($field->id, old('exam_fields')) 
                                                        : ($course->learning_fields && $course->learning_fields->contains($field->id))) 
                                                    ? 'checked' : '' 
                                                }}
                                            >
                                            <label class="form-check-label" for="exam_field_{{ $field->id }}">{{ $field->name }}</label>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            @error('exam_fields') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label">Môn học</label>
                            <div>
                                <input type="checkbox" id="edit_selectAllLearningFields" onclick="toggleCheckboxes(this, 'learning_fields')" />
                                Chọn tất cả
                            </div>
                            <div class="row">
                                @foreach($learningFields as $field)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="learning_fields[]" value="{{ $field->id }}" id="learning_field_{{ $field->id }}"
                                                {{ (is_array(old('learning_fields')) && in_array($field->id, old('learning_fields'))) ? 'checked' : '' }}
                                                onclick="toggleHourKmFields(this, 'edit')">
                                            <label class="form-check-label" for="learning_field_{{ $field->id }}">{{ $field->name }}</label>
                                            <div id="edit_learning_field_{{ $field->id }}_details" class="extra-fields" style="display:none;">
                                                <label for="hours_{{ $field->id }}">Số giờ:</label>
                                                <input type="text" name="hours[{{ $field->id }}]" id="hours_{{ $field->id }}" class="form-control currency-input" placeholder="Số giờ" value="{{ old('hours.' . $field->id) }}">
                                                @error('hours.' . $field->id)
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <label for="km_{{ $field->id }}">Số km:</label>
                                                <input type="text" name="km[{{ $field->id }}]" id="km_{{ $field->id }}" class="form-control currency-input" placeholder="Số km" value="{{ old('km.' . $field->id) }}">
                                                @error('km.' . $field->id)
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('learning_fields') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                    </div>
                    {{-- @dd($course) --}}
                    {{-- <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="ranking_id" class="form-label">Hạng GP</label>
                            <select name="ranking_id" class="form-control" required>
                                <option value="">-- Chọn hạng GPLX --</option>
                                @foreach($rankings as $ranking)
                                    <option value="{{ $ranking->id }}" {{ old('ranking_id') == $ranking->id ? 'selected' : '' }}>
                                        {{ $ranking->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ranking_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div> --}}

                    {{-- <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="duration" class="form-label">Thời gian</label>
                            <input type="number" name="duration" class="form-control" value="{{ old('duration', $course->duration) }}" required>
                            @error('duration') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div> --}}
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                <i class="mdi mdi-close me-2"></i> <span>Hủy</span>
            </button>
            <button type="submit" class="btn btn-primary" form="formCourseEditModal">
                <i class="mdi mdi-content-save-outline me-2"></i> <span>Lưu</span>
            </button>
        </div>
        </div>
    </div>
</div>
@endif

    @if (old('modal_name') && $errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let modalName = @json(old('modal_name'));
            let myModal = new bootstrap.Modal(document.getElementById(modalName));
            myModal.show();
        });
    </script>
    @endif
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Thêm Học Viên Vào Khóa Học</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStudentForm" action="{{ route('courses.addStudent') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="modal_name" value="addStudentModal">
                        <input type="hidden" name="course_id" id="courseIdInput">
                            <div id="student-fields-container">
                                <div class="student-entry row">
                                    <div class="col-10 col-md-10 mb-3 row">
                                        <div class="col-6 col-md-4 mb-3">
                                            <label for="student_id" class="form-label">Chọn Học Viên</label>
                                            <select name="student_id[]" class="form-control student-select" id="studentSelect">
                                                <option value="">Chọn học viên</option>
                                            </select>
                                        </div>
                                        <div class="col-6 col-md-4 mb-3 position-relative">
                                            <label for="contract_date" class="form-label">Ngày ký hợp đồng</label>
                                            <input 
                                                type="text" 
                                                placeholder="dd/mm/yyyy"
                                                name="contract_date" 
                                                class="form-control real-date" autocomplete="off"
                                                value="{{ old('contract_date') ? \Carbon\Carbon::parse(old('contract_date'))->format('d/m/Y') : '' }}"
                                                required
                                            >
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <label for="contract_image" class="form-label">Ảnh hợp đồng</label>
                                            <input type="file" name="contract_image[]" class="form-control @error('contract_image') is-invalid @enderror">
                                            @error('contract_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-6 col-md-4 mb-3 position-relative">
                                            <label for="health_check_date" class="form-label">Ngày khám sức khỏe</label>
                                            <input 
                                                type="text" 
                                                placeholder="dd/mm/yyyy"
                                                name="health_check_date"
                                                class="form-control real-date" autocomplete="off"
                                                value="{{ old('health_check_date') ? \Carbon\Carbon::parse(old('health_check_date'))->format('d/m/Y') : '' }}"
                                                required
                                            >
                                        </div>
                                        <div class="col-6 col-md-4 mb-3">
                                            <label for="teacher_id" class="form-label">Chọn giáo viên</label>
                                            <select name="teacher_id[]" class="form-control">
                                                <option value="">Chọn giáo viên</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 col-md-4 mb-3">
                                            <label for="stadium_id" class="form-label">Chọn sân tập</label>
                                            <select name="stadium_id[]" class="form-control">
                                                <option value="">Chọn sân tập</option>
                                                @foreach($stadiums as $stadium)
                                                    <option value="{{ $stadium->id }}">{{ $stadium->location }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-2 col-md-2 mb-3 d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <button type="button" class="btn btn-danger remove-student" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')"><i class="fa-solid fa-trash-can"></i></button>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-primary" id="addStudentButton">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-3">Thêm</button>
                    </form>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#addStudentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var courseId = button.data('course-id');

            $(this).find('input[name="course_id"]').val(courseId);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const studentContainer = document.getElementById('student-fields-container');
            const addStudentButton = document.getElementById('addStudentButton');
            const courseIdInput = document.getElementById('courseIdInput'); // Lấy input ẩn course_id

            let courseId = null; // Biến lưu course_id hiện tại

            // Khi nhấn vào nút "Thêm Học Viên", cập nhật courseId
            document.querySelectorAll('[data-bs-target="#addStudentModal"]').forEach(button => {
                button.addEventListener('click', function () {
                    courseId = this.getAttribute('data-course-id');
                    courseIdInput.value = courseId;

                    fetchStudents(courseId); // Gọi API lấy danh sách học viên
                });
            });

            // Gọi API lấy danh sách học viên theo course_id
            function fetchStudents(courseId) {
                if (!courseId) return; // Nếu không có courseId, không gọi API

                fetch(`/get-available-students?course_id=${courseId}`)
                    .then(response => response.json())
                    .then(data => updateStudentSelectOptions(data))
                    .catch(error => console.error('Lỗi khi lấy danh sách học viên:', error));
            }

            // Cập nhật danh sách học viên vào select
            function updateStudentSelectOptions(students) {
                document.querySelectorAll('.student-select').forEach(select => {
                    select.innerHTML = '<option value="">Chọn học viên</option>'; // Xóa danh sách cũ

                    students.forEach(student => {
                        const option = document.createElement('option');
                        option.value = student.id;
                        option.textContent = `${student.name} - ${student.student_code}`;
                        select.appendChild(option);
                    });

                    updateSelectOptions(); // Cập nhật danh sách tránh trùng lặp
                });
            }

            // Xử lý thêm học viên mới vào form
            addStudentButton.addEventListener('click', function () {
                const studentEntry = document.querySelector('.student-entry');
                if (studentEntry) {
                    const newEntry = studentEntry.cloneNode(true);
                    newEntry.querySelectorAll('input, select').forEach(input => {
                        input.value = '';
                    });

                    newEntry.querySelector('.remove-student').addEventListener('click', function () {
                        newEntry.remove();
                        updateSelectOptions();
                    });

                    studentContainer.appendChild(newEntry);
                    updateSelectOptions();
                }
            });

            // Xử lý khi bấm nút xóa học viên
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-student')) {
                    e.target.closest('.student-entry').remove();
                    updateSelectOptions();
                }
            });

            // Cập nhật danh sách học viên để tránh chọn trùng
            function updateSelectOptions() {
                const selectedValues = Array.from(document.querySelectorAll('.student-select'))
                    .map(select => select.value)
                    .filter(value => value !== "");

                document.querySelectorAll('.student-select').forEach(select => {
                    Array.from(select.options).forEach(option => {
                        option.hidden = selectedValues.includes(option.value);
                    });
                });
            }

            updateSelectOptions();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Lấy tất cả các cột trong table
            const tableHeaders = document.querySelectorAll('table th');
            const tableColumnsCount = tableHeaders.length;

            // Cập nhật colspan của dòng chi tiết
            const detailRows = document.querySelectorAll('[id^="details-student-"], [id^="details-"]');
            
            detailRows.forEach(row => {
                const colspanElement = row.querySelector('[id="dynamic-colspan"]');
                if (colspanElement) {
                    colspanElement.setAttribute('colspan', tableColumnsCount);
                }
            });
        });
    </script>

    {{ $courses->links('pagination::bootstrap-5') }}
</div>
@endsection
@section('js')

{{-- Fill data to modal edit --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-edit-course').forEach(button => {
            button.addEventListener('click', function () {
                const modal = document.getElementById('courseEditModal');
    
                // Gán dữ liệu cho các input
                modal.querySelector('input[name="code"]').value = this.dataset.code;
                modal.querySelector('select[name="ranking_id"]').value = this.dataset.ranking_id;
                modal.querySelector('input[name="cabin_date"]').value = this.dataset.cabin_date;
                modal.querySelector('input[name="dat_date"]').value = this.dataset.dat_date;
                modal.querySelector('input[name="date_bci"]').value = this.dataset.date_bci;
                modal.querySelector('input[name="start_date"]').value = this.dataset.start_date;
                modal.querySelector('input[name="end_date"]').value = this.dataset.end_date;
                modal.querySelector('input[name="tuition_fee"]').value = this.dataset.tuition_fee;
                modal.querySelector('input[name="decision_kg"]').value = this.dataset.decision_kg;
                // modal.querySelector('input[name="duration"]').value = this.dataset.duration;
    
                // Đổi action form và thêm method PUT
                const form = modal.querySelector('form');
                form.action = `/courses/${this.dataset.id}`;
                if (!form.querySelector('input[name="_method"]')) {
                    form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="PUT">');
                }
    
                // Đổi tiêu đề modal
                modal.querySelector('.modal-title').textContent = 'Chỉnh sửa khóa học';
    
                // Parse learningFields và examFields
                const learningFields = JSON.parse(this.dataset.learning_fields);
                const examFields = JSON.parse(this.dataset.exam_fields);
    
                // Reset checkbox và extra input
                modal.querySelectorAll('input[type="checkbox"][name="exam_fields[]"]').forEach(cb => cb.checked = false);
                modal.querySelectorAll('input[type="checkbox"][name="learning_fields[]"]').forEach(cb => cb.checked = false);
                modal.querySelectorAll('.extra-fields').forEach(div => div.style.display = 'none');
    
                // Gán dữ liệu Exam Fields
                examFields.forEach(field => {
                    const cb = modal.querySelector(`#exam_field_${field.id}`);
                    if (cb) cb.checked = true;
                });
    
                // Gán dữ liệu Learning Fields (và giờ, km)
                learningFields.forEach(field => {
                    const cb = modal.querySelector(`#learning_field_${field.id}`);
                    if (cb) {
                        cb.checked = true;
    
                        const detailDiv = modal.querySelector(`#edit_learning_field_${field.id}_details`);
                        if (detailDiv) detailDiv.style.display = 'block';
    
                        const hourInput = modal.querySelector(`#hours_${field.id}`);
                        const kmInput = modal.querySelector(`#km_${field.id}`);
                        if (hourInput) hourInput.value = field.pivot?.hours || '';
                        if (kmInput) kmInput.value = field.pivot?.km || '';
                    }
                });
    
                // Kiểm tra tất cả đã được checked => check 'Chọn tất cả'
                const allExamCheckboxes = modal.querySelectorAll('input[name="exam_fields[]"]');
                const checkedExamCheckboxes = Array.from(allExamCheckboxes).filter(cb => cb.checked);
                modal.querySelector('#edit_selectAllExamFields').checked = checkedExamCheckboxes.length === allExamCheckboxes.length;
    
                const allLearningCheckboxes = modal.querySelectorAll('input[name="learning_fields[]"]');
                const checkedLearningCheckboxes = Array.from(allLearningCheckboxes).filter(cb => cb.checked);
                modal.querySelector('#edit_selectAllLearningFields').checked = checkedLearningCheckboxes.length === allLearningCheckboxes.length;
            });
        });
    });
    </script>
    
    
    

<script>
    // Toggle all checkboxes in a modal
    function toggleCheckboxes(selectAllCheckbox, fieldName) {
        // Lấy modal cha (đảm bảo đúng modal)
        const modal = selectAllCheckbox.closest('.modal');
        const checkboxes = modal.querySelectorAll(`input[name="${fieldName}[]"]`);
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
            if (fieldName === 'learning_fields') {
                const modalId = modal.id.toLowerCase();
                const modalPrefix = modalId.includes('edit') ? 'edit' : 'create';
                toggleHourKmFields(checkbox, modalPrefix);

            }
        });
    }

    // Toggle hiển thị ô nhập số giờ và km theo checkbox từng môn học
    function toggleHourKmFields(checkboxElement, modalPrefix) {
        
        
        const fieldId = checkboxElement.value;
        const details = document.getElementById(`${modalPrefix}_learning_field_${fieldId}_details`);
        if (details) {
            details.style.display = checkboxElement.checked ? 'block' : 'none';
        }
    }

    // Khi mở modal, nếu checkbox đã được checked sẵn (do old value), hiển thị lại fields
    function initLearningFieldsDisplay(modalPrefix) {
        const checkboxes = document.querySelectorAll(`input[id^="${modalPrefix}_learning_field_"]`);
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                toggleHourKmFields(checkbox, modalPrefix);
            }
        });
    }

    // Gọi lại mỗi khi modal được mở
    document.addEventListener('DOMContentLoaded', function () {
        const createModal = document.getElementById('courseCreateModal');
        const editModal = document.getElementById('courseEditModal');

        if (createModal) {
            createModal.addEventListener('shown.bs.modal', function () {
                initLearningFieldsDisplay('create');
            });
        }

        if (editModal) {
            editModal.addEventListener('shown.bs.modal', function () {
                initLearningFieldsDisplay('edit');
            });
        }
    });
</script>



{{-- <script>
    function toggleCheckboxes(fieldName, selectAllId) {
        var checkboxes = document.getElementsByName(fieldName + '[]');
        var selectAll = document.getElementById(selectAllId);

        checkboxes.forEach(function(checkbox) {
            checkbox.checked = selectAll.checked;
            toggleHourKmFields(checkbox.value);
        });
    }

    function toggleHourKmFields(fieldId) {
        var fieldDetails = document.getElementById('learning_field_' + fieldId + '_details');
        var checkbox = document.getElementById('learning_field_' + fieldId);

        // Hiển thị hoặc ẩn phần giờ và km dựa vào trạng thái checkbox
        if (checkbox && fieldDetails) {
            fieldDetails.style.display = checkbox.checked ? 'block' : 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkedLearningFields = document.querySelectorAll('input[name="learning_fields[]"]:checked');

        checkedLearningFields.forEach(function(checkbox) {
            toggleHourKmFields(checkbox.value);
        });
    });
</script> --}}
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
                            ${group.items.map(item => `
                                <tr>
                                    <td>${item.date_start}</td>
                                    <td>${item.date_end}</td>
                                    <td>${item.hours}</td>
                                    <td>${item.km}</td>
                                    <td>${item.remarks || '-'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
        
                container.appendChild(groupEl);
            });
        }
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
    
                const courseId = this.getAttribute('data-course-id');
                const currentDetailRow = document.getElementById('details-row-' + courseId);
                const currentMainRow = document.querySelector(`.course-main-row[data-course-id="${courseId}"]`);
    
                const allDetailRows = document.querySelectorAll('.course-detail-row');
                const allMainRows = document.querySelectorAll('.course-main-row');
    
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
                document.querySelectorAll('.course-detail-row').forEach(row => {
                    bootstrap.Collapse.getOrCreateInstance(row, { toggle: false }).hide();
                });
                document.querySelectorAll('.course-main-row').forEach(row => {
                    row.style.display = '';
                });
            }
        });
    });
</script>
<script src="{{ asset('assets/js/nouislider.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sliderFee = document.getElementById('tuition-slider');
        const sliderCountStudent = document.getElementById('student-slider');
        if (sliderFee) {
            noUiSlider.create(sliderFee, {
                start: [
                    parseInt(document.getElementById('tuition_fee_min').value || 0),
                    parseInt(document.getElementById('tuition_fee_max').value || 50000000)
                ],
                connect: true,
                range: {
                    min: 0,
                    max: 50000000
                },
                step: 50000,
                format: {
                    to: value => Math.round(value),
                    from: value => Number(value)
                }
            });

            const minInput = document.getElementById('tuition_fee_min');
            const maxInput = document.getElementById('tuition_fee_max');
            const displayValue = document.getElementById('tuition-slider-value');

            sliderFee.noUiSlider.on('update', function (values) {
                const min = values[0];
                const max = values[1];
                minInput.value = min;
                maxInput.value = max;
                displayValue.innerText = `${Number(min).toLocaleString()} - ${Number(max).toLocaleString()} VND`;
            });
        }

        if (sliderCountStudent) {
            noUiSlider.create(sliderCountStudent, {
                start: [
                    parseInt(document.getElementById('student_count_min').value || 0),
                    parseInt(document.getElementById('student_count_max').value || 2000)
                ],
                connect: true,
                range: {
                    min: 0,
                    max: 500
                },
                step: 1,
                format: {
                    to: value => Math.round(value),
                    from: value => Number(value)
                }
            });

            const minInputStudent = document.getElementById('student_count_min');
            const maxInputStudent = document.getElementById('student_count_max');
            const displayValueStudent = document.getElementById('student-slider-value');

            sliderCountStudent.noUiSlider.on('update', function (values) {
                const min = values[0];
                const max = values[1];
                minInputStudent.value = min;
                maxInputStudent.value = max;
                displayValueStudent.innerText = `${Number(min).toLocaleString()} - ${Number(max).toLocaleString()} Học viên`;
            });
        }
    });
</script>
@endsection
