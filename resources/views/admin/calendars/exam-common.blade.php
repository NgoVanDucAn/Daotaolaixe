@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
            <form id="calendarFilterForm" class="row" method="GET">
                <div class="col-12 col-md-3 mb-3">
                    <label for="stadium_id" class="form-label">Sân thi</label>
                    <select name="stadium_id" id="stadium_id" class="form-select select2">
                        <option value=""></option>
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

                <div class="col-12 col-md-3 mb-3">
                    <label for="student_id" class="form-label">Học viên</label>
                    <select name="student_id" id="student_id" class="form-select select2">
                        <option value=""></option>
                        @foreach ($studentAlls as $studentAll)
                            <option value="{{ $studentAll->id }}" {{ request('student_id') == $studentAll->id ? 'selected' : '' }}>
                                {{ $studentAll->name }} - {{ $studentAll->student_code }}
                            </option>
                        @endforeach
                    </select>
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
                        placeholder="Ngày kết thúc"
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
                    <a href="{{ route($removeFilter) }}" class="btn btn-secondary mb-1" id="clearFilters">Bỏ Lọc</a>
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
        <button type="button"
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;"
            data-bs-toggle="modal"
            data-bs-target="#addActivitieModal"
            data-calendar-type="{{ $typeAccept }}"
            data-exam-course-type="{{ $courseType }}"
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Tạo lịch thi {{ $description }}</span>
        </button>
    </div>
@endsection

