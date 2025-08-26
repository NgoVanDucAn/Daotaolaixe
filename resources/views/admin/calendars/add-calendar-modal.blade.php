<div class="modal fade" id="addActivitieModal" tabindex="-1" aria-labelledby="addActivitieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addActivitieModalLabel">Thêm Mới Lịch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAddCalendar" action="{{ route('calendars.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" id="type" value="">
                    <input type="hidden" name="modal_name" value="addActivitieModal">
                    {{-- <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="row">

                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Tên Lịch</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" required />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

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

                                <div class="form-group mb-3 col-12 col-md-6">
                                    <label for="location" class="form-label">Địa Điểm:</label>
                                    <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror"/>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-12 col-md-6" id="date-start-field">
                                    <label for="date_start" class="form-label">Ngày Bắt Đầu</label>
                                    <input type="datetime-local" name="date_start" id="date_start" class="form-control @error('date_start') is-invalid @enderror" required />
                                    @error('date_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-12 col-md-6" id="date-end-field">
                                    <label for="date_end" class="form-label">Ngày Kết Thúc</label>
                                    <input type="datetime-local" name="date_end" id="date_end" class="form-control @error('date_end') is-invalid @enderror" required />
                                    @error('date_end')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-12 col-md-6" id="exam-date-time" style="display: none;">
                                    <div class="mb-3">
                                        <label for="date" class="form-label">Ngày</label>
                                        <input type="date" placeholder="dd/mm/yyyy"name="date" id="date" class="form-control @error('date') is-invalid @enderror" />
                                        @error('date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="time" class="form-label">Buổi thi</label>
                                        <select name="time" id="time" class="form-control @error('time') is-invalid @enderror">
                                            <option value="">-- Chọn buổi thi --</option>
                                            <option value="1">Buổi sáng</option>
                                            <option value="2">Buổi chiều</option>
                                            <option value="3">Cả ngày</option>
                                        </select>
                                        @error('time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="lanthi" class="form-label">Lần thi</label>
                                        <select name="lanthi" id="lanthi" class="form-control @error('lanthi') is-invalid @enderror">
                                            @foreach (range(1, 100) as $i)
                                                <option value={{ $i }}>Lần {{ $i }}</option>
                                            @endforeach
                                        </select>
                                        @error('lanthi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

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

                                <div id="alert-message" class="alert alert-danger d-none"></div>

                                <div class="mb-3">
                                    <label for="stadium_id" class="form-label mt-2">Sân tập</label>
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

                            <div id="exam-fields" class="mb-3" style="display: none;">
                                <input type="hidden" name="exam_course_type" id="exam_course_type" value="">
                                <div class="form-group mb-3">
                                    <label for="exam_fee" class="form-label">Lệ phí thi</label>
                                    <input type="number" name="exam_fee" id="exam_fee" class="form-control @error('exam_fee') is-invalid @enderror"/>
                                    @error('exam_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exam_fee_deadline" class="form-label">Hạn nộp</label>
                                    <input type="date" placeholder="dd/mm/yyyy"name="exam_fee_deadline" id="exam_fee_deadline" class="form-control @error('exam_fee_deadline') is-invalid @enderror"/>
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
                                        <option value="">-- Vui lòng chọn khóa học, thời gian của lịch trước --</option>
                                    </select>
                                    @error('exam_student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="alert-message" class="alert alert-danger d-none"></div>

                                <div id="student-inputs" class="mb-3 space-y-4"></div>

                                <div class="mb-3">
                                    <label for="exam_schedule_id" class="form-label">Sân thi</label>
                                    <select name="exam_schedule_id" id="exam_schedule_id" class="form-select">
                                        <option value="">-- Vui lòng chọn thời gian bắt đầu, kết thúc, khóa học và môn học trước --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Thêm Lịch</button>
                    </div> --}}
                    <div class="row">
                        <div class="col-12 ">
                            <div class="row">
                    
                                {{-- Phần dùng chung: Tên Lịch và Mức Độ Ưu Tiên --}}
                                {{-- <div class="form-group mb-3 col-12">
                                    <label for="name" class="form-label">Tên Lịch</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" required />
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                    
                                <div class="form-group mb-3 col-12 col-md-6">
                                    <label for="priority" class="form-label">Mức Độ Ưu Tiên</label>
                                    <select name="priority" id="priority" class="form-control @error('priority') is-invalid @enderror" required>
                                        <option value="Low">Thấp</option>
                                        <option value="Normal">Bình Thường</option>
                                        <option value="High">Cao</option>
                                        <option value="Urgent">Khẩn Cấp</option>
                                    </select>
                                    @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div> --}}
                    
                                {{-- Nếu là Lịch học --}}
                                <div id="study-fields" class="mb-4">
                                    <div class="row g-3">
                                        <div class="col-12 col-xl-8 col-lg-12">
                                            <div class="row g-3">
                                                <div class="form-group col-12 mb-2">
                                                    <label for="name" class="form-label">Tên sự kiện</label>
                                                    <input type="text" class="form-control" id="name" value="{{ old('name') }}">
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="form-group col-12 col-md-4">
                                                    <label for="date_start" class="form-label">Buổi học</label>
                                                    <select name="time_learn" id="time_learn" class="form-control @error('time_learn') is-invalid @enderror">
                                                        <option value="">-- Chọn buổi học --</option>
                                                        @foreach(range(1,100) as $i)
                                                            <option value="{{ $i }}" {{ old('time_learn') }}>Buổi {{ $i }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('time_learn')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6 col-md-4" id="date-start-field">
                                                    <label for="date_start" class="form-label">Thời Gian Bắt Đầu</label>
                                                    <input 
                                                        type="text" 
                                                        placeholder="dd/mm/yyyy HH:mm" 
                                                        name="date_start" id="date_start" 
                                                        class="form-control datetime-local @error('date_start') is-invalid @enderror"
                                                        value="{{ old('date_start') ? \Carbon\Carbon::parse(old('date_start'))->format('d/m/Y H:i') : '' }}"
                                                    />
                                                    @error('date_start')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                            
                                                <div class="form-group col-6 col-md-4" id="date-end-field">
                                                    <label for="date_end" class="form-label">Thời Gian Kết Thúc</label>
                                                    <input type="text" placeholder="dd/mm/yyyy HH:mm" name="date_end" id="date_end" class="form-control datetime-local @error('date_end') is-invalid @enderror"/>
                                                    @error('date_end')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                            
                                            <div class="row g-3">
                                                {{-- <div class="form-group col-12 col-md-12">
                                                    <label for="learn_course_id" class="form-label">Khóa học</label>
                                                    <select name="learn_course_id" id="learn_course_id" class="form-control">
                                                        <option value="">-- Chọn --</option>
                                                    </select>
                                                </div> --}}
                                                <div class="form-group col-12 col-md-12">
                                                    <label for="vehicle_type" class="form-label">Hình thức học</label>
                                                    <select name="vehicle_type" id="vehicle_type_learn" class="form-control">
                                                        <option value="">-- Chọn --</option>
                                                        <option value="1">Học xe máy</option>
                                                        <option value="2">Học ô tô</option>
                                                    </select>
                                                </div>
                                                {{-- <div class="form-group col-12 col-md-6">
                                                    <label for="learning_id" class="form-label">Môn học</label>
                                                    <select name="learning_id" id="learning_id" class="form-control">
                                                        <option value="">-- Chọn --</option>
                                                    </select>
                                                </div> --}}
                                            </div>
                                            <div class="form-group mb-3 col-12 col-md-12 learning_id">
                                                <label for="learning_id" class="form-label">Môn học
                                                    <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Cần chọn khóa học trước" style="cursor: pointer;">&#x3f;</span>
                                                </label>
                                                <div id="learning_id" class="mt-2">
                                                    <p class="text-muted">-- Vui lòng chọn hình thức học trước --</p>
                                                </div>
                                                @error('learning_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            {{-- <div class="form-group mb-3 col-12 col-md-12 form-calendar-learn-instructor" style="display: none">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="km">Km</label>
                                                        <input type="number" id="km" class="form-control" value="" min="0" name="km">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="hour">Số giờ chạy được</label>
                                                        <input type="text" id="hour" placeholder="HH:mm" class="form-control time-input" value="" min="0" name="hour">
                                                    </div>
                                                </div>
                                                <div class="row mt-4">
                                                    <div class="col-md-6 d-flex align-item-center">
                                                        <input type="checkbox" class="form-check-input" id="learn_auto" name="learn_auto">
                                                        <span class="ms-2">Học tự động</span>
                                                        
                                                    </div>
                                                    <div class="col-md-6 d-flex align-item-center">
                                                        <input type="checkbox" class="form-check-input" id="learn_night" name="learn_night">
                                                        <span class="ms-2">Học ban đêm</span> 
                                                    </div>
                                                </div>
                                            </div> --}}
                                            
                                            {{-- <div class="row g-3">
                                                <div class="form-group col-9 col-md-12">
                                                    <label for="pick_ip_point" class="form-label mt-2">Điểm đón</label>
                                                    <textarea name="pick_ip_point" id="pick_ip_point" class="form-control"></textarea>
                                                </div>
                                            </div> --}}

                                            <div class="row g-3">
                                                <div class="form-group col-9 col-md-12">
                                                    <label for="stadium_id" class="form-label mt-2">Sân tập</label>
                                                    <select name="stadium_id" id="select_stadium_id" class="form-select">
                                                        <option value="">Chọn sân</option>
                                                        @foreach ($stadiums as $stadium)
                                                            <option value="{{ $stadium->id }}" {{ old('stadium_id') == $stadium->id ? 'selected' : '' }}>
                                                                {{ $stadium->location }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="form-group col-9 col-md-12">
                                                    <label for="status" class="form-label mt-2">Trạng thái</label>
                                                    <select name="status" id="status_id_learn" class="form-select">
                                                        <option value="1">Đang chờ</option>
                                                        <option value="2">Đang học</option>
                                                        <option value="10">Hoàn thành</option>
                                                        <option value="3">Thiếu giáo viên</option>
                                                        <option value="4">Hủy ca</option>
                                                        <option value="5">Hoãn</option>
                                                        <option value="6">Bỏ ca lại</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-xl-4 col-lg-12">
                                            <div class="form-group mb-2">
                                                <label for="learn_teacher_id" class="form-label">Giáo viên</label>
                                                <select name="learn_teacher_id" id="learn_teacher_id" class="form-control @error('learn_teacher_id') is-invalid @enderror">
                                                    <option value="">-- Vui lòng chọn thời gian trước --</option>
                                                </select>
                                                @error('learn_teacher_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="learn_student_id" class="form-label">Học viên khóa học
                                                    {{-- <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Cần chọn thời gian và khóa học trước" style="cursor: pointer;">&#x3f;</span> --}}
                                                </label>
                                                <select name="learn_student_id[]" id="learn_student_id_select" class="w-full form-control @error('learn_student_id') is-invalid @enderror" multiple>
                                                    <option value="">-- Chọn học viên khóa học --</option>
                                                </select>
                                                @error('learn_student_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div id="alert-message" class="alert alert-danger d-none"></div>
                                            </div>

                                            <div class="form-group mb-3 col-12 col-md-12 learn-container form-calendar-learn-instructor" style="display: none"></div>

                                            <div class="form-group mb-2 vehicle_select_container" style="display: none">
                                                <label for="vehicle_select" class="form-label">Xe Học</label>
                                                <select name="vehicle_select" id="vehicle_select" class="form-select">
                                                    <option value="">-- Chọn xe --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    
                                {{-- Nếu là Lịch thi --}}
                                <div id="exam-fields" class="mb-4" style="display: none;">
                                    <div class="row g-3">
                                        <div class="col-12 col-xl-8 col-lg-12">
                                            <input type="hidden" name="exam_course_type" id="exam_course_type" value="">
                                            <div class="row g-3" id="exam-date-time">
                                                <div class="form-group col-12 col-md-4 position-relative">
                                                    <label for="date" class="form-label">Chọn ngày <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Trường bắt buộc" style="cursor: pointer;">*</span></label>
                                                    <input type="text" placeholder="dd/mm/yyyy" name="date" id="date" class="form-control real-date" autocomplete="off" value="{{ old('date') ? \Carbon\Carbon::parse(old('date'))->format('d/m/Y') : '' }}" />
                                                    {{-- <div class="datepicker-button" style="position: absolute; right: 20px; top: 38px; cursor: pointer;">📅</div> --}}
                                                </div>
        
                                                <div class="form-group col-12 col-md-4">
                                                    <label for="time" class="form-label">Chọn thời gian <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Trường bắt buộc" style="cursor: pointer;">*</span></label>
                                                    <select name="time" id="time" class="form-control @error('time') is-invalid @enderror">
                                                        <option value="">Chọn thời gian</option>
                                                        <option value="1">Buổi sáng</option>
                                                        <option value="2">Buổi chiều</option>
                                                        <option value="3">Cả ngày</option>
                                                    </select>
                                                    @error('time')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                            
                                                <div class="form-group col-12 col-md-4">
                                                    <label for="lanthi" class="form-label">Lần thi <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Trường bắt buộc" style="cursor: pointer;">*</span></label>
                                                    <select name="lanthi" id="lanthi" class="form-control">
                                                        @foreach(range(1,100) as $i)
                                                            <option value="{{ $i }}">Lần {{ $i }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                            
                                                {{-- <div class="form-group mb-3 col-12 col-md-4">
                                                    <label for="exam_teacher_id" class="form-label">Giáo viên</label>
                                                    <select name="exam_teacher_id" id="exam_teacher_id" class="form-control @error('exam_teacher_id') is-invalid @enderror">
                                                        <option value="">-- Vui lòng chọn thời gian trước --</option>
                                                    </select>
                                                    @error('exam_teacher_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div> --}}
                                            </div>
                            
                                            {{-- Hàng 2 --}}
                                            <div class="row g-3">
                                                <div class="form-group col-12 col-md-12">
                                                    <label for="vehicle_type" class="form-label">Hình thức thi</label>
                                                    <select name="vehicle_type" id="vehicle_type_exam" class="form-control">
                                                        <option value="">-- Chọn --</option>
                                                        <option value="1" {{ old('vehicle_type') == 1 ? 'selected' : ''}}>Thi xe máy</option>
                                                        <option value="2" {{ old('vehicle_type') == 2 ? 'selected' : ''}}>Thi ô tô</option>
                                                    </select>
                                                </div>
                                                {{-- <div class="form-group mb-3 col-12 col-md-12">
                                                    <label for="exam_course_id" class="form-label">Khóa học</label>
                                                    <select name="exam_course_id" id="exam_course_id" class="form-control @error('exam_course_id') is-invalid @enderror">
                                                        <option value="">Chọn khóa học</option>
                                                    </select>
                                                    @error('exam_course_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div> --}}
                            
                                                {{-- <div class="form-group mb-3 col-12 col-md-6">
                                                    <label for="exam_id" class="form-label">Môn thi</label>
                                                    <select name="exam_id[]" id="exam_id" class="form-control @error('exam_id') is-invalid @enderror" multiple>
                                                        <option value="">-- Vui lòng chọn khóa học trước --</option>
                                                    </select>
                                                    @error('exam_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div> --}}
                                                <div class="form-group mb-3 col-12 col-md-12 exam_id">
                                                    <label for="exam_id" class="form-label">Môn thi
                                                        <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Cần chọn hình thức thi trước" style="cursor: pointer;">&#x3f;</span>
                                                    </label>
                                                    <div id="exam_id">
                                                    </div>
                                                    @error('exam_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{-- Hàng 3 --}}
                                            <div class="row g-3">
                                                <div class="form-group col-12">
                                                    <label for="exam_schedule_id" class="form-label">
                                                        Sân thi
                                                        {{-- <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Chọn thời gian bắt đầu, kết thúc, khóa học và môn học trước" style="cursor: pointer;">&#x3f;</span> --}}
                                                    </label>
                                                    <select name="exam_schedule_id" id="exam_schedule_id" class="form-select">
                                                        <option value="">Chọn sân</option>
                                                        @foreach ($stadiums as $stadium)
                                                            <option value="{{ $stadium->id }}" {{ old('stadium_id') == $stadium->id ? 'selected' : '' }}>
                                                                {{ $stadium->location }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                
                                                {{-- <div class="form-group mb-3 col-12 col-md-6">
                                                    <label for="vehicle_select" class="form-label">Xe Học</label>
                                                    <select name="vehicle_select" id="vehicle_select" class="form-select">
                                                        <option value="">-- Chọn --</option>
                                                    </select>
                                                </div> --}}
                                            </div>
                                            {{-- Hàng 4 --}}
                                            <div class="row g-3">
                                                <div class="form-group col-12">
                                                    <label for="status
                                                    " class="form-label mt-2">Trạng thái</label>
                                                    <select name="status" id="status_id" class="form-select">
                                                        <option value="1" {{ old('status') == 1 ? 'selected' : ''}}>Đang chờ</option>
                                                        <option value="10" {{ old('status') == 10 ? 'selected' : ''}}>Đỗ</option>
                                                        <option value="2" {{ old('status') == 2 ? 'selected' : ''}}>Trượt</option>
                                                        <option value="3" {{ old('status') == 3 ? 'selected' : ''}}>Thi lại</option>
                                                        <option value="4" {{ old('status') == 4 ? 'selected' : ''}}>Thi mới</option>
                                                        <option value="5" {{ old('status') == 5 ? 'selected' : ''}}>Bỏ thi</option>
                                                        <option value="6" {{ old('status') == 6 ? 'selected' : ''}}>Hoãn lại</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="form-group mb-3 col-12">
                                                    <label for="description" class="form-label">Mô Tả</label>
                                                    <textarea name="description" id="description" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-xl-4 col-lg-12">
                                            <div class="form-group mb-3">
                                                <label for="exam_student_id" class="form-label">Học viên khóa học
                                                    {{-- <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Cần chọn thời gian và khóa học trước" style="cursor: pointer;">&#x3f;</span> --}}
                                                </label>
                                                <select name="exam_student_id[]" id="exam_student_id_select" class="form-control @error('exam_student_id') is-invalid @enderror" multiple>
                                                    {{-- <option value="">Vui lòng chọn khóa học, thời gian của lịch trước</option> --}}
                                                    <option value="">-- Chọn học viên khóa học --</option>
                                                </select>
                                                @error('exam_student_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
    
                                            <div id="alert-message" class="alert alert-danger d-none"></div>
            
                                            <div id="student-inputs" class="mb-3 space-y-4"></div>
                                        </div>
                                    </div>
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
                <button type="submit" class="btn btn-primary" form="formAddCalendar">
                    <i class="mdi mdi-content-save-outline me-2"></i> <span>Lưu</span>
                </button>
            </div>
        </div>
    </div>
</div>