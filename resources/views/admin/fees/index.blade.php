@extends('layouts.admin')
@section('css')
    <link href="{{ asset('assets/css/nouislider.min.css') }}" rel="stylesheet">
    <style>
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


@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <button type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" data-bs-toggle="modal" data-bs-target="#feeModal">
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <strong>Thêm mới nộp tiền</strong>
        </button>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('fees.index') }}" class="row g-2 mb-4">
            <div class="col-12 col-md-12 col-lg-12 col-xl-12 row mt-2">
                <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3 position-relative">
                    <label class="form-label fw-bold">Từ ngày</label>
                    <input type="text" placeholder="dd/mm/yyyy" name="from_date" class="form-control real-date" autocomplete="off" value="{{ request('from_date') ? \Carbon\Carbon::parse(request('from_date'))->format('d/m/Y') : '' }}">
                </div>
                <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3 position-relative">
                    <label class="form-label fw-bold">Đến ngày</label>
                    <input type="text" placeholder="dd/mm/yyyy" name="to_date" class="form-control real-date" autocomplete="off" value="{{ request('to_date') ? \Carbon\Carbon::parse(request('to_date'))->format('d/m/Y') : '' }}">
                </div>
                {{-- <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label class="form-label fw-bold">Số tiền</label>
                    <div id="amount-slider"></div>
                        <input type="hidden" name="min_amount" id="min_amount" value="{{ request('min_amount') }}">
                        <input type="hidden" name="max_amount" id="max_amount" value="{{ request('max_amount') }}">
                    <div class="mt-2">
                        <span id="amount-slider-value"></span>
                    </div>
                </div> --}}
                <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                    <label class="form-label fw-bold">Mã khóa học</label>
                    {{-- <input type="text" name="course_id" class="form-control" placeholder="ID khóa học" value="{{ request('course_id') }}"> --}}
                    <select name="course_id" id="course_id" class="form-select">
                        <option value=""></option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }} {{ old('course_id') == $course->id ? 'selected' : '' }}">{{ $course->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                    <label class="form-label fw-bold">Mã học viên</label>
                    <select name="student_id" id="student_id" class="form-select">
                        <option value=""></option>
                        @isset($course)
                            @foreach ($students as $student)
                                <option value="{{ $student->id }} {{ old('student_id') == $course->id ? 'selected' : '' }}">{{ $student->student_code }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="fee_type" class="form-label">Loại Thu</label>
                    <select name="fee_type" id="fee_type" class="form-select">
                        <option value="">Chọn loại thu</option>
                        <option value="1">Học phí</option>
                        <option value="2">Lệ phí đăng ký xe chip</option>
                        <option value="3">Lệ phí cọc chip</option>
                        <option value="4">Lệ phí đưa đón</option>
                        <option value="5">Hết môn lý thuyết</option>
                        <option value="6">Hết môn thực hành</option>
                        <option value="7">Thi tốt nghiệp</option>
                        <option value="8">Khác</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label class="form-label fw-bold">Tiền đã về công ty</label>
                    <select name="is_received" class="form-select">
                        <option value="">-- Trạng thái --</option>
                        <option value="1" {{ request('is_received') == '1' ? 'selected' : '' }}>Đã nhận</option>
                        <option value="0" {{ request('is_received') == '0' ? 'selected' : '' }}>Chưa nhận</option>
                    </select>
                </div>
                <div class="col-12 col-md-4 col-lg-4 col-xl-4">
                    <label for="">
                        <b>&nbsp</b>
                    </label>
                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mb-1">
                            <b>Tìm Kiếm</b>
                        </button>
                        <a href="{{ route('fees.index') }}" class="btn btn-outline-danger mb-1 ms-2" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div href="{{ route('fees.create') }}" 
                class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
                style="width: 40px; height: 40px; font-size: 30px; bottom: 3%; right: 60px; z-index: 99;" data-bs-toggle="modal" data-bs-target="#feeModal">
                +
            </div>
        </div>
        <div class="alert alert-primary d-flex align-items-center" role="alert">
            <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-20 me-1"></iconify-icon>
            <div class="lh-1"><strong>Tổng tiền đã nộp: </strong><strong>{{ number_format($totalAmount, 0, ',', '.') }} VNĐ</strong></div>
        </div>
        <div class="table-responsive">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Học viên</th>
                            <th>Ngày sinh</th>
                            <th>Số điện thoại</th>
                            <th>Loại thu</th>
                            <th>Khóa học</th>
                            <th>Số tiền</th>
                            <th>Ngày nộp</th>
                            <th>Giờ giao dịch</th>
                            <td>Ghi chú</td>
                            <td>Người thu</td>
                            <th>Tiền đã về công ty</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $count = 1; @endphp
                        @foreach ($feesGrouped as $date => $hourlyGroups)
                            @php $printedDate = false; @endphp
                
                            @foreach ($hourlyGroups as $time => $feesAtTime)
                                @php $printedTime = false; @endphp
                                {{-- @dd($feesAtTime) --}}
                                @foreach ($feesAtTime as $fee)
                                    <tr>
                                        {{-- Cột ngày (chỉ in 1 lần) --}}
                                        <td>{{ $count++ }}</td>
                                        <td class="text-nowrap">{{ $fee->student->name ?? '' }} - {{ $fee->student->student_code ?? '' }}</td>
                                        <td class="text-nowrap">{{ $fee->student?->dob ? $fee->student->dob->format('d-m-Y') : '' }}</td>
                                        <td class="text-nowrap">{{ $fee->student?->phone ? $fee->student->phone : '' }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $fee->fee_type_label }}</span>
                                        </td>
                                        <td class="text-nowrap">{{ $fee->courseStudent?->course->code }}</td>
                                        <td class="text-nowrap">{{ number_format($fee->amount, 0, ',', '.') }} VNĐ</td>
                                        @if (!$printedDate)
                                            <td rowspan="{{ $hourlyGroups->flatten()->count() }}">
                                                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                            </td>
                                            @php $printedDate = true; @endphp
                                        @endif
                
                                        {{-- Cột giờ (chỉ in 1 lần theo nhóm giờ) --}}
                                        @if (!$printedTime)
                                            <td rowspan="{{ $feesAtTime->count() }}">{{ $time }}</td>
                                            @php $printedTime = true; @endphp
                                        @endif
                                        <td class="text-nowrap">
                                            {{ $fee->note?? '' }}
                                        </td>
                                        <td class="text-nowrap">
                                            {{ $collectors->firstWhere('id', $fee->collector_id)->name ?? '--' }}
                                        </td>
                                        
                                        <td>
                                            <span class="badge {{ $fee->is_received ? 'bg-success' : 'bg-danger' }}">
                                                {{ $fee->is_received ? 'Đã về công ty' : 'Chưa về công ty' }}
                                            </span>
                                        </td>
                                        <td style="width: 130px; min-width: 130px;">
                                            <a 
                                                {{-- href="{{ route('fees.edit', $fee->id) }}"  --}}
                                                class="btn btn-warning btn-sm  ps-2 pe-2 pt-0 pb-0"
                                                data-id="{{ $fee->id }}"
                                                data-course="{{ $fee->course?->code }}"
                                                data-fee-type="{{ $fee->fee_type }}"
                                                data-amount="{{ $fee->amount }}"
                                                data-payment-date="{{ $fee->payment_date }}"
                                                data-payment-time="{{ $time }}"
                                                data-collectors="{{  $fee->collector_id }}"
                                                data-is-received="{{ $fee->is_received }}"
                                                data-note="{{ $fee->note }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editFeeModal"
                                                data-course_student_id="{{ $fee->course_student_id }}"
                                            >
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
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal thêm mới phí -->
<div class="modal fade" id="feeModal" tabindex="-1" aria-labelledby="feeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feeModalLabel">Thêm lịch sử học phí</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            
            <div class="modal-body">
            <form id="formFeeModal" action="{{ route('fees.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="modal_name" value="feeModal">
                <div class="row">
                    @php
                        $isAddError = session()->get('_old_input') && request()->routeIs('fees.store');
                    @endphp
                    <div class="form-group col-12 col-md-6 mx-auto">
                        <label for="course_student_id" class="form-label">Chọn học viên - khóa học</label>
                        <select class="form-select" name="course_student_id" id="course_student_id">
                            <option value="">Chọn khóa học của học viên</option>
                            @foreach ($courseStudents as $courseStudent)
                                <option value="{{ $courseStudent->id }}" {{$isAddError && old('course_student_id') == $courseStudent->id ?  'selected' : '' }}>{{ $courseStudent->student->name }} - {{ $courseStudent->course->code }}</option>
                            @endforeach
                        </select>
                        @error('course_student_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-12 col-md-6 mx-auto">
                        <label for="fee_type" class="form-label">Loại Thu</label>
                        <select name="fee_type" id="fee_type" class="form-select" required>
                            <option value="">Chọn loại thu</option>
                            @foreach (App\Models\Fee::getTypeOptions() as $key => $label)
                                <option value="{{ $key }}" {{ $isAddError && old('fee_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('fee_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group col-12 col-md-6 mx-auto">
                        <label for="amount" class="form-label">Số Tiền</label>
                        <input type="text" class="form-control currency-input" name="amount" id="amount" value="{{ $isAddError ? old('amount') : '' }}" required>
                        @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-12 col-md-6 mx-auto">
                        <label for="payment_date" class="form-label">Ngày nộp</label>
                        <input type="datetime-local" class="form-control" name="payment_date" id="payment_date" value="{{ $isAddError ? old('payment_date') :'' }}" required>
                        @error('payment_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-12 col-md-6 mx-auto">
                        <label for="collector_id" class="form-label">Người thu</label>
                        <select class="form-select" name="collector_id" id="collector_id">
                            <option value="">Chọn người thu</option>
                            @foreach ($collectors as $collector)
                                <option value="{{ $collector->id }}" {{ $isAddError && old('collector_id') == $collector->id ? 'selected' : '' }}>
                                    {{ $collector->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_student_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-12 col-md-6 mx-auto">
                        <label for="note" class="form-label">Ghi Chú</label>
                        <textarea class="form-control" name="note" id="note" rows="2">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_received" id="is_received" value="1" {{ old('is_received') == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_received">Tiền đã về công ty</label>
                            @error('is_received')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>
            </form>
            </div>
    
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-2"></i> <span>Hủy</span>
                </button>
                <button type="submit" class="btn btn-primary" form="formFeeModal">
                    <i class="mdi mdi-content-save-outline me-2"></i> <span>Lưu</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal sửa --}}
<div class="modal fade" id="editFeeModal" tabindex="-1" aria-labelledby="editFeeModal" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa học phí</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
    
            <div class="modal-body">
                <form id="editFeeForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="modal_name" value="editFeeModal">
                    <div class="row">
                        <div class="form-group col-12 col-md-6 mx-auto">
                            <label for="course_student_id" class="form-label">Chọn học viên - khóa học</label>
                            <select class="form-select" id="edit_course_student_id" name="course_student_id" required>
                                <option value="">Chọn khóa học của học viên</option>
                                @foreach ($courseStudents as $courseStudent)
                                    <option data-students={{ $courseStudent }}  value="{{ $courseStudent->id }}"  {{ $courseStudent->id ?  'selected' : '' }}>{{ $courseStudent->student->name }} - {{ $courseStudent->course->code }}</option>
                                @endforeach
                            </select>
                            @error('course_student_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-12 col-md-6 mx-auto">
                            <label for="fee_type" class="form-label">Loại Thu</label>
                            <select name="fee_type" id="edit_fee_type" class="form-select" required>
                                <option value="">Chọn loại thu</option>
                                @foreach (App\Models\Fee::getTypeOptions() as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('fee_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group col-12 col-md-6 mx-auto">
                            <label for="amount" class="form-label">Số Tiền</label>
                            <input type="text" id="edit_amount" class="form-control currency-input" name="amount" required>
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-12 col-md-6 mx-auto">
                            <label for="payment_date" class="form-label">Ngày nộp</label>
                            <input type="datetime-local" class="form-control" name="payment_date" id="edit_payment_date" required>
                            @error('payment_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-12 col-md-6 mx-auto">
                            <label for="collector_id" class="form-label">Người thu</label>
                            <select class="form-select" name="collector_id" id="edit_collector_id">
                                <option value="">Chọn người thu</option>
                                @foreach ($collectors as $collector)
                                    <option value="{{ $collector->id }}">{{ $collector->name }}</option>
                                @endforeach
                            </select>
                            @error('course_student_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-12 col-md-6 mx-auto">
                            <label for="note" class="form-label">Ghi Chú</label>
                            <textarea class="form-control" name="note" id="edit_note" rows="2">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_received" id="edit_is_received" value="1">
                                <label class="form-check-label" for="is_received">Tiền đã về công ty</label>
                                @error('is_received')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </form>
            </div>
    
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-2"></i> <span>Hủy</span>
                </button>
                <button type="submit" class="btn btn-primary" form="editFeeForm">
                    <i class="mdi mdi-content-save-outline me-2"></i> <span>Lưu</span>
                </button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')
{{-- @if ($errors->has('course_student_id') || $errors->has('fee_type') || $errors->has('amount'))
    <script>
        const feeModal = new bootstrap.Modal(document.getElementById('feeModal'));
        feeModal.show();
    </script>
@endif --}}


{{-- fill data modal edit --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editFeeModal = document.getElementById('editFeeModal');
    
        editFeeModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const feeId = button.getAttribute('data-id');
            const courseCode = button.getAttribute('data-course');
            const feeType = button.getAttribute('data-fee-type');
            const amount = button.getAttribute('data-amount');
            const paymentDate = button.getAttribute('data-payment-date');
            const paymentTime = button.getAttribute('data-payment-time');
            
            const collectors = button.getAttribute('data-collectors');
            const note = button.getAttribute('data-note');
            const isReceived = button.getAttribute('data-is-received');
            const courseStudentId = JSON.parse(button.getAttribute('data-course_student_id'));
            
            // Đổi action form đúng feeId
            const form = editFeeModal.querySelector('form');
            form.action = `/fees/${feeId}`;
    
            // Set course select (nên truyền thêm data-course-id sẽ chính xác hơn)
            const courseSelect = editFeeModal.querySelector('#edit_course_student_id');
            Array.from(courseSelect.options).forEach(option => {
                option.selected = option.text === courseCode;
            });
    
            editFeeModal.querySelector('#edit_fee_type').value = feeType;
            editFeeModal.querySelector('#edit_amount').value = amount;
            editFeeModal.querySelector('#edit_course_student_id').value = courseStudentId;
    
            if (paymentDate && paymentTime) {
                const dateOnly = paymentDate.split(' ')[0]; // "2025-08-12"
                const datetimeValue = `${dateOnly}T${paymentTime}`; // "2025-08-12T08:21"
                editFeeModal.querySelector('#edit_payment_date').value = datetimeValue;
            }

    
            editFeeModal.querySelector('#edit_collector_id').value = collectors;
            editFeeModal.querySelector('#edit_note').value = note ?? '';
            editFeeModal.querySelector('#edit_is_received').checked = isReceived === '1';
        });
    });
</script>
    


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const feeModal = document.getElementById('feeModal');

        feeModal.addEventListener('hidden.bs.modal', function () {
            const form = feeModal.querySelector('form');
            form.reset();

            form.querySelectorAll('.text-danger, .invalid-feedback').forEach(el => el.remove());

            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            form.querySelectorAll('select').forEach(select => {
                select.value = '';
                select.dispatchEvent(new Event('change'));
            });

            form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);

            const collapse = feeModal.querySelector('#new-course-form');
            if (collapse && collapse.classList.contains('show')) {
                bootstrap.Collapse.getInstance(collapse)?.hide();
            }
            form.querySelectorAll('input, textarea').forEach(el => {
                if (el.type === 'checkbox' || el.type === 'radio') {
                    el.checked = false;
                } else if (el.type === 'file') {
                    el.value = null;
                } else {
                    el.value = '';
                }
            });
        });
    });
</script>
<script>
    //xử lý modal thêm mới và lấy dữ liệu
    let courseStudents = @json($courseStudents);
    let courses = @json($courses);

    document.getElementById('student_id').addEventListener('change', function() {
        let studentId = this.value;
        let courseSelect = document.getElementById('course_student_id');
        let addCourseSelect = document.getElementById('course_id');

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
<script src="{{ asset('assets/js/nouislider.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sliderFee = document.getElementById('amount-slider');
        if (sliderFee) {
            noUiSlider.create(sliderFee, {
                start: [
                    parseInt(document.getElementById('min_amount').value || 0),
                    parseInt(document.getElementById('max_amount').value || 50000000)
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

            const minInput = document.getElementById('min_amount');
            const maxInput = document.getElementById('max_amount');
            const displayValue = document.getElementById('amount-slider-value');

            sliderFee.noUiSlider.on('update', function (values) {
                const min = values[0];
                const max = values[1];
                minInput.value = min;
                maxInput.value = max;
                displayValue.innerText = `${Number(min).toLocaleString()} - ${Number(max).toLocaleString()} VND`;
            });
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('#student_id').select2({
            placeholder: "Chọn học viên",
            allowClear: true
        });
        $('#course_id').select2({
            placeholder: "Chọn khóa học",
            allowClear: true
        });
        $('#course_student_id').select2({
            placeholder: "Chọn khóa học của học viên",
            allowClear: true
        });
        $('#collector_id').select2({
            placeholder: "Chọn người thu",
            allowClear: true
        });
    });
</script>
@endsection