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
        .table-detail td {
            text-align: left;
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
        <div class="card-header">Thông tin {{ $student->name ?? 'NO_NAME' }}</div>
        <div class="card-body">
            <div class="table-responsive">
                <?php
                    $matchedCourse = collect($student['courses'])->firstWhere('id', $course->id);
                ?>
                <table class="table table-bordered table-detail">
                    <tr>
                        <td colspan="2" style="width: 150px;">Avatar</td>
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
                        <td colspan="2" style="width: 150px;">Mã khóa học</td>
                        <td>{{ $course->code ?? 'Chưa có thông tin' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Hạng</td>
                        <td>{{ $course->ranking->name ?? 'Chưa có thông tin' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Họ tên</td>
                        <td>{{ $student->name ?? 'NO_NAME' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Ngày sinh</td>
                        <td> {{ $student->dob ? $student->dob->format('d/m/Y') : 'Chưa có thông tin' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Giới tính</td>
                        <td>{{ $student->gender == 'male' ? 'Nam' : ($student->gender == 'female' ? 'Nữ' : ($student->gender == 'other' ? 'Khác' : 'Chưa có thông tin')) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Số CMT</td>
                        <td>{{ $student->identity_number ?? 'Chưa có thông tin' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Id thẻ</td>
                        <td>{{ $student->id_card ?? 'Chưa có thông tin' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Số thẻ</td>
                        <td>
                            {{ $student->card_id ?? 'Chưa có thông tin'}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Giáo viên</td>
                        <td>
                            @foreach ($teachers as $teacher)
                                @if ($teacher->id == $courseStudent->teacher_id)
                                    {{ $teacher->name }}
                                @endif
                            @endforeach
                        </td>
                        
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Nhân viên Sale</td>
                        <td>
                            @foreach ($sales as $sale)
                                @if ($courseStudent->sale_id == $sale->id)
                                    {{ $sale->name }}
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <script>
                        console.log(@json($courseStudent));
                        
                    </script>
                    <tr>
                        <td colspan="2" style="width: 150px;">Ngày khai giảng</td>
                        <td>{{ $courseStudent->start_date ? ($courseStudent->start_date)->format('d/m/Y') : 'N/A', }}</td>
                        
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Ngày bế giảng</td>
                        <td>
                            {{ $courseStudent->end_date ? ($courseStudent->end_date)->format('d/m/Y') : 'N/A', }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Ngày học Cabin</td>
                        <td>
                            {{ $courseStudent->cabin_learning_date ? ($courseStudent->cabin_learning_date)->format('d/m/Y') : 'N/A', }}
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="3" style="width: 150px;" class="text-nowrap">Thông tin</td>
                        <td class="text-nowrap" style="width: 150px;">Thi hết môn lý thuyết</td>
                        <td>
                            new <i class="mdi mdi-close-circle-outline text-danger fs-2"></i>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap">Thi hết môn thực hành</td>
                        <td>
                            new <i class="mdi mdi-close-circle-outline text-danger fs-2"></i>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap">Thi tốt nghiệp</td>
                        <td>
                            new <i class="mdi mdi-close-circle-outline text-danger fs-2"></i>
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2">Phiên học</td>
                        <td>Giờ</td>
                        <td>{{ $totalHours }}</td>
                    </tr>
                    <tr>
                        <td>Km</td>
                        <td>{{ $totalKm }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Tổng học phí phải đóng</td>
                        <td>{{ $coursePivot && $coursePivot->pivot ? number_format($coursePivot->pivot->tuition_fee) . ' VNĐ' : 'Chưa có thông tin' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Tổng học phí đã nộp</td>
                        <td>
                            {{ number_format($courseFees) }} VNĐ
                            @if (number_format($courseFees))
                                <a href="#history-tuition" type="button" class="btn btn-sm btn-warning m-1">
                                    <i class="mdi mdi-eye-outline"></i>
                                    &nbsp; Xem chi tiết
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Tổng học phí còn thiếu</td>
                        <td> {{ number_format($remainingFees) }} VNĐ</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 150px;">Trạng thái</td>
                        <td>
                            @if ($student->status == 'active')
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Ngưng hoạt động</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            
        </div>
    </div>


    {{-- <div class="card mb-4">
        <div class="card-header">Thông Tin Sinh Viên - Khóa Học</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    @if (!empty(optional($student)->image))
                        <img src="{{ asset('storage/'. $student->image) }}" alt="Avatar" class="img-fluid rounded-circle mb-3" title="avatar học viên">
                    @else
                        @php
                            $user = Auth::user();
                            $name = $user->name ?? 'Khách';
                            $nameParts = explode(' ', $name);
                            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
                        @endphp
                        <div class="w-100 rounded-circle mb-3 d-flex bg-success text-white d-flex align-items-center justify-content-center" style="aspect-ratio: 1; font-size: clamp(2.2rem, 20vw, 4rem); font-weight: bold;">
                            {{ $initials }}
                        </div>
                    @endif
                </div>
                <div class="col-md-10 row">
                    <div class="col-md-3">
                        <p><strong>Mã Sinh Viên:</strong> {{ $student->student_code ?? 'Chưa có thông tin' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Họ Tên:</strong> {{ $student->name ?? 'Chưa có thông tin' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Ngày Sinh:</strong> {{ $student->dob ? $student->dob->format('d/m/Y') : 'Chưa có thông tin' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Giới Tính:</strong> {{ $student->gender == 'male' ? 'Nam' : ($student->gender == 'female' ? 'Nữ' : ($student->gender == 'other' ? 'Khác' : 'Chưa có thông tin')) }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Số CMT:</strong> {{ $student->identity_number ?? 'Chưa có thông tin' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>ID Thẻ:</strong> {{ $student->id_card ?? 'Chưa có thông tin' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Số Điện Thoại:</strong> {{ $student->phone ?? 'Chưa có thông tin' }}</p>
                    </div>
                        <div class="col-md-3">
                    <p><strong>Người Giới Thiệu:</strong> {{ $student->convertedBy->name ?? 'Chưa có thông tin' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Nhân Viên Sale:</strong> {{ $student->saleSupport->name ?? 'Chưa có thông tin' }}</p>
                    </div>
                    @php
                        $filePath = 'storage/' . $contractImage;
                    @endphp
                    @if($contractImage)
                        <div class="col-md-3">    
                            <p><strong>Hợp Đồng:</strong> <a href="{{ asset($filePath) }}" target="_blank">Xem Hợp Đồng</a></p>
                        </div>
                    @else
                        <div class="col-md-3">    
                            <p><strong>Hợp Đồng:</strong> Chưa có ảnh hợp đồng</p>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <p><strong>Mã Khóa Học:</strong> {{ $course->code ?? 'Chưa có thông tin' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Hạng:</strong> {{ $course->ranking->name ?? 'Chưa có thông tin' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Ngày ký hợp đồng:</strong> {{ $coursePivot->pivot->contract_date ?? 'Chưa có thông tin' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Giờ:</strong> {{ $totalHours }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Km:</strong> {{ $totalKm }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Tổng học phí phải đóng:</strong> {{ $coursePivot && $coursePivot->pivot ? number_format($coursePivot->pivot->tuition_fee) . ' VNĐ' : 'Chưa có thông tin' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Tổng Học Phí Đã Đóng:</strong> {{ number_format($courseFees) }} VNĐ</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Tổng Học Phí Còn Thiếu:</strong> {{ number_format($remainingFees) }} VNĐ</p>
                    </div>
                </div> 
                <div class="col-md-10 row">
                    <div class="col-md-4">
                        <p><strong>Mã Sinh Viên:</strong> {{ $student->student_code ?? 'Chưa có thông tin' }}</p>
                        <p><strong>Họ Tên:</strong> {{ $student->name ?? 'Chưa có thông tin' }}</p>
                        <p><strong>Ngày Sinh:</strong> {{ $student->dob ? $student->dob->format('d/m/Y') : 'Chưa có thông tin' }}</p>
                        <p><strong>Giới Tính:</strong> 
                            {{ $student->gender == 'male' ? 'Nam' : ($student->gender == 'female' ? 'Nữ' : ($student->gender == 'other' ? 'Khác' : 'Chưa có thông tin')) }}
                        </p>
                        <p><strong>Số CMT:</strong> {{ $student->identity_number ?? 'Chưa có thông tin' }}</p>
                        <p><strong>ID Thẻ:</strong> {{ $student->id_card ?? 'Chưa có thông tin' }}</p>
                        <p><strong>Số Điện Thoại:</strong> {{ $student->phone ?? 'Chưa có thông tin' }}</p>
                    </div>
                
                    <div class="col-md-4 border-start ps-4">
                        <p><strong>Người Giới Thiệu:</strong> {{ $student->convertedBy->name ?? 'Chưa có thông tin' }}</p>
                        <p><strong>Nhân Viên Sale:</strong> {{ $student->saleSupport->name ?? 'Chưa có thông tin' }}</p>
                
                        @php
                            $filePath = 'storage/' . $contractImage;
                        @endphp
                        @if($contractImage)
                            <p><strong>Hợp Đồng:</strong> <a href="{{ asset($filePath) }}" target="_blank">Xem Hợp Đồng</a></p>
                        @else
                            <p><strong>Hợp Đồng:</strong> Chưa có ảnh hợp đồng</p>
                        @endif
                
                        <p><strong>Mã Khóa Học:</strong> {{ $course->code ?? 'Chưa có thông tin' }}</p>
                        <p><strong>Hạng:</strong> {{ $course->ranking->name ?? 'Chưa có thông tin' }}</p>
                        <p><strong>Ngày ký hợp đồng:</strong> {{ $coursePivot->pivot->contract_date ?? 'Chưa có thông tin' }}</p>
                        <p><strong>Giờ:</strong> {{ $totalHours }}</p>
                        <p><strong>Km:</strong> {{ $totalKm }}</p>
                    </div>
                
                    <div class="col-md-4 border-start ps-4">
                        <p><strong>Tổng học phí phải đóng:</strong> 
                            {{ $coursePivot && $coursePivot->pivot ? number_format($coursePivot->pivot->tuition_fee) . ' VNĐ' : 'Chưa có thông tin' }}
                        </p>
                        <p><strong>Tổng học phí đã đóng:</strong> {{ number_format($courseFees) }} VNĐ</p>
                        <p><strong>Tổng học phí còn thiếu:</strong> {{ number_format($remainingFees) }} VNĐ</p>
                    </div>
                </div>
                
            </div>
        </div>
    </div> --}}

    @include('admin.students.common-calendar')
@endsection
@section('js')
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

    <script src="{{ asset('assets/js/add-calendar-modal.js') }}"></script>
    <script src="{{ asset('assets/js/add-calendar-exam-modal.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const course = {!! json_encode($course) !!};
            const calendarTh = $('.calendar-th');
            const calendarCb = $('.calendar-cb');
            const calendarLt = $('.calendar-lt');
            const calendarExamTh = $('.calendar-exam-th');
            const calendarTn = $('.calendar-tn');
            const calendarSh = $('.calendar-sh');

            calendarTh.show();
            calendarLt.show();
            calendarSh.show();
            if (course?.ranking?.vehicle_type !== 0) {
                calendarCb.show();
                calendarExamTh.show();
                calendarTn.show();
            }

            const calendarButton = document.querySelectorAll('[data-bs-target="#addActivitieModal"]');

            calendarButton.forEach(button => {
                button.addEventListener('click', function () {
                    const calendarType = button.getAttribute('data-calendar-type');

                    // Tùy loại mà gọi hàm phù hợp (giả sử đã có hàm init)
                    switch (calendarType) {
                        case 'study_practice':
                        case 'study_theory':
                            if (typeof initStudyCalendar === 'function') {
                                initStudyCalendar(button);
                            }
                            break;
                        case 'exam_theory':
                        case 'exam_practice':
                        case 'exam_graduation':
                        case 'exam_certification':
                            if (typeof initExamCalendar === 'function') {
                                initExamCalendar(button);
                            }
                            break;
                    }
                });
            });
        });
    </script>




@endsection


