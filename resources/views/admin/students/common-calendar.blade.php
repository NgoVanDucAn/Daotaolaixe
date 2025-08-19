    <!-- Lịch học thực hành -->
    <div class="card mb-4 calendar calendar-th study_practice" style="display: none" data-id="study_practice">
        <div class="card-header">Lịch Học
            
            <button 
                type="button" 
                class="btn btn-sm float-end" 
                style="color: #3b82f6; border: 1px solid #3b82f6;"
                data-bs-toggle="modal" data-bs-target="#addActivitieModal" 
                data-student-id="{{ $student->id }}" 
                data-student-name="{{ $student->name }} - {{ $student->student_code }}" 
                data-course-id="{{ $course->id }}" 
                data-course-name="{{ $course->code }}"
                data-calendar-type="study_practice"
            >
            <span class="me-2">+</span> <span>Tạo Lịch Học Mới</span>
            </button>
            <button type="button" class="btn btn-sm btn-primary float-end me-2">Tìm kiếm</button>
            <div class="float-end me-2" >
                <select name="" id="" class="form-control" style="height: 32px; line-height: 15px;">
                    <option value="1">Loại học</option>
                    <option value="2">Học kỹ năng</option>
                    <option value="3">Sa hình</option>
                    <option value="4">Chạy DAT</option>
                </select>
            </div>
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
                            <th>Học viên</th>
                            <th>Ngày sinh</th>
                            <th>Số điện thoại</th>
                            <th>Môn học</th>
                            <th>Giáo viên</th>
                            <th>Điểm đón</th>
                            <th>Sân học</th>
                            <th>Khoá học</th>
                            <th>Tự động</th>
                            <th>Ban đêm</th>
                            <th>Duyệt Km</th>
                            <th>Km</th>
                            <th>Giờ</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php \Carbon\Carbon::setLocale('vi'); @endphp
                        @forelse($calendars->where('type', 'study')->filter(fn($calendar) => $calendar->fields->contains(fn($field) => $field->is_practical)) as $index => $calendar)
                        <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ ucfirst(\Carbon\Carbon::parse($calendar->date_start)->translatedFormat('l')) }} <br>
                                    {{ \Carbon\Carbon::parse($calendar->date_start)->format('d-m-Y') }}
                                </td>
                                <td>{{ ($calendar->date_start && $calendar->date_end) ? $calendar->date_start->format('H:i') . " - " . $calendar->date_end->format('H:i') : 'N/A' }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($student->dob)->format('d/m/Y') }}</td>
                                <td>{{ $student->phone }}</td>
                                <td>
                                    @forelse ($calendar->fields as $field)
                                        {{ $field->name }}
                                    @empty
                                        Không có môn
                                    @endforelse
                                </td>
                                <td>{{ $calendar->instructor?->name ?? 'Không có giáo viên' }}</td>
                                <td>new</td>
                                <td>
                                    @if (!empty($calendar->stadium_location))
                                        <a href="{{ $calendar->stadium_google_maps_url }}" target="_blank">{{ $calendar->stadium_location ?? 'N/A' }}</a>
                                    @else
                                        Không có sân tập
                                    @endif
                                </td>
                                <td>{{ $course->code ?? 'Chưa có thông tin' }}</td>
                                <td>
                                    <i style="font-size: 24px; color: green" class="mdi mdi-check-circle-outline"></i>lưu
                                </td>
                                <td>
                                    <i style="font-size: 24px; color: red" class="mdi mdi-close-circle-outline"></i>lưu
                                </td>
                                <td>new</td>
                                <td>{{ $calendar->pivot->km ?? 'N/A' }}</td>
                                <td>{{ $calendar->pivot->hours ?? 'N/A' }}</td>
                                <td>
                                    <a href="#" class="btn btn-warning ps-2 pe-2 pt-0 pb-0">
                                        <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                                    </a>
                                    <form action="#" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger ps-2 pe-2 pt-0 pb-0">
                                            <i class="mdi mdi-trash-can-outline fs-4"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="17" class="text-center">Không có lịch học</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lịch học lý thuyết - cabin -->
    <div class="card mb-4 calendar calendar-cb study_theory" style="display: none" data-id="study_theory">
        <div class="card-header">Lịch học Lý thuyết - Cabin
            <button 
                type="button" 
                class="btn btn-sm float-end" 
                style="color: #3b82f6; border: 1px solid #3b82f6;"
                data-bs-toggle="modal" data-bs-target="#addActivitieModal" 
                data-student-id="{{ $student->id }}" 
                data-student-name="{{ $student->name }} - {{ $student->student_code }}" 
                data-course-id="{{ $course->id }}" 
                data-course-name="{{ $course->code }}"
                data-calendar-type="study_theory"
            >
                <span class="me-2">+</span> <span>Tạo Lịch học Lý thuyết - Cabin</span>
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
                            <th>Học viên</th>
                            <th>Ngày sinh</th>
                            <th>Môn học</th>
                            <th>Trạng thái</th>
                            <th>Sân học</th>
                            <th>Khoá học</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calendars->where('type', 'exam')->where('level', 6) as $index => $calendar)
                            @foreach ($calendar->fields as $fieldIndex => $field)
                                {{-- <tr>
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
                                </tr> --}}
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ucfirst(\Carbon\Carbon::parse($calendar->date_start)->translatedFormat('l')) }} <br>
                                        {{ \Carbon\Carbon::parse($calendar->date_start)->format('d-m-Y') }}</td>
                                    <td>{{ ($calendar->date_start && $calendar->date_end) ? $calendar->date_start->format('H:i') . " - " . $calendar->date_end->format('H:i') : 'N/A' }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($student->dob)->format('d/m/Y') }}</td>
    
                                    {{-- <td>{{ $calendar->date_start ? $calendar->date_start->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ ($calendar->date_start && $calendar->date_end) ? $calendar->date_start->format('H:i') . " - " . $calendar->date_end->format('H:i') : 'N/A' }}</td> --}}
                                    {{-- <td>{{ $calendar->instructor?->name ?? 'Không có giáo viên' }}</td> --}}
                                    <td>
                                        @forelse ($calendar->fields as $field)
                                            {{ $field->name }}
                                        @empty
                                            Không có môn
                                        @endforelse
                                    </td>
                                    <td>new</td>
                                    <td>
                                        @if (!empty($calendar->stadium_location))
                                            <a href="{{ $calendar->stadium_google_maps_url }}" target="_blank">{{ $calendar->stadium_location ?? 'N/A' }}</a>
                                        @else
                                            Không có sân tập
                                        @endif
                                    </td>
                                    <td>{{ $course->code ?? 'Chưa có thông tin' }}</td>
                                    {{-- <td>{{ $calendar->pivot->hours ?? 'N/A' }}</td>
                                    <td>{{ $calendar->pivot->remarks}}</td> --}}
                                    <td>
                                        <a href="#" class="btn btn-warning ps-2 pe-2 pt-0 pb-0">
                                            <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                                        </a>
                                        <form action="#" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger ps-2 pe-2 pt-0 pb-0">
                                                <i class="mdi mdi-trash-can-outline fs-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="17" class="text-center">Không có lịch thi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lịch thi hết môn lý thuyết -->
    <div class="card mb-4 calendar calendar-lt exam_theory" style="display: none" data-id="exam_theory">
        <div class="card-header">Lịch Thi Hết Môn Lý Thuyết
            <button 
                type="button" 
                class="btn btn-sm float-end" 
                style="color: #3b82f6; border: 1px solid #3b82f6;"
                data-bs-toggle="modal" data-bs-target="#addActivitieModal" 
                data-student-id="{{ $student->id }}" 
                data-student-name="{{ $student->name }} - {{ $student->student_code }}" 
                data-course-id="{{ $course->id }}" 
                data-course-name="{{ $course->code }}"
                data-calendar-type="exam_theory"
                data-exam-course-type="6"
            >
                <span class="me-2">+</span> <span>Tạo Lịch Thi Hết Môn Lý Thuyết</span>
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
                            <th>Học viên</th>
                            <th>Ngày sinh</th>
                            <th>Môn học</th>
                            <th>Trạng thái</th>
                            <th>Sân học</th>
                            <th>Khoá học</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calendars->where('type', 'exam')->where('level', 6) as $index => $calendar)
                            @foreach ($calendar->fields as $fieldIndex => $field)
                                {{-- <tr>
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
                                </tr> --}}
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ucfirst(\Carbon\Carbon::parse($calendar->date_start)->translatedFormat('l')) }} <br>
                                        {{ \Carbon\Carbon::parse($calendar->date_start)->format('d-m-Y') }}</td>
                                    <td>{{ ($calendar->date_start && $calendar->date_end) ? $calendar->date_start->format('H:i') . " - " . $calendar->date_end->format('H:i') : 'N/A' }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($student->dob)->format('d/m/Y') }}</td>
    
                                    {{-- <td>{{ $calendar->date_start ? $calendar->date_start->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ ($calendar->date_start && $calendar->date_end) ? $calendar->date_start->format('H:i') . " - " . $calendar->date_end->format('H:i') : 'N/A' }}</td> --}}
                                    {{-- <td>{{ $calendar->instructor?->name ?? 'Không có giáo viên' }}</td> --}}
                                    <td>
                                        @forelse ($calendar->fields as $field)
                                            {{ $field->name }}
                                        @empty
                                            Không có môn
                                        @endforelse
                                    </td>
                                    <td>new</td>
                                    <td>
                                        @if (!empty($calendar->stadium_location))
                                            <a href="{{ $calendar->stadium_google_maps_url }}" target="_blank">{{ $calendar->stadium_location ?? 'N/A' }}</a>
                                        @else
                                            Không có sân tập
                                        @endif
                                    </td>
                                    <td>{{ $course->code ?? 'Chưa có thông tin' }}</td>
                                    {{-- <td>{{ $calendar->pivot->hours ?? 'N/A' }}</td>
                                    <td>{{ $calendar->pivot->remarks}}</td> --}}
                                    <td>
                                        <a href="#" class="btn btn-warning ps-2 pe-2 pt-0 pb-0">
                                            <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                                        </a>
                                        <form action="#" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger ps-2 pe-2 pt-0 pb-0">
                                                <i class="mdi mdi-trash-can-outline fs-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="17" class="text-center">Không có lịch thi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lịch thi hết môn thực hành -->
    <div class="card mb-4 calendar calendar-exam-th exam_practice" style="display: none" data-id="exam_practice">
        <div class="card-header">Lịch Thi Hết Môn Thực Hành
            <button 
                type="button" 
                class="btn btn-sm float-end" 
                style="color: #3b82f6; border: 1px solid #3b82f6;"
                data-bs-toggle="modal" data-bs-target="#addActivitieModal" 
                data-student-id="{{ $student->id }}" 
                data-student-name="{{ $student->name }} - {{ $student->student_code }}" 
                data-course-id="{{ $course->id }}" 
                data-course-name="{{ $course->code }}"
                data-calendar-type="exam_practice"
                data-exam-course-type="5"
            >
                <span class="me-2">+</span> <span>Tạo Lịch Thi Hết Môn Thực Hành</span>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            {{-- <th>STT</th>
                            <th>Ngày Thi</th>
                            <th>Buổi Thi</th>
                            <th>Địa Điểm</th>
                            <th>Kết Quả Tổng</th>
                            <th>Môn Thi</th>
                            <th>Lần Thi</th>
                            <th>Kết Quả</th>
                            <th>Nhận xét</th> --}}
                            <th>STT</th>
                            <th>Buổi thi</th>
                            <th>Lần thi</th>
                            <th>Học viên</th>
                            <th>Ngày sinh</th>
                            <th>CCCD</th>
                            <th>SĐT</th>
                            <th>SBD</th>
                            <th>Khoá học</th>
                            <th>Môn thi</th>
                            <th>Khám SK</th>
                            <th>Sân</th>
                            <th>Đưa đón</th>
                            <th>Kết quả</th>
                            <th>Ghi chú</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    {{-- <tbody>
                        @forelse($calendars->where('type', 'exam')->where('level', 5) as $index => $calendar)
                            @foreach ($calendar->fields as $fieldIndex => $field)
                                <tr>
                                    @if ($fieldIndex === 0)
                                        <td rowspan="{{ $calendar->fields->count() }}">{{ $loop->iteration }}</td>
                                        <td rowspan="{{ $calendar->fields->count() }}">
                                            @php
                                                $date = \Carbon\Carbon::parse($calendar->date ?? $calendar->date_start);
                                            @endphp
                                            {{ ucfirst($date->translatedFormat('l')) }}<br>
                                            {{ $date->format('d-m-Y') }}
                                        </td>
                                        <td>new</td>
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
                    </tbody> --}}
                    <tbody>
                        @forelse($calendars->where('type', 'exam')->where('level', 5) as $index => $calendar)
                        @php
                            $session = match($calendar->time) {
                                1 => 'Buổi sáng',
                                2 => 'Buổi chiều',
                                3 => 'Cả ngày',
                                default => 'Không xác định',
                            };

                            $badgeClass = match($session) {
                                'Buổi sáng' => 'bg-primary',
                                'Buổi chiều' => 'bg-warning text-dark',
                                'Cả ngày' => 'bg-success',
                                default => 'bg-secondary',
                            };
                        @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">{{ $session }}</span>
                                </td>
                                <td>
                                    @php
                                        $attemptNumber = $calendar['fields'][0]->attempt_number
                                    @endphp
                                    <span class="badge bg-secondary">Lần {{ $attemptNumber }}</span>
                                </td>
                                <td>{{ $student->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($student->dob)->format('d/m/Y') }}</td>
                                <td>{{ $student->identity_card }}</td>
                                <td>{{ $student->phone }}</td>
                                <td>{{ '--' }}</td>
                                <td>{{ $course->code }}</td>
                                <td>
                                    @if($calendar->fields && $calendar->fields->isNotEmpty())
                                        @foreach($calendar->fields as $field)
                                            <span class="badge bg-success">{{ $field->name ?? 'Không có tên field' }}</span>
                                        @endforeach
                                    @else
                                        <p>Không có dữ liệu môn thi</p>
                                    @endif
                                </td>
                                <td>{{ 'new' }}</td>
                                <td>
                                    @if (!empty($calendar->stadium_location))
                                    <a href="{{ $calendar->stadium_google_maps_url }}" target="_blank">{{ $calendar->stadium_location ?? 'N/A' }}</a>
                                    @else
                                    Không có sân tập
                                    @endif
                                </td>
                                <td>
                                    @if ($calendar->pivot->pickup === 1)
                                        <i style="font-size: 24px; color: green" class="mdi mdi-check-circle-outline"></i>
                                    @else
                                        <i style="font-size: 24px; color: red" class="mdi mdi-close-circle-outline"></i>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $uniqueStatuses = [];
                                
                                        
                                            $status = $calendar['fields'][0]['exam_all_status'] ?? null;
                                            if (!in_array($status, $uniqueStatuses)) {
                                                $uniqueStatuses[] = $status;
                                            }
                                        
                                    @endphp
                                
                                    @foreach ($uniqueStatuses as $status)
                                        @if ($status === 1)
                                            <span class="badge bg-success">Đỗ</span>
                                        @elseif ($status === 2)
                                            <span class="badge bg-danger">Trượt</span>
                                        @else
                                            <span class="badge bg-secondary">Chưa có kết quả</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $calendar->description }}</td>
                                <td>
                                    <a href="#" class="btn btn-warning ps-2 pe-2 pt-0 pb-0">
                                        <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                                    </a>
                                    <form action="#" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger ps-2 pe-2 pt-0 pb-0">
                                            <i class="mdi mdi-trash-can-outline fs-4"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="17" class="text-center">Không có lịch thi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lịch thi tốt nghiệp -->
    <div class="card mb-4 calendar calendar-tn exam_graduation" style="display: none" data-id="exam_graduation">
        <div class="card-header">Lịch Thi Tốt Nghiệp
            <button 
                type="button" 
                class="btn btn-sm float-end" 
                style="color: #3b82f6; border: 1px solid #3b82f6;"
                data-bs-toggle="modal" data-bs-target="#addActivitieModal" 
                data-student-id="{{ $student->id }}" 
                data-student-name="{{ $student->name }} - {{ $student->student_code }}" 
                data-course-id="{{ $course->id }}" 
                data-course-name="{{ $course->code }}"
                data-calendar-type="exam_graduation"
                data-exam-course-type="1"
            >
                <span class="me-2">+</span> <span>Tạo Lịch Thi Tốt Nghiệp</span>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            {{-- <th>STT</th>
                            <th>Ngày Thi</th>
                            <th>Buổi Thi</th>
                            <th>Địa Điểm</th>
                            <th>Kết Quả Tổng</th>
                            <th>Môn Thi</th>
                            <th>Lần Thi</th>
                            <th>Kết Quả</th>
                            <th>Nhận xét</th> --}}
                            <th>STT</th>
                            <th>Buổi thi</th>
                            <th>Lần thi</th>
                            <th>Học viên</th>
                            <th>Ngày sinh</th>
                            <th>CCCD</th>
                            <th>SĐT</th>
                            <th>SBD</th>
                            <th>Khoá học</th>
                            <th>Môn thi</th>
                            <th>Khám SK</th>
                            <th>Sân</th>
                            <th>Đưa đón</th>
                            <th>Kết quả</th>
                            <th>Ghi chú</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calendars->where('type', 'exam')->where('level', 1) as $index => $calendar)
                        @php
                            $session = match($calendar->time) {
                                1 => 'Buổi sáng',
                                2 => 'Buổi chiều',
                                3 => 'Cả ngày',
                                default => 'Không xác định',
                            };

                            $badgeClass = match($session) {
                                'Buổi sáng' => 'bg-primary',
                                'Buổi chiều' => 'bg-warning text-dark',
                                'Cả ngày' => 'bg-success',
                                default => 'bg-secondary',
                            };
                        @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">{{ $session }}</span>
                                </td>
                                <td>
                                    @php
                                        $attemptNumber = $calendar['fields'][0]->attempt_number
                                    @endphp
                                    <span class="badge bg-secondary">Lần {{ $attemptNumber }}</span>
                                </td>
                                <td>{{ $student->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($student->dob)->format('d/m/Y') }}</td>
                                <td>{{ $student->identity_card }}</td>
                                <td>{{ $student->phone }}</td>
                                <td>{{ '--' }}</td>
                                <td>{{ $course->code }}</td>
                                <td>
                                    @if($calendar->fields && $calendar->fields->isNotEmpty())
                                        @foreach($calendar->fields as $field)
                                            <span class="badge bg-success">{{ $field->name ?? 'Không có tên field' }}</span>
                                        @endforeach
                                    @else
                                        <p>Không có dữ liệu môn thi</p>
                                    @endif
                                </td>
                                <td>{{ 'new' }}</td>
                                <td>
                                    @if (!empty($calendar->stadium_location))
                                    <a href="{{ $calendar->stadium_google_maps_url }}" target="_blank">{{ $calendar->stadium_location ?? 'N/A' }}</a>
                                    @else
                                    Không có sân tập
                                    @endif
                                </td>
                                <td>
                                    @if ($calendar->pivot->pickup === 1)
                                        <i style="font-size: 24px; color: green" class="mdi mdi-check-circle-outline"></i>
                                    @else
                                        <i style="font-size: 24px; color: red" class="mdi mdi-close-circle-outline"></i>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $uniqueStatuses = [];
                                
                                        
                                            $status = $calendar['fields'][0]['exam_all_status'] ?? null;
                                            if (!in_array($status, $uniqueStatuses)) {
                                                $uniqueStatuses[] = $status;
                                            }
                                        
                                    @endphp
                                
                                    @foreach ($uniqueStatuses as $status)
                                        @if ($status === 1)
                                            <span class="badge bg-success">Đỗ</span>
                                        @elseif ($status === 2)
                                            <span class="badge bg-danger">Trượt</span>
                                        @else
                                            <span class="badge bg-secondary">Chưa có kết quả</span>
                                        @endif
                                    @endforeach
                                </td>
                                
                                <td>{{ $calendar->description }}</td>
                                <td>
                                    <a href="#" class="btn btn-warning ps-2 pe-2 pt-0 pb-0">
                                        <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                                    </a>
                                    <form action="#" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger ps-2 pe-2 pt-0 pb-0">
                                            <i class="mdi mdi-trash-can-outline fs-4"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            {{-- @foreach ($calendar->fields as $fieldIndex => $field)
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
                            @endforeach --}}
                        @empty
                            <tr>
                                <td colspan="16" class="text-center">Không có lịch thi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lịch thi sát hạch -->
    <div class="card mb-4 calendar calendar-sh exam_certification" style="display: none" data-id="exam_certification">
        <div class="card-header">Lịch Thi Sát Hạch
            <button 
                type="button" 
                class="btn btn-sm float-end" 
                style="color: #3b82f6; border: 1px solid #3b82f6;"
                data-bs-toggle="modal" data-bs-target="#addActivitieModal" 
                data-student-id="{{ $student->id }}" 
                data-student-name="{{ $student->name }} - {{ $student->student_code }}" 
                data-course-id="{{ $course->id }}" 
                data-course-name="{{ $course->code }}"
                data-calendar-type="exam_certification"
                data-exam-course-type="2"
            >
                <span class="me-2">+</span> <span>Tạo Lịch Thi Sát Hạch</span>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        {{-- <tr>
                            <th>STT</th>
                            <th>Ngày Thi</th>
                            <th>Buổi Thi</th>
                            <th>Địa Điểm</th>
                            <th>Kết Quả Tổng</th>
                            <th>Môn Thi</th>
                            <th>Lần Thi</th>
                            <th>Kết Quả</th>
                            <th>Nhận xét</th>
                        </tr> --}}
                        <tr>
                            <th rowspan="2" class="align-middle">STT</th>
                            <th rowspan="2" class="align-middle">Buổi thi</th>
                            <th rowspan="2" class="align-middle">Ngày thi</th>
                            <th rowspan="2" class="align-middle">Lần thi</th>
                            <th rowspan="2" class="align-middle">Học viên</th>
                            <th rowspan="2" class="align-middle" style="width: 100px">Ngày sinh</th>
                            <th rowspan="2" class="align-middle">CCCD</th>
                            <th rowspan="2" class="align-middle">SĐT</th>
                            <th rowspan="2" class="align-middle">SBD</th>
                            <th rowspan="2" class="align-middle">Khóa học</th>
                            <th rowspan="2" class="align-middle">Môn thi</th>
                            <th rowspan="2" class="align-middle">Khám SK</th>
                            <th rowspan="2" class="align-middle">Sân</th>
                            <th rowspan="2" class="align-middle">Đưa đón</th>
                            <th rowspan="2" class="align-middle">Kết quả</th>
                            <th colspan="3" class="align-middle">Xe chip</th>
                            <th rowspan="2" class="align-middle" style="width: 80px">Ghi chú</th>
                            <th rowspan="2" class="align-middle">Hành động</th>
                        </tr>
                        <tr>
                            <th class="align-middle">Giờ tặng</th>
                            <th class="align-middle">Giờ đăng ký</th>
                            <th class="align-middle">Tổng giờ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calendars->where('type', 'exam')->where('level', 2) as $index => $calendar)
                            @php
                                $session = match($calendar->time) {
                                    1 => 'Buổi sáng',
                                    2 => 'Buổi chiều',
                                    3 => 'Cả ngày',
                                    default => 'Không xác định',
                                };

                                $badgeClass = match($session) {
                                    'Buổi sáng' => 'bg-primary',
                                    'Buổi chiều' => 'bg-warning text-dark',
                                    'Cả ngày' => 'bg-success',
                                    default => 'bg-secondary',
                                };

                                $date = \Carbon\Carbon::parse($calendar->date ?? $calendar->date_start);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">{{ $session }}</span>
                                </td>
                                <td>{{ $date->format('d-m-Y') }}</td>
                                <td>
                                    @php
                                        $attemptNumber = $calendar['fields'][0]->attempt_number
                                    @endphp
                                    <span class="badge bg-secondary">Lần {{ $attemptNumber }}</span>
                                </td>
                                <td>{{ $student->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($student->dob)->format('d/m/Y') }}</td>
                                <td>{{ $student->identity_card }}</td>
                                <td>{{ $student->phone }}</td>
                                <td>{{ '--' }}</td>
                                <td>{{ $course->code }}</td>
                                <td>
                                    @if($calendar->fields && $calendar->fields->isNotEmpty())
                                        @foreach($calendar->fields as $field)
                                            <span class="badge bg-success">{{ $field->name ?? 'Không có tên field' }}</span>
                                        @endforeach
                                    @else
                                        <p>Không có dữ liệu môn thi</p>
                                    @endif
                                </td>
                                <td>{{ 'new' }}</td>
                                <td>
                                    @if (!empty($calendar->stadium_location))
                                    <a href="{{ $calendar->stadium_google_maps_url }}" target="_blank">{{ $calendar->stadium_location ?? 'N/A' }}</a>
                                    @else
                                    Không có sân tập
                                    @endif
                                </td>
                                <td>
                                    @if ($calendar->pivot->pickup === 1)
                                        <i style="font-size: 24px; color: green" class="mdi mdi-check-circle-outline"></i>
                                    @else
                                        <i style="font-size: 24px; color: red" class="mdi mdi-close-circle-outline"></i>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $uniqueStatuses = [];
                                
                                        
                                            $status = $calendar['fields'][0]['exam_all_status'] ?? null;
                                            if (!in_array($status, $uniqueStatuses)) {
                                                $uniqueStatuses[] = $status;
                                            }
                                        
                                    @endphp
                                
                                    @foreach ($uniqueStatuses as $status)
                                        @if ($status === 1)
                                            <span class="badge bg-success">Đỗ</span>
                                        @elseif ($status === 2)
                                            <span class="badge bg-danger">Trượt</span>
                                        @else
                                            <span class="badge bg-secondary">Chưa có kết quả</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ '--' }}</td>
                                <td>{{ '--' }}</td>
                                <td>{{ '--' }}</td>
                                <td>{{ $calendar->description }}</td>
                                <td class="d-flex">
                                    <a href="#" class="btn btn-warning ps-2 pe-2 pt-0 pb-0 me-2">
                                        <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                                    </a>
                                    <form action="#" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger ps-2 pe-2 pt-0 pb-0">
                                            <i class="mdi mdi-trash-can-outline fs-4"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            {{-- @foreach ($calendar->fields as $fieldIndex => $field)
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
                            @endforeach --}}
                        @empty
                            <tr>
                                <td colspan="20" class="text-center">Không có lịch thi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lịch sử nộp học phí -->
    <div class="card mb-4" id="history-tuition">
        <div class="card-header">Lịch Sử Nộp Học Phí
            <button 
                type="button" 
                class="btn btn-sm float-end" 
                style="color: #3b82f6; border: 1px solid #3b82f6;"
                data-bs-toggle="modal" 
                data-bs-target="#addFeeModal" 
                data-student-id="{{ $student->id }}" 
                data-student-name="{{ $student->name }} - {{ $student->student_code }}" 
                data-course-student-id="{{ $coursePivot->pivot->id }}" 
                data-course-code="{{ $course->code }}"
            >
                <span class="me-2">+</span> <span>Tạo Học Phí</span>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- <div class="col-12 col-sm-6 col-md-4">
                    <p class="text-primary">
                        <strong>Tổng học phí phải đóng:</strong>
                        {{ $coursePivot && $coursePivot->pivot ? number_format($coursePivot->pivot->tuition_fee) . ' VNĐ' : 'Chưa có thông tin' }}
                    </p>
                </div> --}}
                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <div class="col-12 col-sm-6 col-md-4">
                        <strong class="text-success">
                            Tổng Tiền Đã Nộp: {{ number_format($courseFees) ?? 0 }} VNĐ
                        </strong>
                    </div>
                </div>
                
                {{-- <div class="col-12 col-sm-6 col-md-4">
                    <p class="text-danger">
                        Tổng Nợ: {{ number_format($remainingFees) ?? 0 }} VNĐ
                    </p>
                </div> --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            {{-- <th>STT</th>
                            <th>Số Tiền Nộp</th>
                            <th>Ngày Nộp</th>
                            <th>Người Thu</th>
                            <th>Ghi Chú</th> --}}
                            <th>STT</th>
                            <th>Học viên</th>
                            <th>Ngày sinh</th>
                            <th>Số điện thoại</th>
                            <th>Loại thu</th>
                            <th>Khóa học</th>
                            <th>Số tiền</th>
                            <th>Ngày nộp</th>
                            <th>Ghi chú</th>
                            <th>Người thu</th>
                            <th>Tiền đã về công ty</th>
                            <th>Hành động</th>
                    </thead>
                    <tbody>
                        
                        @forelse($feesForCourse as $fee)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->dob->format('d/m/Y') }}</td>
                                <td>{{ $student->phone }}</td>
                                <td>{{ $fee->fee_type_vi }}</td>
                                <td>{{ $course->code }}</td>
                                <td>{{ number_format($fee->amount) }} VNĐ</td>
                                <td>{{ $fee->payment_date ? $fee->payment_date->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $fee->note }}</td>
                                <td>{{ $fee->collector_id ? $users->find($fee->collector_id)->name : 'N/A' }}</td>
                                <td>
                                    @if ($fee->is_received == 1)
                                        <span class="badge bg-success">Đã về công ty</span>
                                    @else
                                        <span class="badge bg-secondary">Chưa về công ty</span>
                                    @endif 
                                </td>
                                <td>
                                    <a href="#" class="btn btn-warning ps-2 pe-2 pt-0 pb-0">
                                        <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                                    </a>
                                    <form action="{{ route('fees.destroy', $fee->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm  ps-2 pe-2 pt-0 pb-0" onclick="return confirm('Are you sure?')">
                                            <i class="mdi mdi-trash-can-outline fs-4"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">Không có lịch sử nộp học phí</td>
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
    {{-- <div class="card mb-4">
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
    </div> --}}
    
    <!-- Modal thêm mới lịch -->
    @include('admin.calendars.add-calendar-modal')

@section('js')
<script>
    const course = {!! json_encode($course) !!};
    const rankings = {!! json_encode($rankings) !!};
    const ranking = rankings.find(item => item.id == course.ranking_id)
    console.log(ranking);
    
    if(ranking.vehicle_type == 0){
        $('.calendar-th').show()
        $('.calendar-lt').show()
        $('.calendar-sh').show()
    }
    else{
        $('.calendar-th').show()
        $('.calendar-cb').show()
        $('.calendar-lt').show()
        $('.calendar-exam-th').show()
        $('.calendar-tn').show()
        $('.calendar-sh').show()
    }
</script>
@endsection



    