<div class="card" id="card">
    <div class="card-body">
        <a href="{{ route('calendars.create') }}"
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 40px; height: 40px; font-size: 30px; bottom: 3%; right: 60px; z-index: 99;"
            data-bs-toggle="modal" data-bs-target="#addActivitieModal"
            data-calendar-type="{{ $typeAccept }}"
            data-exam-course-type="{{ $courseType }}"
            >
            <i class="mdi mdi-plus"></i>
        </a>

        {{-- Tab Content cho loại lịch --}}
        <div class="tab-content mt-3" id="calendarTypeTabsContent">
            @foreach($calendarTypes as $key => $label)
                <div class="tab-pane fade {{ $key == $type ? 'show active' : '' }}" id="tab-{{ $key }}" role="tabpanel" aria-labelledby="tab-{{ $key }}-tab">
                    <!-- Nếu người dùng chọn loại lịch này, sẽ hiển thị nội dung dưới đây -->
                    {{-- <h5>Lịch thi {{ $description }}</h5> --}}
                    <div class="alert alert-primary d-flex align-items-center" role="alert">
                        <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-20 me-1"></iconify-icon>
                        <div class="lh-1"><strong>Tổng số lượng học viên: </strong><strong>{{ $totalStudentCount }}</strong></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Buổi</th>
                                    <th>Thứ</th>
                                    <th>Ngày</th>
                                    <th>Sân thi</th>
                                    <th>Số lượng học viên</th>
                                    <th>Chi tiết</th>
                                </tr>

                            </thead>
                            <tbody>
                                @php
                                $counter = 1;
                                \Carbon\Carbon::setLocale('vi');
                                $timeMap = [
                                    1 => 'Buổi sáng',
                                    2 => 'Buổi chiều',
                                    3 => 'Cả ngày',
                                ];
                            @endphp
                            @foreach($paginatedCalendars as $date => $groupDay)
                                @foreach($groupDay['calendars_by_time'] as $time => $groupTime)
                                    @foreach($groupTime['calendars_by_stadium'] as $stadiumId => $groupStadium)
                                        <tr>
                                            <td>{{ $counter++ }}</td>
                                            <td>
                                                <a
                                                    style="font-weight: 600; color: #4C9AFF"
                                                    href="{{ route($detailRoute, [ 'time' => $time]) }}"
                                                    title="Xem tất cả lịch theo thời gian này "
                                                >
                                                    @switch($time)
                                                        @case(1) Buổi sáng @break
                                                        @case(2) Buổi chiều @break
                                                        @case(3) Cả ngày @break
                                                        @default -
                                                    @endswitch
                                                    </a>

                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}
                                            </td>
                                            <td>
                                                <a style="font-weight: 600; color: #4C9AFF"
                                                    href="{{ route($detailRoute, ['start_date' => $date]) }}"
                                                    title="Xem tất cả lịch tại ngày này"
                                                >

                                                    {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
                                                </a>
                                            </td>
                                            <td>
                                                @php $sid = $groupStadium['stadium']->id ?? null; @endphp
                                                @if($sid)
                                                    <a
                                                        href="{{ route($detailRoute, ['stadium_id' => $sid]) }}"
                                                        class="fw-semibold"
                                                        style="color:#4C9AFF"
                                                        title="Xem tất cả lịch tại sân này"
                                                    >
                                                        {{ $groupStadium['stadium']->location }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                {{ $groupStadium['student_count'] }}
                                            </td>

                                            {{-- THÊM CỘT "CHI TIẾT" Ở ĐÂY --}}
                                            <td class="text-nowrap">
                                                @foreach($groupStadium['calendars'] as $cal)
                                                    <a
                                                        href="{{ route($detailRoute, ['calendar_id' => $cal->id]) }}"
                                                        class="btn btn-sm btn-outline-primary me-1 mb-1">
                                                        Xem chi tiết
                                                    </a>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
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
<!-- Modal thêm mới lịch -->
@include('admin.calendars.add-calendar-modal')

@if (old('modal_name') && $errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let modalName = @json(old('modal_name'));
            const vehicleType = @json(old('vehicle_type'));
            const type = @json(old('type'));

            const calendarType = @json(old('type')); // nếu type chính là calendarType
            const examStudentIdSelect = $('#exam_student_id_select');
            const studentInputs = $('#student-inputs')

            const oldIds = @json(old('exam_student_id', []));
            const oldExamIds = @json(old('exam_id', []));
            const oldStudents = @json(old('students'))

            const studyFields = $('#study-fields');
            const examFields = $('#exam-fields');
            const subjectContainer = document.getElementById('exam_id'); // ID container môn thi

            // Ẩn/hiện field theo type
            if (type === 'exam') {
                examFields.show();
                studyFields.hide();
            } else {
                examFields.hide();
                studyFields.show();
            }

            if(oldIds.length > 0 ){
                studentInputs.show();
            }

            let url = `/calendars/infor?vehicle_type=${vehicleType}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log(data);

                    /*** XỬ LÝ MÔN THI ***/
                    subjectContainer.innerHTML = '';
                    let subjects = data.exam_fields || [];

                    // ✅ Lọc theo điều kiện calendarType = 'exam_theory' hoặc 'exam_practice'
                    if (calendarType === 'exam_practice' || calendarType === 'exam_theory') {
                        if (vehicleType == 1) {
                            $('.exam_id').hide();
                            subjects = [];
                        } else if (vehicleType == 2) {
                            $('.exam_id').show();
                            const isPractice = calendarType === 'exam_practice';
                            subjects = subjects.filter(subject => subject.is_practical == (isPractice ? 1 : 0));
                        }
                    }

                    // Nếu còn môn thi
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

                        // Chuyển tất cả phần tử của oldExamIds thành string để so sánh
                        const isChecked = oldExamIds.includes(String(subject.id)) ? 'checked' : '';

                        col.innerHTML = `
                            <input type="checkbox" name="exam_id[]" value="${subject.id}" class="form-check-input exam-checkbox" id="exam_${subject.id}" ${isChecked}>
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

                    /*** XỬ LÝ HỌC VIÊN ***/
                    examStudentIdSelect.empty();
                    (data.students || []).forEach(student => {
                        examStudentIdSelect.append(
                            $('<option>', {
                                value: student.id,
                                text: student.label
                            })
                        );
                    });

                    $('#exam_student_id_select').select2({
                        dropdownParent: $('#addActivitieModal'),
                        placeholder: "Chọn học viên khóa học",
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

                                // Lấy dữ liệu cũ (nếu có)
                                const oldData = oldStudents[studentId] || {};
                                const examNumber = oldData.exam_number || '';
                                const pickupChecked = oldData.pickup == "1" ? 'checked' : '';

                                const groupHtml = `
                                    <div class="student-input-group border p-4 rounded bg-gray-50" data-student-id="${studentId}">
                                        <div class="mb-2 font-semibold">${studentName}</div>
                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Số báo danh:</label>
                                            <input style="width: 100%;" type="text" name="students[${studentId}][exam_number]"
                                                value="${examNumber}" class="w-full rounded border-gray-300">
                                        </div>
                                        <div>
                                            <label class="block text-sm mb-1">
                                                <input type="checkbox" name="students[${studentId}][pickup]" value="1" class="mr-1" ${pickupChecked}>
                                                Đăng ký đưa đón
                                            </label>
                                        </div>
                                    </div>
                                `;
                                $container.append(groupHtml);
                            }
                        });
                    });


                    examStudentIdSelect.val(oldIds).trigger('change');

                })
                .catch(error => console.error('Error:', error));

            let myModal = new bootstrap.Modal(document.getElementById(modalName));
            myModal.show();
        });
    </script>

@endif
<script>
    console.log(@json(old()));

</script>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#stadium_id').select2({
                placeholder: 'Chọn sân',
                width: '100%',
                allowClear: true
            });
            $('#student_id').select2({
                placeholder: 'Chọn học viên',
                width: '100%',
                allowClear: true
            });
        });
    </script>
    <script src="{{ asset('assets/js/add-calendar-exam-modal.js') }}"></script>


@endsection
