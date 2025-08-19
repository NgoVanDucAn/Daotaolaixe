@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('calendars.update', $calendar->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="row">
                        <!-- Loại Lịch -->
                        <div class="form-group mb-3">
                            <label class="form-label">
                                Loại Lịch: 
                                @switch($calendar->type)
                                    @case('study') Học @break
                                    @case('exam') Thi @break
                                    @case('work') Công Việc @break
                                    @case('meeting') Họp @break
                                    @case('call') Gọi @break
                                    @default Không xác định
                                @endswitch
                            </label>
                                <input type="hidden" name="type" id="calendar-type" value="{{ $calendar->type }}">
                            @error('type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tên Lịch -->
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Tên Lịch</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $calendar->name) }}" required />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mức Độ Ưu Tiên -->
                        <div class="form-group mb-3 col-12 col-md-6">
                            <label for="priority" class="form-label">Mức Độ Ưu Tiên</label>
                            <select name="priority" id="priority" class="form-control @error('priority') is-invalid @enderror" required>
                                <option value="Low" {{ $calendar->priority == 'Low' ? 'selected' : '' }}>Thấp</option>
                                <option value="Normal" {{ $calendar->priority == 'Normal' ? 'selected' : '' }}>Bình Thường</option>
                                <option value="High" {{ $calendar->priority == 'High' ? 'selected' : '' }}>Cao</option>
                                <option value="Urgent" {{ $calendar->priority == 'Urgent' ? 'selected' : '' }}>Khẩn Cấp</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Địa Điểm -->
                        <div class="form-group mb-3 col-12 col-md-6">
                            <label for="location" class="form-label">Địa Điểm:</label>
                            <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $calendar->location) }}"/>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ngày Bắt Đầu -->
                        <div class="form-group mb-3 col-12 col-md-6" id="date-start-field">
                            <label for="date_start" class="form-label">Ngày Bắt Đầu</label>
                            <input type="datetime-local" name="date_start" id="date_start" class="form-control @error('date_start') is-invalid @enderror" value="{{ old('date_start', $calendar->date_start->format('Y-m-d\TH:i')) }}" required />
                            @error('date_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ngày Kết Thúc -->
                        <div class="form-group mb-3 col-12 col-md-6" id="date-end-field">
                            <label for="date_end" class="form-label">Ngày Kết Thúc</label>
                            <input type="datetime-local" name="date_end" id="date_end" class="form-control @error('date_end') is-invalid @enderror" value="{{ old('date_end', $calendar->date_end->format('Y-m-d\TH:i')) }}" required />
                            @error('date_end')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ngày và Buổi Thi (cho lịch thi) -->
                        <div class="form-group mb-3 col-12 col-md-6" id="exam-date-time" style="display: {{ $calendar->type == 'exam' ? 'block' : 'none' }};">
                            <div class="mb-3 position-relative">
                                <label for="date" class="form-label">Ngày</label>
                                <input 
                                    type="text" 
                                    placeholder="dd/mm/yyyy"
                                    name="date" id="date" 
                                    class="form-control real-date 
                                    @error('date') is-invalid @enderror" 
                                    autocomplete="off"
                                    value="{{ old('date', ($calendar->date) ? $calendar->date->format('Y-m-d') : '') }}" />
                                {{-- <div class="datepicker-button" style="position: absolute; right: 20px; top: 38px; cursor: pointer;">📅</div> --}}
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="time" class="form-label">Buổi thi</label>
                                <select name="time" id="time" class="form-control @error('time') is-invalid @enderror">
                                    <option value="">-- Chọn buổi thi --</option>
                                    <option value="1" {{ old('time', $calendar->time) == '1' ? 'selected' : '' }}>Buổi sáng</option>
                                    <option value="2" {{ old('time', $calendar->time) == '2' ? 'selected' : '' }}>Buổi chiều</option>
                                    <option value="3" {{ old('time', $calendar->time) == '3' ? 'selected' : '' }}>Cả ngày</option>
                                </select>
                                @error('time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Mô Tả -->
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Mô Tả</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $calendar->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <!-- Trường riêng cho lịch học -->
                    <div id="study-fields" class="mb-3" style="display: {{ $calendar->type == 'study' ? 'block' : 'none' }};">
                        <label for="learning_id" class="form-label mt-2">Môn học</label>
                        <select name="learning_id" id="learning_id" class="form-control @error('learning_id') is-invalid @enderror">
                            <option value="">-- Vui lòng chọn khóa học trước --</option>
                            @if($calendar->type == 'study')
                                @foreach($learningFields as $field)
                                    <option value="{{ $field->id }}" {{ (string)$calendar->learning_field_id === (string)$field->id ? 'selected' : '' }}>{{ $field->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('learning_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="learn_teacher_id" class="form-label mt-2">Giáo viên</label>
                        <select name="learn_teacher_id" id="learn_teacher_id" class="form-control @error('learn_teacher_id') is-invalid @enderror">
                            <option value="">-- Vui lòng chọn thời gian trước --</option>
                            @if($calendar->type == 'study')
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $calendar->users->where('pivot.role', 2)->contains($teacher->id) ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('learn_teacher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="learn_student_id" class="form-label mt-2">Học viên khóa học</label>
                        <select name="learn_student_id[]" id="learn_student_id" class="w-full form-control @error('learn_student_id') is-invalid @enderror" multiple>
                            <option value="">-- Vui lòng chọn khóa học, thời gian của lịch trước --</option>
                            @if($calendar->type == 'study')
                                @foreach($courseStudentAlls as $courseStudent)
                                    <option value="{{ $courseStudent->id }}" 
                                        {{ optional($calendar->calendarStudents)->contains(function ($calendarStudent) use ($courseStudent) { 
                                            return $calendarStudent->courseStudent?->id === $courseStudent->id; 
                                        }) ? 'selected' : '' }}>
                                        {{ $courseStudent->student->name }} - {{ $courseStudent->course->code }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('learn_student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="mb-3">
                            <label for="stadium_id" class="form-label">Sân tập</label>
                            <select name="stadium_id" id="stadium_id" class="form-select">
                                <option value="">Chọn sân</option>
                                @foreach ($stadiums as $stadium)
                                    <option value="{{ $stadium->id }}" {{ old('stadium_id', $calendar->stadium_id) == $stadium->id ? 'selected' : '' }}>{{ $stadium->location }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="vehicle_select" class="form-label">Xe Học</label>
                            <select name="vehicle_select" id="vehicle_select" class="form-select">
                                <option value="">-- Vui lòng chọn thời gian trước --</option>
                                @if($calendar->type == 'study' && $calendar->vehicle)
                                    <option value="{{ $calendar->vehicle->id }}" selected>{{ $calendar->vehicle->license_plate }} - {{ $calendar->vehicle->model }}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- Trường riêng cho lịch thi -->
                    <div id="exam-fields" class="mb-3" style="display: {{ $calendar->type == 'exam' ? 'block' : 'none' }};">
                        <div class="form-group mb-3">
                            <label for="exam_course_type" class="form-label">Kỳ thi</label>
                            <select name="exam_course_type" id="exam_course_type" class="form-control @error('exam_course_type') is-invalid @enderror">
                                <option value="">Chọn kỳ thi</option>
                                <option value="1" {{ $calendar->level == 1  ? 'selected' : '' }}>Thi tốt nghiệp</option>
                                <option value="2" {{ $calendar->level == 2  ? 'selected' : '' }}>Thi sát hạch</option>
                            </select>
                            @error('exam_course_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="exam_id" class="form-label">Môn thi</label>
                            <select name="exam_id[]" id="exam_id" class="form-control @error('exam_id') is-invalid @enderror" multiple>
                                <option value="">-- Vui lòng chọn khóa học trước --</option>
                                @if($calendar->type == 'exam')
                                    @foreach($examFields as $field)
                                        
                                        <option value="{{ $field->id }}" {{ in_array($field->id, $examFieldOfCalendar) ? 'selected' : '' }}>{{ $field->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('exam_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="exam_teacher_id" class="form-label">Giáo viên</label>
                            <select name="exam_teacher_id" id="exam_teacher_id" class="form-control @error('exam_teacher_id') is-invalid @enderror">
                                <option value="">-- Vui lòng chọn thời gian trước --</option>
                                @if($calendar->type == 'exam')
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ $calendar->users->where('pivot.role', 2)->contains($teacher->id) ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('exam_teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="exam_student_id" class="form-label">Học viên khóa học</label>
                            <select name="exam_student_id[]" id="exam_student_id" class="form-control @error('exam_student_id') is-invalid @enderror" multiple>
                                <option value="">-- Vui lòng chọn khóa học, thời gian của lịch trước --</option>
                                @if($calendar->type == 'exam')
                                    @foreach($courseStudentAlls as $courseStudent)
                                    <option value="{{ $courseStudent->id }}" 
                                        {{ optional($calendar->calendarStudents)->contains(function ($calendarStudent) use ($courseStudent) { 
                                            return $calendarStudent->courseStudent?->id === $courseStudent->id; 
                                        }) ? 'selected' : '' }}>
                                        {{ $courseStudent->student->name }} - {{ $courseStudent->course->code }}
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('exam_student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="student-inputs" class="mb-3 space-y-4">
                            @if($calendar->type == 'exam')
                                @foreach($calendar->calendarStudents as $calendarStudent)
                                    <div class="student-input-group border p-4 rounded bg-gray-50" data-student-id="{{ $calendarStudent->courseStudent->student->id }}">
                                        <div class="mb-2 font-semibold">{{ $calendarStudent->courseStudent->student->name }} - {{ $calendarStudent->courseStudent->course->code }}</div>
                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Số báo danh:</label>
                                            <input type="text" name="students[{{ $calendarStudent->courseStudent->id }}][exam_number]" class="w-full rounded border-gray-300" value="{{ old("students.{$calendarStudent->courseStudent?->id}.exam_number", $calendarStudent->exam_number) }}">
                                        </div>
                                        
                                        <div> 
                                            <label class="block text-sm mb-1">
                                                <input type="checkbox" name="students[{{ $calendarStudent->courseStudent->id }}][pickup]" value="1" class="mr-1" {{ old("students.{$calendarStudent->courseStudent?->id}.pickup", $calendarStudent->pickup ?? false) ? 'checked' : '' }}>
                                                Đăng ký đưa đón
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="exam_schedule_id" class="form-label">Sân thi</label>
                            <select name="exam_schedule_id" id="exam_schedule_id" class="form-select">
                                <option value="">-- Vui lòng chọn thời gian, khóa học và môn học trước --</option>
                                @if($calendar->type == 'exam' && $calendar->examSchedule)
                                    <option value="{{ $calendar->examSchedule->id }}" selected>{{ $calendar->examSchedule->stadium->location }} ({{ $calendar->examSchedule->start_time }} → {{ $calendar->examSchedule->end_time }})</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- Trường riêng cho lịch công việc -->
                    <div id="work-fields" class="mb-3" style="display: {{ $calendar->type == 'work' ? 'block' : 'none' }};">
                        <label for="work_assigned_to" class="form-label mt-2">Người phụ trách</label>
                        <select name="work_assigned_to" id="work_assigned_to" class="form-control @error('work_assigned_to') is-invalid @enderror">
                            <option value="">Chọn người phụ trách</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $calendar->users->where('pivot.role', 1)->contains($user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('work_assigned_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="work_support" class="form-label mt-2">Người hỗ trợ</label>
                        <select name="work_support" id="work_support" class="form-control @error('work_support') is-invalid @enderror">
                            <option value="">Chọn người hỗ trợ</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $calendar->users->where('pivot.role', 4)->contains($user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('work_support')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Trường riêng cho lịch họp -->
                    <div id="meeting-fields" class="mb-3" style="display: {{ $calendar->type == 'meeting' ? 'block' : 'none' }};">
                        <label for="meeting_assigned_to" class="form-label mt-2">Người phụ trách</label>
                        <select name="meeting_assigned_to" id="meeting_assigned_to" class="form-control @error('meeting_assigned_to') is-invalid @enderror">
                            <option value="">Chọn người phụ trách</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $calendar->users->where('pivot.role', 1)->contains($user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('meeting_assigned_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="meeting_support" class="form-label mt-2">Người sắp xếp</label>
                        <select name="meeting_support" id="meeting_support" class="form-control @error('meeting_support') is-invalid @enderror">
                            <option value="">Chọn người sắp xếp</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $calendar->users->where('pivot.role', 4)->contains($user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('meeting_support')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="user_participants" class="form-label mt-2">Nhân viên tham gia</label>
                        <select name="user_participants" id="user_participants" class="form-control @error('user_participants') is-invalid @enderror" multiple>
                            <option value="">Chọn nhân viên tham gia</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $calendar->users->where('pivot.role', 5)->contains($user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_participants')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="student_participants" class="form-label mt-2">Học viên tham gia</label>
                        <select name="student_participants[]" id="student_participants" class="form-control @error('student_participants') is-invalid @enderror" multiple>
                            <option value="">Chọn học viên tham gia</option>
                            @foreach($studentsAll as $student)
                                {{ $calendar->calendarStudents->contains(fn($cs) => $cs->courseStudent?->student?->id === $student->id) ? 'selected' : '' }}>{{ $student->name }} - {{ $student->student_code }}</option>
                            @endforeach
                        </select>
                        @error('student_participants')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Trường riêng cho lịch gọi -->
                    <div id="call-fields" class="mb-3" style="display: {{ $calendar->type == 'call' ? 'block' : 'none' }};">
                        <label for="call_sale_id" class="form-label mt-2">Người thực hiện</label>
                        <select name="call_sale_id" id="call_sale_id" class="form-control @error('call_sale_id') is-invalid @enderror">
                            <option value="">Chọn người thực hiện</option>
                            @foreach($salespersons as $salesperson)
                                <option value="{{ $salesperson->id }}" {{ $calendar->users->where('pivot.role', 3)->contains($salesperson->id) ? 'selected' : '' }}>{{ $salesperson->name }}</option>
                            @endforeach
                        </select>
                        @error('call_sale_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="call_student_id" class="form-label mt-2">Học viên</label>
                        <select name="call_student_id" id="call_student_id" class="form-control @error('call_student_id') is-invalid @enderror" multiple>
                            <option value="">Chọn học viên</option>
                            @foreach($studentsAll as $student)
                                {{ $calendar->calendarStudents->contains(fn($cs) => $cs->courseStudent?->student?->id === $student->id) ? 'selected' : '' }}>{{ $student->name }} - {{ $student->student_code }}</option>
                            @endforeach
                        </select>
                        @error('call_student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Nút Submit -->
                <button type="submit" class="btn btn-primary mt-3">Cập Nhật Lịch</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const radios = document.getElementById('calendar-type');
            const startTimeInput = document.getElementById('date_start');
            const endTimeInput = document.getElementById('date_end');
            const dateInput = document.getElementById('date');
            const timeInput = document.getElementById('time');
            const examCourseSelect = document.getElementById('exam_course_id');
            const dateStartField = document.getElementById('date-start-field');
            const dateEndField = document.getElementById('date-end-field');
            const examDateTimeField = document.getElementById('exam-date-time');

            function updateForm(selectedType) {
                document.querySelectorAll('#study-fields, #exam-fields, #work-fields, #meeting-fields, #call-fields').forEach(function(field) {
                    field.style.display = 'none';
                });

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
                        placeholder: "Chọn khóa học",
                        allowClear: true,
                        width: '100%'
                    }).on('change', function () {
                        const startTime = startTimeInput.value;
                        const endTime = endTimeInput.value;
                        const courseId = $(this).val();
                        fetchAndUpdate(courseId, 'study', startTime, endTime);
                    });
                    $('#learn_student_id').select2({
                        placeholder: "Chọn học viên",
                        allowClear: true,
                        width: '100%'
                    });
                    $('#stadium_id').select2({
                        placeholder: "Chọn sân tập",
                        allowClear: true,
                        width: '100%'
                    });
                    $('#vehicle_select').select2({
                        placeholder: "Chọn xe",
                        allowClear: true,
                        width: '100%'
                    });
                } else if (selectedType === 'exam') {
                    document.getElementById('exam-fields').style.display = 'block';
                    $('#exam_student_id').select2({
                        placeholder: "Chọn học viên",
                        allowClear: true
                    });
                    $('#exam_course_id').select2({
                        placeholder: "Chọn khóa học",
                        allowClear: true,
                        width: '100%'
                    }).on('change', function () {
                        const date = dateInput.value;
                        const time = timeInput.value;
                        const courseId = $(this).val();
                        fetchAndUpdate(courseId, 'exam', date, time);
                    });
                    $('#exam_id').select2({
                        placeholder: "Chọn môn thi",
                        allowClear: true,
                        width: '100%'
                    });
                    $('#exam_schedule_id').select2({
                        placeholder: "Chọn sân thi",
                        allowClear: true,
                        width: '100%'
                    });
                } else if (selectedType === 'work') {
                    document.getElementById('work-fields').style.display = 'block';
                    $('#work_assigned_to').select2({
                        placeholder: "Chọn người phụ trách",
                        allowClear: true,
                        width: '100%'
                    });
                    $('#work_support').select2({
                        placeholder: "Chọn người hỗ trợ",
                        allowClear: true,
                        width: '100%'
                    });
                } else if (selectedType === 'meeting') {
                    document.getElementById('meeting-fields').style.display = 'block';
                    $('#meeting_assigned_to').select2({
                        placeholder: "Chọn người phụ trách",
                        allowClear: true,
                        width: '100%'
                    });
                    $('#meeting_support').select2({
                        placeholder: "Chọn người sắp xếp",
                        allowClear: true,
                        width: '100%'
                    });
                    $('#user_participants').select2({
                        placeholder: "Chọn nhân viên tham gia",
                        allowClear: true,
                        width: '100%'
                    });
                    $('#student_participants').select2({
                        placeholder: "Chọn học viên tham gia",
                        allowClear: true,
                        width: '100%'
                    });
                } else if (selectedType === 'call') {
                    document.getElementById('call-fields').style.display = 'block';
                    $('#call_sale_id').select2({
                        placeholder: "Chọn người thực hiện",
                        allowClear: true,
                        width: '100%'
                    });
                    $('#call_student_id').select2({
                        placeholder: "Chọn học viên",
                        allowClear: true,
                        width: '100%'
                    });
                }
            }

            // Gán sự kiện
            if (radios) {
                updateForm(radios.value);
            }

            // Khởi tạo form với loại lịch hiện tại
            const checkedRadio = document.querySelector('input[name="type"]:checked');
            if (checkedRadio) {
                updateForm(checkedRadio.value);
            }

            // ========== Xử lý AJAX khi chọn khóa học ==========
            function fetchAndUpdate(courseId, type, dateStart, dateEnd) {
                if (!courseId) return;

                let url = `/course-data/${type}/${courseId}`;
                if (type === 'exam') {
                    url += `?date=${encodeURIComponent(dateStart)}&time=${encodeURIComponent(dateEnd)}`;
                } else {
                    url += `?date_start=${encodeURIComponent(dateStart)}&date_end=${encodeURIComponent(dateEnd)}`;
                }

                fetch(url).then(response => response.json()).then(data => {
                    const subjectSelect = document.getElementById(type === 'study' ? 'learning_id' : 'exam_id');
                    subjectSelect.innerHTML = (type === 'study')
                        ? '<option value="">Chọn môn học</option>'
                        : '<option value="">Chọn môn thi</option>';

                    const subjects = type === 'study' ? data.course.learning_fields : data.course.exam_fields;
                    subjects.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name;
                        subjectSelect.appendChild(option);
                    });

                    const studentSelect = document.getElementById(type === 'study' ? 'learn_student_id' : 'exam_student_id');
                    studentSelect.innerHTML = '';
                    if (data.option_message) {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = data.option_message;
                        option.disabled = true;
                        option.selected = true;
                        studentSelect.appendChild(option);
                    } else {
                        data.available_students.forEach(student => {
                            const option = document.createElement('option');
                            option.value = student.id;
                            option.textContent = `${student.name} - ${student.student_code}`;
                            studentSelect.appendChild(option);
                        });
                    }

                    if (type === 'study') {
                        $('#learn_student_id').select2({
                            placeholder: "Chọn học viên",
                            allowClear: true,
                            width: '100%'
                        });
                    } else if (type === 'exam') {
                        $('#exam_student_id').select2({
                            placeholder: "Chọn học viên",
                            allowClear: true,
                            width: '100%'
                        });
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

                        $('#vehicle_select').select2({
                            placeholder: "Chọn xe",
                            allowClear: true,
                            width: '100%'
                        });
                    })
                    .catch(error => console.error('Error fetching vehicles:', error));
            }

            function fetchAndUpdateUser(dateOrStartTime, timeOrEndTime, type) {
                if (!dateOrStartTime || !timeOrEndTime) return;
                const params = new URLSearchParams({ type: type });
                if (type === 'exam') {
                    params.append('date', dateOrStartTime);
                    params.append('time', timeOrEndTime);
                } else {
                    params.append('start_time', dateOrStartTime);
                    params.append('end_time', timeOrEndTime);
                }

                fetch(`/users-available?${params.toString()}`).then(response => response.json()).then(data => {
                    switch (type) {
                        case 'study': {
                            const teacherSelect = document.getElementById('learn_teacher_id');
                            teacherSelect.innerHTML = '<option value="">Chọn giáo viên</option>';
                            (data.teachers || []).forEach(user => {
                                const option = document.createElement('option');
                                option.value = user.id;
                                option.textContent = user.name;
                                teacherSelect.appendChild(option);
                            });
                            $('#learn_teacher_id').select2({
                                placeholder: "Chọn giáo viên",
                                allowClear: true,
                                width: '100%'
                            });
                            break;
                        }
                        case 'exam': {
                            const teacherSelect = document.getElementById('exam_teacher_id');
                            teacherSelect.innerHTML = '<option value="">Chọn giáo viên</option>';
                            (data.teachers || []).forEach(user => {
                                const option = document.createElement('option');
                                option.value = user.id;
                                option.textContent = user.name;
                                teacherSelect.appendChild(option);
                            });
                            $('#exam_teacher_id').select2({
                                placeholder: "Chọn giáo viên",
                                allowClear: true,
                                width: '100%'
                            });
                            break;
                        }
                        case 'work': {
                            const assignedSelect = document.getElementById('work_assigned_to');
                            const supportSelect = document.getElementById('work_support');
                            assignedSelect.innerHTML = '<option value="">Chọn người phụ trách</option>';
                            supportSelect.innerHTML = '<option value="">Chọn người hỗ trợ</option>';
                            (data.users || []).forEach(user => {
                                const option = document.createElement('option');
                                option.value = user.id;
                                option.textContent = user.name;
                                assignedSelect.appendChild(option.cloneNode(true));
                                supportSelect.appendChild(option.cloneNode(true));
                            });
                            $('#work_assigned_to').select2({
                                placeholder: "Chọn người phụ trách",
                                allowClear: true,
                                width: '100%'
                            });
                            $('#work_support').select2({
                                placeholder: "Chọn người hỗ trợ",
                                allowClear: true,
                                width: '100%'
                            });
                            break;
                        }
                        case 'meeting': {
                            const assignedSelect = document.getElementById('meeting_assigned_to');
                            const supportSelect = document.getElementById('meeting_support');
                            const participantsSelect = document.getElementById('user_participants');
                            assignedSelect.innerHTML = '<option value="">Chọn người phụ trách</option>';
                            supportSelect.innerHTML = '<option value="">Chọn người sắp xếp</option>';
                            participantsSelect.innerHTML = '<option value="">Chọn nhân viên tham gia</option>';
                            (data.users || []).forEach(user => {
                                const option = document.createElement('option');
                                option.value = user.id;
                                option.textContent = user.name;
                                assignedSelect.appendChild(option.cloneNode(true));
                                supportSelect.appendChild(option.cloneNode(true));
                                participantsSelect.appendChild(option.cloneNode(true));
                            });
                            $('#meeting_assigned_to').select2({
                                placeholder: "Chọn người phụ trách",
                                allowClear: true,
                                width: '100%'
                            });
                            $('#meeting_support').select2({
                                placeholder: "Chọn người sắp xếp",
                                allowClear: true,
                                width: '100%'
                            });
                            $('#user_participants').select2({
                                placeholder: "Chọn nhân viên tham gia",
                                allowClear: true,
                                width: '100%'
                            });
                            break;
                        }
                        case 'call': {
                            const callSaleSelect = document.getElementById('call_sale_id');
                            callSaleSelect.innerHTML = '<option value="">Chọn người thực hiện</option>';
                            (data.sales || []).forEach(user => {
                                const option = document.createElement('option');
                                option.value = user.id;
                                option.textContent = user.name;
                                callSaleSelect.appendChild(option);
                            });
                            $('#call_sale_id').select2({
                                placeholder: "Chọn người thực hiện",
                                allowClear: true,
                                width: '100%'
                            });
                            break;
                        }
                    }
                }).catch(error => console.error('Error fetching users:', error));
            }

            function fetchExamSchedules(courseId, examFieldIds, dateOrStartTime, timeOrEndTime) {
                const params = new URLSearchParams({
                    course_id: courseId,
                    date: dateOrStartTime,
                    time: timeOrEndTime,
                });
                if (Array.isArray(examFieldIds)) {
                    examFieldIds.forEach(id => params.append('exam_field_ids[]', id));
                } else {
                    params.append('exam_field_ids[]', examFieldIds);
                }

                fetch(`/exam-schedules-available?${params.toString()}`).then(response => response.json()).then(data => {
                    const examScheduleSelect = document.getElementById('exam_schedule_id');
                    examScheduleSelect.innerHTML = '<option value="">-- Chọn sân thi --</option>';

                    data.exam_schedules.forEach(schedule => {
                        const option = document.createElement('option');
                        const location = schedule.stadium?.location || 'Không rõ địa điểm';
                        option.value = schedule.id;
                        option.textContent = `${location} (${schedule.start_time} → ${schedule.end_time})`;
                        examScheduleSelect.appendChild(option);
                    });
                    $('#exam_schedule_id').select2();
                }).catch(error => console.error('Lỗi khi lấy kế hoạch sân thi:', error));
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
                    } else if (selectedType === 'work') {
                        fetchAndUpdateUser(startTime, endTime, 'work');
                    } else if (selectedType === 'meeting') {
                        fetchAndUpdate(null, 'meeting', startTime, endTime);
                        fetchAndUpdateUser(startTime, endTime, 'meeting');
                    } else if (selectedType === 'call') {
                        fetchAndUpdateUser(startTime, endTime, 'call');
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

        $(document).ready(function () {
            const $select = $('#exam_student_id');
            const $container = $('#student-inputs');

            $select.on('change', function () {
                const selectedOptions = $(this).val() || [];

                $container.find('.student-input-group').each(function () {
                    const studentId = $(this).data('student-id');
                    if (!selectedOptions.includes(studentId.toString())) {
                        $(this).remove();
                    }
                });

                selectedOptions.forEach(function (studentId) {
                    if ($container.find(`.student-input-group[data-student-id="${studentId}"]`).length === 0) {
                        const studentName = $select.find(`option[value="${studentId}"]`).text();

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
        });
    </script>
@endsection