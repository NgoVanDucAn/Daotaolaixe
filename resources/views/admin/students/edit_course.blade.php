@extends('layouts.admin')

@section('title', 'Thông Tin Sinh Viên và Khóa Học')
@section('css')
    <style>
        .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
@endsection

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => url()->previous(),
            'title' => 'Quay về trang trước'
        ])
    </div>
@endsection


@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- LEFT SIDE -->
                <div class="col-12 col-lg-8 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <!-- Học viên -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="student_id" class="form-label">Chọn học viên</label>
                                    <select name="name_student" id="name_student" class="form-control" disabled>
                                        <option value="{{ $student->id }}">{{ $student->name}} - {{ $student->student_code }}</option>
                                    </select>
                                    @error('student_id') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                <!-- Khóa học -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label fw-bold">Chọn khóa học</label>
                                    <select name="name_course" id="name_course" class="form-control" disabled>
                                        @foreach ($courseAlls as $courses)
                                            <option value="{{ $courses->id ==  $courseStudent->course_id}}">{{ $courses->code }}</option>
                                        @endforeach
                                        {{-- <option value="{{ $courseStudent->id  }}">{{ $courseStudent->code }}</option> --}}
                                    </select>
                                </div>

                                <!-- Học phí -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="tuition_fee" class="form-label">Học phí</label>
                                    <input type="text" id="tuition_fee" name="tuition_fee" class="form-control currency-input" value="{{ $courseStudent->tuition_fee }}" required>
                                    @error('tuition_fee') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                <!-- Ngày khám SK -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="health_check_date" class="form-label">Ngày khám sức khỏe</label>
                                    <input 
                                        type="text" 
                                        placeholder="dd/mm/yyyy" 
                                        name="health_check_date" 
                                        value="{{ old('health_check_date') ? \Carbon\Carbon::parse(old('health_check_date'))->format('d/m/Y') : \Carbon\Carbon::parse($courseStudent->health_check_date)->format('d/m/Y') }}"
                                        class="form-control real-date" autocomplete="off" 
                                        required
                                    >
                                </div>

                                <!-- Sân thi -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="stadium_id" class="form-label">Sân thi</label>
                                    <select name="stadium_id" id="stadium_id" class="form-select">
                                        <option value="">Chọn sân thi</option>
                                        @foreach($stadiums as $stadium)
                                            <option value="{{ $courseStudent->stadium_id ? $courseStudent->stadium_id : $stadium->id }}">
                                                {{ $stadium->location }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('stadium_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <!-- Ngày ký HĐ -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="contract_date">Ngày ký hợp đồng</label>
                                    <input 
                                        type="text" 
                                        placeholder="dd/mm/yyyy" 
                                        name="contract_date" 
                                        id="contract_date" 
                                        class="form-control real-date" autocomplete="off" 
                                        value="{{ old('contract_date') ? \Carbon\Carbon::parse(old('contract_date'))->format('d/m/Y') : \Carbon\Carbon::parse($courseStudent->contract_date)->format('d/m/Y') }}"
                                    >
                                </div>
                                <!-- Giờ chip tặng -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="give_chip_hour">Số giờ chip tặng</label>
                                    <input type="text" name="give_chip_hour" id="give_chip_hour" class="form-control time-input" value="{{ \Carbon\Carbon::parse($courseStudent->gifted_chip_hours)->format('H:i') }}">
                                </div>

                                <!-- Giờ chip đặt -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="order_chip_hour">Số giờ đặt chip</label>
                                    <input type="text" name="order_chip_hour" id="order_chip_hour" class="form-control time-input" value="{{ \Carbon\Carbon::parse($courseStudent->reserved_chip_hours)->format('H:i') }}">
                                </div>

                                <!-- Ghi chú -->
                                <div class="col-12 mb-3">
                                    <label for="note">Ghi chú</label>
                                    <textarea name="note" id="note" class="form-control">{{ $courseStudent->note ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div class="col-12 col-lg-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <!-- Giáo viên -->
                            <div class="col-12 mb-3">
                                <label for="learn_teacher_id" class="form-label">Giáo viên</label>
                                <select name="learn_teacher_id" id="learn_teacher_id" class="form-control @error('learn_teacher_id') is-invalid @enderror">
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $courseStudent->teacher_id ? $courseStudent->teacher_id : $teacher->id }}" {{ old('learn_teacher_id') }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('learn_teacher_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Sale -->
                            <div class="col-12 mb-3">
                                <label for="sale_id" class="form-label">Nhân viên Sale</label>
                                <select name="sale_id" id="sale_id" class="form-control @error('sale_id') is-invalid @enderror">
                                    <option value="">-- Chọn nhân viên sale --</option>
                                    @foreach ($sales as $sale)
                                        <option value="{{ $courseStudent->sale_id ? $courseStudent->sale_id : $sale->id }}" {{ (old('sale_id', $student->saleSupport->id ?? '') == $sale->id) ? 'selected' : '' }}>
                                            {{ $sale->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sale_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Button -->
                            <button type="submit" class="btn btn-primary mt-3 ps-2 pe-2 pt-0 pb-0">
                                <b>
                                    <i class="mdi mdi-content-save fs-4"></i>Lưu
                                </b>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.students.common-calendar')
@endsection
@section('js')
<script>
    
    $(document).ready(function() {   
       $('#sale_id').select2({
           placeholder: "-- Chọn nhân viên Sale --",
           allowClear: true,
       });
       $('#learn_teacher_id').select2({
           placeholder: "-- Chọn giáo viên --",
           allowClear: true,
       });
       $('#stadium_id').select2({
           placeholder: "-- Chọn sân --",
           allowClear: true,
       });
   });
</script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addFeeModal = document.getElementById('addFeeModal');
            addFeeModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const studentId = button.getAttribute('data-student-id');
                const studentName = button.getAttribute('data-student-name');
                const courseStudentId = button.getAttribute('data-course-student-id');
                const courseCode = button.getAttribute('data-course-code');

                // Điền dữ liệu vào form
                const studentIdInput = addFeeModal.querySelector('#student_id');
                const studentDisplayInput = addFeeModal.querySelector('#student_display');
                const courseStudentIdInput = addFeeModal.querySelector('#course_student_id');
                const courseDisplayInput = addFeeModal.querySelector('#course_display');

                studentIdInput.value = studentId;
                studentDisplayInput.value = studentName;
                courseStudentIdInput.value = courseStudentId;
                courseDisplayInput.value = courseCode;
            });
        });
    </script>
    
    @php
        $calendarTypes = collect(['study_practice', 'study_theory', 'exam_theory', 'exam_practice', 'exam_graduation', 'exam_certification']);
        $studyTypes = ['study_practice', 'study_theory'];
    @endphp

    @if ($calendarTypes->intersect($studyTypes)->isNotEmpty())
        <script src="{{ asset('assets/js/add-calendar-modal.js') }}"></script>
    @else
        <script src="{{ asset('assets/js/add-calendar-exam-modal.js') }}"></script>
    @endif

@endsection
