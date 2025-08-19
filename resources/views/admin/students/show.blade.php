@extends('layouts.admin')

@section('page-action-back-button')

    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            // 'route' => route('students.index'),
            'route' =>  url()->previous(),
            'title' => 'Quay về trang trước'
        ])
    </div>
@endsection
@section('content')

<div class="card">
    <div class="card-body">
        <span style="font-size: 24px">Thông tin <b>{{ $student->name }}</b></span>
        <div class="table-responsive">
            <table class="table mt-3 table-bordered">
                <tr>
                    <td>Avatar</td>
                    <td>
                        <img 
                            style="width: 50px;
                            height: 50px;
                            border-radius: 100%;" 
                            src="{{ $student->image ? $student->image : asset('assets/images/no_image.jpg') }}" 
                            
                            alt="Avatar"
                        >
                    </td>
                </tr>
                <tr>
                    <td>Tên</td>
                    <td>{{ $student->name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $student->email }}</td>
                </tr>
                <tr>
                    <td>Giới tính</td>
                    <td>{{ $student->gender }}</td>
                </tr>
                <tr>
                    <td>Số điện thoại</td>
                    <td>{{ $student->phone }}</td>
                </tr>
                <tr>
                    <td>Ngày sinh</td>
                    <td>{{ $student->dob ? $student->dob->format('d/m/Y') : 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>



<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between aligt-items-center" style="margin-bottom: 20px">
            <div style="font-size: 20px"> Danh sách khoá học</div>
            <button 
                type="button" 
                class="btn btn-add-student-course"
                style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;"
                data-bs-toggle="modal"
                data-bs-target="#addStudentModal"
                data-student-id="{{ $student->id }}"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                data-add-student-course="{{ $student }}"
                data-rankings="{{ $rankings }}"
            >
                <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Thêm mới</span>
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle">STT</th>
                        <th rowspan="2" class="text-nowrap align-middle">Mã học viên</th>
                        <th rowspan="2" class="text-nowrap align-middle">Khóa học</th>
                        <th rowspan="2" class="align-middle">Hạng</th>
                        <th rowspan="2" class="text-nowrap align-middle">Họ và tên</th>
                        <th rowspan="2" class="text-nowrap align-middle">Ngày sinh</th>
                        <th rowspan="2" class="text-nowrap align-middle">Giới tính</th>
                        <th rowspan="2" class="text-nowrap align-middle">Ngày ký hợp đồng</th>
                        <th rowspan="2" class="text-nowrap align-middle">CMT/CCCD</th>
                        <th rowspan="2" class="text-nowrap align-middle">Khám sức khoẻ</th>
                        <th rowspan="2" class="text-nowrap align-middle">Địa chỉ</th>
                        <th rowspan="2" class="text-nowrap align-middle">Số thẻ</th>
                        <th rowspan="2" class="text-nowrap align-middle">Giáo viên</th>
                        <th colspan="3" class="align-middle">Tình trạng thi</th>
                        <th colspan="4" class="align-middle">Phiên học</th>
                        <th colspan="3" class="align-middle">Học phí</th>
                        <th rowspan="3" class="align-middle">Trạng thái</th>
                        <th rowspan="3" class="align-middle">Hành động</th>
                    </tr>
                    <tr>
                        <th class="align-middle">Thi hết môn LT</th>
                        <th class="align-middle">Thi hết môn TH</th>
                        <th class="align-middle">Thi tốt nghiệp</th>
                        <th class="align-middle">Giờ</th>
                        <th class="align-middle">Km</th>
                        <th class="align-middle">Giờ đêm</th>
                        <th class="align-middle">Giờ tự động</th>
                        <th class="align-middle">Tổng</th>
                        <th class="align-middle">Đã nạp</th>
                        <th class="align-middle">Còn thiếu</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $courseCount = $student->courses->count();
                    @endphp
                    
                    @foreach ($student->courses as $index => $course)
                        <tr class="student-main-row" data-student-id="{{ $student->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student->student_code }}</td>
                            <td><span class="badge bg-success">
                                {{ ($course->code ?? 'Chưa có khóa học') }}
                            </span></td>
                            <td>
                                <span class="badge bg-success">
                                    @if ($course->id == 99999999)
                                        {{ ucfirst($student->ranking->name ) }} chưa xếp
                                    @else
                                        {{ ucfirst($course->ranking->name ?? 'Chưa có khóa học') }}
                                    @endif
                                </span>
                            </td>
                            <td class="text-nowrap text-start">
                                <a href="{{ route('student.course.action', ['student' => $student->id, 'course' => $course->id]) }}" class="toggle-detail" style="padding: 2px 12px;">
                                    {{ $student->name }}
                                </a>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($student->dob)->format('d/m/Y') }}</td>
                            <td>
                                @if($student->gender == 'male') Nam
                                @elseif($student->gender == 'female') Nữ
                                @else Khác
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($course->pivot->contract_date)->format('d/m/Y') }}</td>
                
                            <td class="text-center">{{ $student->identity_card ?? '--' }}</td>
                    
                            <td>{{ \Carbon\Carbon::parse($course->pivot->health_check_date)->format('d/m/Y') }}</td>
                            <td class="text-nowrap text-start">{{ $student->address ?? '--' }}</td>
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
                    
                            {{-- <td>
                                <a href="{{ route('student.course.action', ['student' => $student->id, 'course' => $course->id]) }}">
                                    <span class="badge bg-success">{{ $course->code }}</span>
                                </a>
                            </td> --}}
                            <td class="text-nowrap">
                                @foreach ($teachers as $teacher)
                                    @if ($teacher->id == $course->pivot->teacher_id)
                                        {{ $teacher->name }}
                                    @endif
                                @endforeach
                            </td>
                    
                            {{-- Phần thông tin học tập theo khóa học --}}
                            <td></td>
                            <td>0 new</td>
                            <td>0 new</td>
                            <td>
                                <button class="btn btn-sm btn-info"
                                    data-student-id="{{ $student->id }}"
                                    data-course-id="{{ $course->id }}"
                                    onclick="showStudyDetails(this)">
                                    {{ $totalHours[$student->id][$course->id] ?? 0 }}/{{ $course->duration ?? '-' }}
                                </button>
                            </td>
                            <td>{{ $totalKm[$student->id][$course->id] }}/{{ $course->km ?? '-' }}</td>
                            <td>{{ $totalNightHours[$student->id][$course->id] ?? 0 }}</td>
                            <td>{{ $totalAutoHours[$student->id][$course->id] ?? 0 }}</td>
                            <td>
                                @if(isset($remainingFees[$student->id][$course->id]))    
                                    {{ number_format($course->pivot->tuition_fee, 0, ',', '.') }} VND
                                @else
                                    0 VND
                                @endif
                            </td>
                            <td>
                                @if(isset($remainingFees[$student->id][$course->id]))    
                                    {{ number_format($courseFees[$student->id][$course->id], 0, ',', '.') }} VND
                                @else
                                    0 VND
                                @endif
                            </td>
                            <td>
                                @if(isset($remainingFees[$student->id][$course->id]))
                                    <span class="badge bg-danger">
                                        {{ number_format($remainingFees[$student->id][$course->id], 0, ',', '.') }} VND
                                    </span>
                                @else
                                    <span class="badge bg-success">0 VND</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge 
                                    {{ 
                                        $course->pivot->status == 1 ? 'bg-success' : 
                                        ($course->pivot->status == 2 ? 'bg-warning' : 
                                        ($course->pivot->status == 3 ? 'bg-primary' : 'bg-secondary')) 
                                    }}">
                                    {{ 
                                        $course->pivot->status == 0 ? 'Chưa học' : 
                                        ($course->pivot->status == 1 ? 'Đang học' : 
                                        ($course->pivot->status == 2 ? 'Bỏ học' : 'Đã tốt nghiệp')) 
                                    }}
                                </span>
                            </td>
                    
                            <td class="text-nowrap">
                                <a 
                                    href="{{ route('student.course.action', ['student' => $student->id, 'course' => $course->id]) }}"
                                    class="btn btn-sm btn-info m-1" 
                                    style="padding: 2px 12px;">
                                    <i class="mdi mdi-eye-outline"></i>
                                </a>
                                <a href="{{ route('student.course.edit', ['student' => $student->id, 'course' => $course->id]) }}" class="btn btn-sm btn-warning m-1">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
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
            {{-- {{ $students->links('pagination::bootstrap-5') }} --}}
        </div>
    </div>
