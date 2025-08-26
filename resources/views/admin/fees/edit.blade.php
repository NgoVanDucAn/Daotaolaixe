@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('fees.update', $fee->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="payment_date" class="form-label">Ngày Thanh Toán</label>
                    <input type="datetime-local" class="form-control" name="payment_date" id="payment_date" value="{{ old('payment_date', $fee->payment_date->format('Y-m-d h:i')) }}" required>
                    @error('payment_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="amount" class="form-label">Số Tiền</label>
                    <input type="text" class="form-control currency-inpu" name="amount" id="amount" value="{{ old('amount', $fee->amount) }}" required>
                    @error('amount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="student_id" class="form-label">Học Viên</label>
                    <select class="form-select" name="student_id" id="student_id" required>
                        <option value="">Chọn học viên</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" @if(old('student_id', $fee->student_id) == $student->id) selected @endif>{{ $student->name }} - {{ $student->student_code }}</option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="course_student_id" class="form-label">Khóa Học Của Học Viên</label>
                    <select class="form-select" name="course_student_id" id="course_student_id">
                        <option value="">Chọn khóa học của học viên</option>
                        @foreach ($courseStudents as $courseStudent)
                            <option value="{{ $courseStudent->id }}" {{ old('course_student_id', $courseStudentId) == $courseStudent->id ? 'selected' : ''}}>{{ $courseStudent->course->code }}</option>
                        @endforeach
                    </select>
                    @error('course_student_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="fee_type" class="form-label">Loại Thu</label>
                    <select name="fee_type" id="fee_type" class="form-select" required>
                        <option value="">Chọn loại thu</option>
                        @foreach (App\Models\Fee::getTypeOptions() as $key => $label)
                            <option value="{{ $key }}" {{ old('fee_type', $fee->fee_type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('fee_type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="collector_id" class="form-label">Người thu</label>
                    <select class="form-select" name="collector_id" id="collector_id">
                        <option value="">Chọn người thu</option>
                        @foreach ($collectors as $collector)
                            <option value="{{ $collector->id }}" {{ old('collector_id', $fee->collector_id) == $collector->id ? 'selected' : '' }}>{{ $collector->name }}</option>
                        @endforeach
                    </select>
                    @error('course_student_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 d-flex align-items-end mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="is_received" id="is_received" value="1" {{ old('is_received', $fee->is_received) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_received">Đã nhận</label>
                        @error('is_received')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <!-- Nút thêm khóa học -->
                <div class="col-12 col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center align-items-end mb-3">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#new-course-form" aria-expanded="false" aria-controls="new-course-form">
                        Thêm khóa học mới cho học viên
                    </button>
                </div>

                <!-- Form nhập thông tin khóa học, mặc định ẩn -->
                <div class="collapse mt-3" id="new-course-form">
                    <div class="card card-body">
                        <h5>Nhập Thông Tin Bổ Sung Cho Học Viên Khi Tham Gia Khóa Học</h5>
                        <div class="d-flex justyfy-content-between">
                            <div id="student-fields-container">
                                <div class="student-entry d-flex justyfy-content-evenly">
                                    <div class="form-group me-2">
                                        <label for="course_id" class="form-label">Khóa học</label>
                                        <select class="form-select" name="course_id" id="course_id">
                                            <option value="">Chọn khóa học</option>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group me-2 position-relative">
                                        <label for="contract_date" class="form-label">Ngày ký hợp đồng:</label>
                                        <input 
                                            type="text"
                                            placeholder="dd/mm/yyyy"
                                            name="contract_date" 
                                            id="contract_date" 
                                            class="form-control real-date" autocomplete="off" 
                                        >
                                    </div>
                                    <div class="form-group me-2 position-relative">
                                        <label for="health_check_date" class="form-label">Ngày khám sức khỏe:</label>
                                        <input 
                                            type="text"
                                            placeholder="dd/mm/yyyy"
                                            name="health_check_date" 
                                            id="health_check_date"
                                            autocomplete="off"
                                            class="form-control real-date" autocomplete="off"
                                        >
                                    </div>
                                    <div class="form-group me-2">
                                        <label for="teacher_id" class="form-label">Chọn giáo viên</label>
                                        <select name="teacher_id" id="teacher_id" class="form-control">
                                            <option value="">Chọn giáo viên</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group me-2">
                                        <label for="practice_field" class="form-label">Địa điểm thực hành:</label>
                                        <input type="text" name="practice_field" id="practice_field" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <label for="note" class="form-label">Ghi Chú</label>
                    <textarea class="form-control" name="note" id="note" rows="1">{{ old('note', $fee->note) }}</textarea>
                    @error('note')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="{{ route('fees.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
<script>
    let courseStudents = @json($courseStudents);
    let courses = @json($courses);

    document.getElementById('student_id').addEventListener('change', function() {
        let studentId = this.value;
        let courseSelect = document.getElementById('course_student_id');
        let addCourseSelect = document.getElementById('course_id');

        // Xóa các option cũ
        courseSelect.innerHTML = '<option value="">Chọn khóa học của học viên</option>';
        addCourseSelect.innerHTML = '<option value="">Chọn khóa học</option>';

        // Lọc danh sách khóa học theo học viên được chọn
        let filteredCourses = courseStudents.filter(cs => cs.student_id == studentId);
        let availableCourses = courses.filter(course => !filteredCourses.some(cs => cs.course_id === course.id));

        // Thêm khóa học của học viên tương ứng vào dropdown
        filteredCourses.forEach(cs => {
            let option = document.createElement('option');
            option.value = cs.id;
            option.textContent = cs.course.code;
            courseSelect.appendChild(option);
        });

        // Thêm khóa học vào dropdown sau khi loại bỏ những khóa học mà học viên đã đăng ký trước đó
        availableCourses.forEach(course => {
            let option = document.createElement('option');
            option.value = course.id;
            option.textContent = course.code;
            addCourseSelect.appendChild(option);
        });
    });
</script>
@endsection
