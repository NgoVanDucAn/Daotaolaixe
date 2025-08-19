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
@section('content')
    <div class="card mb-4">
        <div class="card-header">Thông Tin Sinh Viên - Khóa Học</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <img src="{{ asset('storage/'. $student->image) }}" alt="Avatar" class="img-fluid rounded-circle mb-3" title="avatar học viên">
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
                        <p><strong>ID Thế:</strong> {{ $student->id_card ?? 'Chưa có thông tin' }}</p>
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
            </div>
        </div>
    </div>

    <!-- Lịch học thực hành -->
    <div class="card mb-4">
        <div class="card-header">Lịch Học Các Môn Thực Hành
            <button type="button" class="btn btn-sm btn-primary float-end" 
                data-bs-toggle="modal" data-bs-target="#addActivitieModal" 
                data-student-id="{{ $student->id }}" 
                data-student-name="{{ $student->name }} - {{ $student->student_code }}" 
                data-course-id="{{ $course->id }}" 
                data-course-name="{{ $course->code }}"
                data-calendar-type="study"
                data-type-exam="no">
                Thêm Mới
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-sm-2 col-md-1">
                    <p class="text-primary">
                        <strong>Tổng: </strong>
                    </p>
                </div>
                <div class="col-12 col-sm-10 col-md-11 row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <p class="text-primary">
                            <strong>Giờ học: </strong>
                            {{ $totalHoursAlls }}
                        </p>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <p class="text-primary">
                            <strong>Km: </strong>
                            {{ $totalKmAlls }}
                        </p>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <p class="text-primary">
                            <strong>Giờ tự động: </strong>
                            {{ $totalAutoHoursAlls }}
                        </p>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <p class="text-primary">
                            <strong>Giờ đêm: </strong>
                            {{ $totalNightHoursAlls }}
                        </p>
                    </div>
                </div>
                <div class="col-12 col-sm-2 col-md-1">
                    <p class="text-primary">
                        <strong>Đã duyệt: </strong>
                    </p>
                </div>
                <div class="col-12 col-sm-10 col-md-11 row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <p class="text-primary">
                            <strong>Giờ học: </strong>
                            {{ $totalHours }}
                        </p>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <p class="text-primary">
                            <strong>Km: </strong>
                            {{ $totalKm }}
                        </p>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <p class="text-primary">
                            <strong>Giờ tự động: </strong>
                            {{ $totalAutoHours }}
                        </p>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <p class="text-primary">
                            <strong>Giờ đêm: </strong>
                            {{ $totalNightHours }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Ngày</th>
                            <th>Thời gian</th>
                            <th>Giáo Viên</th>
                            <th>Môn Học</th>
                            <th>Sân học</th>
                            <th>Km</th>
                            <th>Giờ</th>
                            <th>Giờ tự động</th>
                            <th>Giờ đêm</th>
                            <th>Nhận xét</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calendars->where('type', 'study')->filter(fn($calendar) => $calendar->fields->contains(fn($field) => $field->is_practical)) as $index => $calendar)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $calendar->date_start ? $calendar->date_start->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ ($calendar->date_start && $calendar->date_end) ? $calendar->date_start->format('H:i') . " - " . $calendar->date_end->format('H:i') : 'N/A' }}</td>
                                <td>{{ $calendar->instructor?->name ?? 'Không có giáo viên' }}</td>
                                <td>
                                    @forelse ($calendar->fields as $field)
                                        {{ $field->name }}
                                    @empty
                                        Không có môn
                                    @endforelse
                                </td>
                                <td>
                                    @if (!empty($calendar->stadium_location))
                                        <a href="{{ $calendar->stadium_google_maps_url }}" target="_blank">{{ $calendar->stadium_location ?? 'N/A' }}</a>
                                    @else
                                        Không có sân tập
                                    @endif
                                </td>
                                <td>{{ $calendar->pivot->km ?? 'N/A' }}</td>
                                <td>{{ $calendar->pivot->hours ?? 'N/A' }}</td>
                                <td>{{ $calendar->pivot->auto_hours ?? 'N/A' }}</td>
                                <td>{{ $calendar->pivot->night_hours ?? 'N/A' }}</td>
                                <td>{{ $calendar->pivot->remarks}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">Không có lịch học</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lịch học lý thuyết -->
    <div class="card mb-4">
        <div class="card-header">Lịch Học Các Môn Lý Thuyết
            <button type="button" class="btn btn-sm btn-primary float-end" 
                data-bs-toggle="modal" data-bs-target="#addActivitieModal" 
                data-student-id="{{ $student->id }}" 
                data-student-name="{{ $student->name }} - {{ $student->student_code }}" 
                data-course-id="{{ $course->id }}" 
                data-course-name="{{ $course->code }}"
                data-calendar-type="study"
                data-type-exam="no">
                Thêm Mới
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Ngày</th>
                            <th>Thời gian</th>
                            <th>Giáo Viên</th>
                            <th>Môn Học</th>
                            <th>Sân học</th>
                            <th>Km</th>
                            <th>Giờ</th>
                            <th>Giờ tự động</th>
                            <th>Giờ đêm</th>
                            <th>Nhận xét</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calendars->where('type', 'study')->filter(fn($calendar) => $calendar->fields->contains(fn($field) => !$field->is_practical)) as $index => $calendar)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $calendar->date_start ? $calendar->date_start->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ ($calendar->date_start && $calendar->date_end) ? $calendar->date_start->format('H:i') . " - " . $calendar->date_end->format('H:i') : 'N/A' }}</td>
                                <td>{{ $calendar->instructor?->name ?? 'Không có giáo viên' }}</td>
                                <td>
                                    @forelse ($calendar->fields as $field)
                                        {{ $field->name }}
                                    @empty
                                        Không có môn
                                    @endforelse
                                </td>
                                <td>
                                    @if (!empty($calendar->stadium_location))
                                        <a href="{{ $calendar->stadium_google_maps_url }}" target="_blank">{{ $calendar->stadium_location ?? 'N/A' }}</a>
                                    @else
                                        Không có sân tập
                                    @endif
                                </td>
                                <td>{{ $calendar->pivot->km ?? 'N/A' }}</td>
                                <td>{{ $calendar->pivot->hours ?? 'N/A' }}</td>
                                <td>{{ $calendar->pivot->auto_hours ?? 'N/A' }}</td>
                                <td>{{ $calendar->pivot->night_hours ?? 'N/A' }}</td>
                                <td>{{ $calendar->pivot->remarks}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">Không có lịch học</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal thêm mới lịch -->
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
                                            <input type="text" placeholder="dd/mm/yyyy"name="date" id="date" class="form-control real-date @error('date') is-invalid @enderror" />
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
                                        <input type="text" placeholder="dd/mm/yyyy"name="exam_fee_deadline" id="exam_fee_deadline" class="form-control real-date @error('exam_fee_deadline') is-invalid @enderror"/>
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

    <!-- Lịch thi hết môn lý thuyết -->
    <div class="card mb-4">
        <div class="card-header">Lịch Thi Hết Môn Lý Thuyết
            <button type="button" class="btn btn-sm btn-primary float-end" 
                data-bs-toggle="modal" data-bs-target="#addActivitieModal" 
                data-student-id="{{ $student->id }}" 
                data-student-name="{{ $student->name }} - {{ $student->student_code }}" 
                data-course-id="{{ $course->id }}" 
                data-course-name="{{ $course->code }}"
                data-calendar-type="exam"
                data-type-exam="1">
                Thêm Mới
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Ngày Thi</th>
                            <th>Buổi Thi</th>
                            <th>Địa Điểm</th>
                            <th>Kết Quả Tổng</th>
                            <th>Môn Thi</th>
                            <th>Lần Thi</th>
                            <th>Kết Quả</th>
                            <th>Nhận xét</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calendars->where('type', 'exam')->where('level', 6) as $index => $calendar)
                            @foreach ($calendar->fields as $fieldIndex => $field)
                                <tr>
                                    @if ($fieldIndex === 0)
                                        <td rowspan="{{ $calendar->fields->count() }}">{{ $loop->iteration }}</td>
                                        <td rowspan="{{ $calendar->fields->count() }}">{{ $calendar->date ? $calendar->date->format('d/m/Y') : $calendar->date_start->format('d/m/Y') }}</td>
                                        <td rowspan="{{ $calendar->fields->count() }}">{{ $calendar->time ?? 'N/A' }}</td>
                                        <td rowspan="{{ $calendar->fields->count() }}">
                                            @if (!empty($calendar->stadium_location))
                                                <a href="{{ $calendar->stadium_google_maps_url }}" target="_blank">{{ $calendar->stadium_location ?? 'N/A' }}</a>
                                            @else
                                                Không có sân tập
                                            @endif
                                        </td>
                                        <td rowspan="{{ $calendar->fields->count() }}">
                                            {{ $field->exam_all_status === 1 ? 'Đỗ' : ($field->exam_all_status === 2 ? 'Trượt' : 'Chưa thi') }}
                                        </td>
                                    @endif
                                    
                                    <td>{{ $field->name ?? 'N/A' }}</td>
                                    <td>{{ $field->attempt_number ?? 'N/A' }}</td>
                                    <td>{{ $field->exam_status === 1 ? 'Đỗ' : ($field->exam_status === 2 ? 'Trượt' : 'Chưa thi') }}</td>
                                    <td>{{ $field->remarks ?? '' }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Không có lịch thi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lịch thi hết môn thực hành -->
    <div class="card mb-4">
        <div class="card-header">Lịch Thi Hết Môn Thực Hành
            <button type="button" class="btn btn-sm btn-primary float-end" 
                data-bs-toggle="modal" data-bs-target="#addActivitieModal" 
                data-student-id="{{ $student->id }}" 
                data-student-name="{{ $student->name }} - {{ $student->student_code }}" 
                data-course-id="{{ $course->id }}" 
                data-course-name="{{ $course->code }}"
                data-calendar-type="exam"
                data-type-exam="1">
                Thêm Mới
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Ngày Thi</th>
                            <th>Buổi Thi</th>
                            <th>Địa Điểm</th>
                            <th>Kết Quả Tổng</th>
                            <th>Môn Thi</th>
                            <th>Lần Thi</th>
                            <th>Kết Quả</th>
                            <th>Nhận xét</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calendars->where('type', 'exam')->where('level', 5) as $index => $calendar)
                            @foreach ($calendar->fields as $fieldIndex => $field)
                                <tr>
                                    @if ($fieldIndex === 0)
                                        <td rowspan="{{ $calendar->fields->count() }}">{{ $loop->iteration }}</td>
                                        <td rowspan="{{ $calendar->fields->count() }}">{{ $calendar->date ? $calendar->date->format('d/m/Y') : $calendar->date_start->format('d/m/Y') }}</td>
                                        <td rowspan="{{ $calendar->fields->count() }}">{{ $calendar->time ?? 'N/A' }}</td>
                                        <td rowspan="{{ $calendar->fields->count() }}">
                                            @if (!empty($calendar->stadium_location))
                                                <a href="{{ $calendar->stadium_google_maps_url }}" target="_blank">{{ $calendar->stadium_location ?? 'N/A' }}</a>
                                            @else
                                                Không có sân tập
                                            @endif
                                        </td>
                                        <td rowspan="{{ $calendar->fields->count() }}">
                                            {{ $field->exam_all_status === 1 ? 'Đỗ' : ($field->exam_all_status === 2 ? 'Trượt' : 'Chưa thi') }}
                                        </td>
                                    @endif
                                    
                                    <td>{{ $field->name ?? 'N/A' }}</td>
                                    <td>{{ $field->attempt_number ?? 'N/A' }}</td>
                                    <td>{{ $field->exam_status === 1 ? 'Đỗ' : ($field->exam_status === 2 ? 'Trượt' : 'Chưa thi') }}</td>
                                    <td>{{ $field->remarks ?? '' }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Không có lịch thi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lịch thi tốt nghiệp -->
    <div class="card mb-4">
        <div class="card-header">Lịch Thi Tốt Nghiệp
            <button type="button" class="btn btn-sm btn-primary float-end" 
                data-bs-toggle="modal" data-bs-target="#addActivitieModal" 
                data-student-id="{{ $student->id }}" 
                data-student-name="{{ $student->name }} - {{ $student->student_code }}" 
                data-course-id="{{ $course->id }}" 
                data-course-name="{{ $course->code }}"
                data-calendar-type="exam"
                data-type-exam="1">
                Thêm Mới
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Ngày Thi</th>
                            <th>Buổi Thi</th>
                            <th>Địa Điểm</th>
                            <th>Kết Quả Tổng</th>
                            <th>Môn Thi</th>
                            <th>Lần Thi</th>
                            <th>Kết Quả</th>
                            <th>Nhận xét</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calendars->where('type', 'exam')->where('level', 1) as $index => $calendar)
                            @foreach ($calendar->fields as $fieldIndex => $field)
                                <tr>
                                    @if ($fieldIndex === 0)
                                        <td rowspan="{{ $calendar->fields->count() }}">{{ $loop->iteration }}</td>
                                        <td rowspan="{{ $calendar->fields->count() }}">{{ $calendar->date ? $calendar->date->format('d/m/Y') : $calendar->date_start->format('d/m/Y') }}</td>
                                        <td rowspan="{{ $calendar->fields->count() }}">{{ $calendar->time ?? 'N/A' }}</td>
                                        <td rowspan="{{ $calendar->fields->count() }}">
                                            @if (!empty($calendar->stadium_location))
                                                <a href="{{ $calendar->stadium_google_maps_url }}" target="_blank">{{ $calendar->stadium_location ?? 'N/A' }}</a>
                                            @else
                                                Không có sân tập
                                            @endif
                                        </td>
                                        <td rowspan="{{ $calendar->fields->count() }}">
                                            {{ $field->exam_all_status === 1 ? 'Đỗ' : ($field->exam_all_status === 2 ? 'Trượt' : 'Chưa thi') }}
                                        </td>
                                    @endif
                                    
                                    <td>{{ $field->name ?? 'N/A' }}</td>
                                    <td>{{ $field->attempt_number ?? 'N/A' }}</td>
                                    <td>{{ $field->exam_status === 1 ? 'Đỗ' : ($field->exam_status === 2 ? 'Trượt' : 'Chưa thi') }}</td>
                                    <td>{{ $field->remarks ?? '' }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Không có lịch thi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lịch thi sát hạch -->
    <div class="card mb-4">
        <div class="card-header">Lịch Thi Sát Hạch
            <button type="button" class="btn btn-sm btn-primary float-end" 
                data-bs-toggle="modal" data-bs-target="#addActivitieModal" 
                data-student-id="{{ $student->id }}" 
                data-student-name="{{ $student->name }} - {{ $student->student_code }}" 
                data-course-id="{{ $course->id }}" 
                data-course-name="{{ $course->code }}"
                data-calendar-type="exam"
                data-type-exam="2">
                Thêm Mới
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Ngày Thi</th>
                            <th>Buổi Thi</th>
                            <th>Địa Điểm</th>
                            <th>Kết Quả Tổng</th>
                            <th>Môn Thi</th>
                            <th>Lần Thi</th>
                            <th>Kết Quả</th>
                            <th>Nhận xét</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calendars->where('type', 'exam')->where('level', 2) as $index => $calendar)
                            @foreach ($calendar->fields as $fieldIndex => $field)
                                <tr>
                                    @if ($fieldIndex === 0)
                                        <td rowspan="{{ $calendar->fields->count() }}">{{ $loop->iteration }}</td>
                                        <td rowspan="{{ $calendar->fields->count() }}">{{ $calendar->date ? $calendar->date->format('d/m/Y') : $calendar->date_start->format('d/m/Y') }}</td>
                                        <td rowspan="{{ $calendar->fields->count() }}">{{ $calendar->time ?? 'N/A' }}</td>
                                        <td rowspan="{{ $calendar->fields->count() }}">
                                            @if (!empty($calendar->stadium_location))
                                                <a href="{{ $calendar->stadium_google_maps_url }}" target="_blank">{{ $calendar->stadium_location ?? 'N/A' }}</a>
                                            @else
                                                Không có sân tập
                                            @endif
                                        </td>
                                        <td rowspan="{{ $calendar->fields->count() }}">
                                            {{ $field->exam_all_status === 1 ? 'Đỗ' : ($field->exam_all_status === 2 ? 'Trượt' : 'Chưa thi') }}
                                        </td>
                                    @endif
                                    
                                    <td>{{ $field->name ?? 'N/A' }}</td>
                                    <td>{{ $field->attempt_number ?? 'N/A' }}</td>
                                    <td>{{ $field->exam_status === 1 ? 'Đỗ' : ($field->exam_status === 2 ? 'Trượt' : 'Chưa thi') }}</td>
                                    <td>{{ $field->remarks ?? '' }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Không có lịch thi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lịch sử nộp học phí -->
    <div class="card mb-4">
        <div class="card-header">Lịch Sử Nộp Học Phí
            <button type="button" class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addFeeModal" 
                    data-student-id="{{ $student->id }}" data-student-name="{{ $student->name }} - {{ $student->student_code }}" 
                    data-course-student-id="{{ $coursePivot->pivot->id }}" data-course-code="{{ $course->code }}">
                Thêm mới
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                    <p class="text-primary">
                        <strong>Tổng học phí phải đóng:</strong>
                        {{ $coursePivot && $coursePivot->pivot ? number_format($coursePivot->pivot->tuition_fee) . ' VNĐ' : 'Chưa có thông tin' }}
                    </p>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <p class="text-success">
                        Tổng Tiền Đã Nộp: {{ number_format($courseFees) ?? 0 }} VNĐ
                    </p>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <p class="text-danger">
                        Tổng Nợ: {{ number_format($remainingFees) ?? 0 }} VNĐ
                    </p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Số Tiền Nộp</th>
                            <th>Ngày Nộp</th>
                            <th>Người Thu</th>
                            <th>Ghi Chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->fees->where('course_id', $course->id) as $index => $fee)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ number_format($fee->amount) }} VNĐ</td>
                                <td>{{ $fee->payment_date ? $fee->payment_date->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $fee->is_received ? $users->find($fee->is_received)->name : 'N/A' }}</td>
                                <td>{{ $fee->note }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Không có lịch sử nộp học phí</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal thêm mới học phí -->
    <div class="modal fade" id="addFeeModal" tabindex="-1" aria-labelledby="addFeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFeeModalLabel">Thêm Thanh Toán Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('fees.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="modal_name" value="addFeeModal">
                        <div class="mb-3 position-relative">
                            <label for="payment_date" class="form-label">Ngày Thanh Toán</label>
                            <input type="text" placeholder="dd/mm/yyyy"class="form-control real-date" autocomplete="off" name="payment_date" id="payment_date" value="{{ old('payment_date') }}" required>
                            @error('payment_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="mb-3">
                            <label for="amount" class="form-label">Số Tiền</label>
                            <input type="text" class="form-control currency-inpu" name="amount" id="amount" value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <!-- Trường ẩn cho student_id -->
                        <input type="hidden" name="student_id" id="student_id">
                        <div class="mb-3">
                            <label for="student_display" class="form-label">Học Viên</label>
                            <input type="text" class="form-control" id="student_display" readonly>
                        </div>
    
                        <!-- Trường ẩn cho course_student_id -->
                        <input type="hidden" name="course_student_id" id="course_student_id">
                        <div class="mb-3">
                            <label for="course_display" class="form-label">Khóa Học</label>
                            <input type="text" class="form-control" id="course_display" readonly>
                        </div>
    
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_received" id="is_received" value="1" @if(old('is_received') == 1) checked @endif>
                            <label class="form-check-label" for="is_received">Đã nhận</label>
                            @error('is_received')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi Chú</label>
                            <textarea class="form-control" name="note" id="note" rows="3">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Lưu</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Kết quả thi -->
    <div class="card mb-4">
        <div class="card-header">Kết Quả Thi</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Môn thi</th>
                            <th>Số lần thi</th>
                            <th>Kết Quả</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examsResults as $index => $examResult)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $examResult->examField?->name ?? 'N/A' }}</td>
                                <td>{{ $examResult->attempt_number ?? 'N/A' }}</td>
                                <td>
                                    @if ($examResult->status == 1)
                                        <span class="text-success">Hoàn thành</span>
                                    @elseif ($examResult->status == 2)
                                        <span class="text-danger">Đã bỏ</span>
                                    @else
                                        <span class="text-danger">Chưa hoàn thành</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Không có kết quả thi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addFeeModal = document.getElementById('addFeeModal');
            addFeeModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Nút kích hoạt modal
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        let defaultStudentId = '';
        let defaultStudentName = '';
        let defaultCourseId = '';
        let defaultCourseName = '';

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
            var button = $(event.relatedTarget);
            defaultStudentId = button.data('student-id');
            defaultStudentName = button.data('student-name') || `Học viên ID ${defaultStudentId}`;
            defaultCourseId = button.data('course-id');
            defaultCourseName = button.data('course-name') || `Khóa học ID ${defaultCourseId}`;
            var calendarType = button.data('calendar-type'); // Lấy calendar-type
            var typeExam = button.data('type-exam'); // Lấy type-exam

            // Chọn radio button và cập nhật form dựa trên calendar-type
            if (calendarType === 'exam') {
                $('input[name="type"][value="exam"]').prop('checked', true).trigger('change');
                updateForm('exam');

                if (typeExam == '1') {
                    $('#exam_course_type').val(1).trigger('change');
                } else if (typeExam == '2') {
                    $('#exam_course_type').val(2).trigger('change');
                }
            } else {
                $('input[name="type"][value="study"]').prop('checked', true).trigger('change');
                updateForm('study');
            }

            // Thêm học viên mặc định vào select
            var selectLearn = modal.find('#learn_student_id_select');
            var selectExam = modal.find('#exam_student_id_select');
            selectLearn.empty();
            selectExam.empty();
            selectLearn.append(new Option(`${defaultStudentName}`, defaultStudentId, true, true));
            selectExam.append(new Option(`${defaultStudentName}`, defaultStudentId, true, true));

            // Thêm khóa học mặc định vào select
            var learnSelect = $('#learn_course_id');
            var examSelect = $('#exam_course_id');
            learnSelect.empty().append(new Option(defaultCourseName, defaultCourseId, true, true));
            examSelect.empty().append(new Option(defaultCourseName, defaultCourseId, true, true));
            learnSelect.prop('disabled', true);
            examSelect.prop('disabled', true);

            if (calendarType === 'study') {
                const startTime = startTimeInput.value;
                const endTime = endTimeInput.value;
                fetchAndUpdate(defaultCourseId, 'study', startTime, endTime);
            } else if (calendarType === 'exam') {
                const date = dateInput.value;
                const time = timeInput.value;
                fetchAndUpdate(defaultCourseId, 'exam', date, time);
            }
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