</div>

<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Thêm khóa học cho học viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
            </div>
            <div class="modal-body">
                <div id="show-noti" class="text-warning mb-2"></div>
                <form id="formAddStudent" action="{{ route('courses.addStudent') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="modal_name" value="addStudentModal">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="student_id" class="form-label">Chọn học viên</label>
                                    <select name="student_id" id="add_student_id" class="form-select">
                                        <option value="">-- Chọn học viên --</option>
                                        @foreach($studentsAll as $student)
                                            <option value="{{ $student->id }}" data-student="{{ $student }}">{{ $student->name}} - {{ $student->student_code }}</option>
                                        @endforeach
                                    </select>
                                    @error('sale_support')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label fw-bold">Chọn khóa học</label>
                                    <select name="course_id" id="add_course_id" class="form-select">
                                        <option value="">Chọn khóa học</option>
                                        @foreach($courseAlls as $course)
                                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                                {{ $course->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="tuition_fee" class="form-label">Học phí</label>
                                    <input type="text" name="tuition_fee" class="form-control currency-input" value="{{ old('tuition_fee') }}" required>
                                    @error('tuition_fee') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12 col-md-6 mb-3 position-relative">
                                    <label for="health_check_date" class="form-label">Ngày khám sức khỏe</label>
                                    <input type="text" placeholder="dd/mm/yyyy"name="health_check_date" id="health_check_date" class="form-control real-date" autocomplete="off" required>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="stadium_id" class="form-label">Sân thi</label>
                                    <select name="stadium_id"  id="add_stadium_id" class="form-select">
                                        @foreach($stadiums as $stadium)
                                            <option value="">Chọn sân thi</option>
                                            <option value="{{ $stadium->id }}" {{ old('stadium_id')}}>
                                                {{ $stadium->location }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('stadium_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6 mb-3 position-relative">
                                    <div class="form-group">
                                        <label for="contract_date">Ngày ký hợp đồng</label>
                                        <input type="text" placeholder="dd/mm/yyyy"name="contract_date" id="contract_date" class="form-control real-date" autocomplete="off" value="{{ old('contract_date') }}">
                                    </div>
                                </div>
                                {{-- Số giờ chip tặng new --}}
                                <div class="row p-0 m-0 container-chip-hour">
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="give_chip_hour">Số giờ chip tặng</label>
                                            <input type="text" name="give_chip_hour" id="give_chip_hour" placeholder="HH:mm" class="form-control time-input" value="{{ old('give_chip_hour') }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="order_chip_hour">Số giờ đặt chip</label>
                                            <input type="text" name="order_chip_hour" id="order_chip_hour" placeholder="HH:mm" class="form-control time-input" value="{{ old('order_chip_hour') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="note">Ghi chú</label>
                                        <textarea name="note" id="note" class="form-control">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <label for="learn_teacher_id" class="form-label">Giáo viên</label>
                                    <select name="learn_teacher_id" id="learn_teacher_id" class="form-control @error('learn_teacher_id') is-invalid @enderror">
                                        <option value="">Chọn giáo viên</option>
                                        @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('learn_teacher_id')}}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                    </select>
                                    @error('learn_teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <label for="sale_id" class="form-label">Nhân viên Sale</label>
                                    <select name="sale_id" id="sale_id" class="form-control @error('sale_id') is-invalid @enderror">
                                        <option value="">Chọn nhân viên sale</option>
                                        @foreach ($sales as $sale)
                                            <option value="{{ $sale->id }}">{{ $sale->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sale_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                <button type="submit" class="btn btn-primary" form="formAddStudent">
                    <i class="mdi mdi-content-save-outline me-2"></i> <span>Lưu</span>
                </button>
            </div>
        </div>
    </div>
</div>



@endsection

@section('js')
<script>
    $(document).ready(function() {   
       $('#add_student_id').select2({
           placeholder: "-- Chọn học viên --",
           allowClear: true,
           dropdownParent: $('#addStudentModal')
       });
       $('#stadium_id').select2({
           placeholder: "Chọn sân thi",
           allowClear: true,
       });
       $('#teacher_id').select2({
           placeholder: "Chọn giáo viên",
           allowClear: true,
       });
       $('#course_id').select2({
           placeholder: "Chọn khóa học",
           allowClear: true,
       });
       $('#learn_teacher_id').select2({
           placeholder: "-- Chọn giáo viên --",
           allowClear: true,
       });
       $('#add_stadium_id').select2({
           placeholder: "-- Chọn sân thi --",
           allowClear: true,
       });
       $('#add_course_id').select2({
           placeholder: "-- Chọn khóa học --",
           allowClear: true,
       });
       $('#sale_id').select2({
           placeholder: "-- Chọn nhân viên sale --",
           allowClear: true,
       });
   });
</script>
{{-- add course --}}
<script>
    let selectedCourse = null;
    let selectedStudent = null;
    let cachedCourses = [];
    const contractDateInput = $('#contract_date')
    // ✅ Hàm kiểm tra và hiển thị học phí
    flatpickr(contractDateInput, {
        dateFormat: "d/m/Y",
    });

    // click trong table
    $(document).on('click', '.btn-add-student-course', function () {
        selectedStudent = $(this).data('add-student-course');
        $('#add_student_id').prop('disabled', true);
        $('#add_student_id').val(selectedStudent.id).trigger('change');
    });

    // Optional: reset lại khi modal đóng
    $('#addStudentModal').on('hidden.bs.modal', function () {
        $('.student-select').hide();
        $('.student-input').hide();

        selectedCourse = null;
        selectedStudent = null;

        // Reset input
        $('#student_id').val('');
        $('#add_course_id').val('');
        $('input[name="tuition_fee"]').val('');
        $('#contract_date').val('');
        $('#show-noti').text('');

        // Optional: reset select2 nếu đang dùng
        if ($('#student_id').hasClass("select2-hidden-accessible")) {
            $('#student_id').val(null).trigger('change');
        }
    });
    
    function formatDate(item){
        const date = new Date(item);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();

        return `${day}/${month}/${year}`;
    }

    function checkSelected(ranking) {  
        if (selectedCourse && selectedStudent) {
            if(selectedStudent?.date_of_profile_set) {
                const value = formatDate(selectedStudent?.date_of_profile_set)
                contractDateInput.val(value)
            }
            
            if (selectedCourse?.ranking_id == selectedStudent?.ranking_id) {
                $('#show-noti').text('');
                return $('input[name="tuition_fee"]').val(formatNumber(selectedStudent?.fee_ranking));
            } else {
                !(selectedStudent?.rankingOfStudent) ? $('#show-noti').text('') : $('#show-noti').text(`Hạng bằng đăng ký của học viên là ${selectedStudent?.rankingOfStudent?.name} và đang khác với Hạng bằng của khóa học!`);
                return $('input[name="tuition_fee"]').val(formatNumber(selectedCourse?.tuition_fee));
            }
        }
    }

    // ✅ Hàm xử lý khi chọn khóa học
    function handleCourseChange() {
        const selectedId = $('#add_course_id').val();
        selectedCourse = cachedCourses.find(course => course.id == selectedId) || null;
        if(selectedCourse.ranking.vehicle_type == 1) {
            $('.container-chip-hour').show();
        }else {
            $('.container-chip-hour').hide();
        }
        checkSelected();
    }

    // ✅ Hàm xử lý khi chọn học viên
    function handleStudentChange() {
        const selectedOption = this.options[this.selectedIndex];
        const studentData = JSON.parse(selectedOption.getAttribute('data-student'));
        const rankings = $('#addStudentModal').data('student-ranking'); // lấy từ modal

        const rankingOfStudent = rankings.find(item => item.id == studentData.ranking_id)
        
        selectedStudent = {
            ...studentData,
            rankingOfStudent
        };

        checkSelected();
    }

    // ✅ Hàm fetch danh sách khóa học và lưu vào cachedCourses
    function loadCourses() {
        return fetch(`/courses-alls`)
            .then(response => response.json())
            .then(courses => {
                cachedCourses = courses;
                return courses;
            })
            .catch(error => {
                console.error('Lỗi khi tải khóa học:', error);
                return [];
            });
    }

    // ✅ Xử lý khi DOM đã sẵn sàng
    document.addEventListener("DOMContentLoaded", function () {
        $('#addStudentModal').on('show.bs.modal', function (event) {
            $('#student_id').prop('disabled', false);
            const button = $(event.relatedTarget);
            const studentId = button.data('student-id');
            const rankings =  button.data('rankings');
            $('#addStudentModal').data('student-ranking', rankings);

            const courseSelect = $(this).find('select[name="course_id"]');

            $(this).find('input[name="student_id[]"]').val(studentId);
            courseSelect.empty().append('<option value="">Đang tải khóa học...</option>');
            
            loadCourses(1).then(courses => {
                courseSelect.empty().append('<option value="">Chọn khóa học</option>');
                courses.forEach(course => {
                    courseSelect.append(`<option value="${course.id}">${course.code}</option>`);
                });

                // ✅ Nếu có course_id trong query, chọn sẵn
                const dataRequest = @json(request()->query());
                const courseIdFromQuery = dataRequest.course_id;

                if (courseIdFromQuery && courses.some(course => course.id == courseIdFromQuery)) {
                    $('#add_course_id').val(courseIdFromQuery).trigger('change');
                }
            });
        });

        // Gắn sự kiện khi thay đổi select
        $(document).on('change', '#add_course_id', handleCourseChange);
        $(document).on('change', '#add_student_id', handleStudentChange);
    });
</script>

<script>
    $('#course_id').on('change', function () {
        const courseId = $(this).val();
        if (!courseId) return;

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: `/api/courses/${courseId}`,
            type: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            success: function (response) {
                const data = response.data;
                $('#tuition_fee').val(data.tuition_fee || '');
            },
            error: function (xhr, status, error) {
                console.error('Lỗi khi lấy thông tin khóa học:', error);
                alert('Không thể lấy thông tin khóa học.');
            }
        });
    });
</script>


{{-- <script>
    $(document).ready(function() {   
       $('#student_id').select2({
           placeholder: "Chọn học viên",
           allowClear: true,
           dropdownParent: $('#addStudentModal')
       });
   });
</script> --}}
@endsection
