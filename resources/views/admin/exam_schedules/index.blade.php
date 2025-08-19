@extends('layouts.admin')
@section('content')

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <button type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#examScheduleModal">
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Thêm mới</span>
        </button>
    </div>
@endsection

<style>
    .table-container {
        overflow-x: auto;
        width: 100%;
        max-height: 70vh;
    }
</style>

<div class="card">
    <div class="card-body">

        <div class="filter-options mb-3">
            
            <form id="examSchedulesFilterForm" class="row" method="GET">
                <div class="col-6 col-lg-3 col-md-4 mb-4 position-relative">
                    <label class="form-label fw-bold">Ngày bắt đầu</label>
                    <input 
                        type="text" 
                        placeholder="dd/mm/yyyy"
                        name="start_date" 
                        value="{{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '' }}"
                        class="form-control real-date" autocomplete="off" 
                    >
                </div>
                <div class="col-6 col-lg-3 col-md-4 mb-4 position-relative">
                    <label class="form-label fw-bold">Ngày kết thúc</label>
                    <input 
                        type="text" 
                        placeholder="dd/mm/yyyy"
                        name="end_date" 
                        value="{{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : '' }}"
                        class="form-control real-date" autocomplete="off" 
                    >
                </div>

                <div class="col-12 col-lg-3 col-md-4 mb-4">
                    <label for="stadium_id" class="form-label">Sân thi</label>
                    <select name="stadium_id" id="stadium_id" class="form-select select2">
                        <option value="">Chọn sân</option>
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

                <div class="col-12 col-lg-3 col-md-12 mb-2">
                    <label for="">&nbsp;</label>
                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mb-1">
                            <b>Tìm Kiếm</b>
                        </button>
                        <div class="ms-2">
                            <a href="{{ route('exam-schedules.index') }}" class="btn btn-outline-danger mb-1" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <!-- Nút Thêm -->
        <button
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#examScheduleModal"
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed shadow"
            style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 2%; z-index: 999;">
            +
        </button>

        {{-- <h4 class="mb-3">Danh sách kế hoạch thi</h4> --}}
        <div class="table-container" id="bottom-scroll top-scroll">
            <table class="table mt-3 table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>STT</th>
                        <th>Thời gian bắt đầu</th>
                        <th>Thời gian kết thúc</th>
                        <th>Hạng thi</th>
                        <th>Sân thi</th>
                        <th>Môn thi</th>
                        <th>Mô tả</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($examSchedules as $schedule)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('d/m/Y') }}</td>
                            <td>{{\Carbon\Carbon::parse($schedule->end_time)->format('d/m/Y') }}</td>
                            <td>
                                @foreach($schedule->rankings as $ranking)
                                    <span class="badge bg-info text-dark mb-1">{{ $ranking->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $schedule->stadium->location ?? '-' }}</td>
                            <td>
                                @foreach($schedule->examFields as $field)
                                    <span class="badge bg-secondary mb-1">{{ $field->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $schedule->description }}</td>
                            <td>
                                <span class="badge {{ $schedule->status === 'scheduled' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $schedule->status === 'scheduled' ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    {{-- <a href="{{ route('exam-schedules.show', $schedule) }}" class="btn btn-sm btn-info mb-1 ps-2 pe-2 pt-0 pb-0">
                                        <i class="mdi mdi-eye-outline fs-4"></i>
                                    </a> --}}
                                    {{-- <a href="{{ route('exam-schedules.edit', $schedule) }}" class="btn btn-sm btn-warning mb-1 ps-2 pe-2 pt-0 pb-0">
                                        <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                                    </a> --}}
                                    <button 
                                        type="button"
                                        class="btn btn-sm btn-warning edit-btn mb-1 ps-2 pe-2 pt-0 pb-0"
                                        data-id="{{ $schedule->id }}"
                                        data-start_time="{{ \Carbon\Carbon::parse($schedule->start_time)->format('Y-m-d\TH:i') }}"
                                        data-end_time="{{ \Carbon\Carbon::parse($schedule->end_time)->format('Y-m-d\TH:i') }}"
                                        data-stadium_id="{{ $schedule->stadium->id }}"
                                        data-description="{{ $schedule->description }}"
                                        data-status="{{ $schedule->status }}"
                                        data-rankings='@json($schedule->rankings->pluck("id"))'
                                        data-examfields='@json($schedule->examFields->pluck("id"))'
                                    >
                                        <i class="mdi mdi-square-edit-outline fs-4"></i>
                                    </button>
                                
                                    <form action="{{ route('exam-schedules.destroy', $schedule) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Xác nhận xóa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger mb-1 ps-2 pe-2 pt-0 pb-0">
                                            <i class="mdi mdi-trash-can-outline fs-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Không có kế hoạch thi nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tạo Kế Hoạch Thi -->
<div class="modal fade" id="examScheduleModal" tabindex="-1" aria-labelledby="examScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow rounded-4">
            <div class="modal-header">
                <h5 class="modal-title">Tạo kế hoạch thi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <form id="formExamScheduleModal" action="{{ route('exam-schedules.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="modal_name" value="examScheduleModal">
                    <div class="row mb-3">
                        <div class="col-md-6 position-relative">
                            <label class="form-label">Thời gian bắt đầu</label>
                            <input 
                                type="text" 
                                placeholder="dd/mm/yyyy"
                                name="start_time" 
                                class="form-control real-date" autocomplete="off" 
                                value="{{ old('start_time') ? \Carbon\Carbon::parse(old('start_time'))->format('d/m/Y') : '' }}"
                            >
                            @error('start_time') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 position-relative">
                            <label class="form-label">Thời gian kết thúc</label>
                            <input 
                                type="text" 
                                placeholder="dd/mm/yyyy"
                                name="end_time" 
                                class="form-control real-date" autocomplete="off" 
                                value="{{ request('end_time') ? \Carbon\Carbon::parse(request('end_time'))->format('d/m/Y') : '' }}"
                            >
                            @error('end_time') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="card">
                        <div class="row g-3 mb-3 card-body">
                            <label class="form-label">Hạng thi</label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach($rankings as $ranking)
                                    <div class="form-check" style="min-width: 150px;">
                                        <input 
                                            type="checkbox" 
                                            name="ranking_ids[]" 
                                            value="{{ $ranking->id }}" 
                                            id="ranking_{{ $ranking->id }}"
                                            class="form-check-input"
                                            {{ in_array($ranking->id, old('ranking_ids', [])) ? 'checked' : '' }}
                                        >
                                        <label for="ranking_{{ $ranking->id }}" class="form-check-label">
                                            {{ $ranking->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('ranking_ids') 
                                <div class="text-danger mt-1">{{ $message }}</div> 
                            @enderror
                        </div>
                    </div>

                    <div class="card">
                        <div class="row g-3 mb-3 card-body">
                            <label class="form-label">Môn thi</label>
                            <div class="form-check">
                                @foreach($examFields as $field)
                                    <div class="form-check form-check-inline">
                                        <input 
                                            type="checkbox" 
                                            name="exam_field_ids[]" 
                                            value="{{ $field->id }}" 
                                            id="exam_field_{{ $field->id }}"
                                            class="form-check-input"
                                            {{ in_array($field->id, old('exam_field_ids', [])) ? 'checked' : '' }}
                                        >
                                        <label for="exam_field_{{ $field->id }}" class="form-check-label">
                                            {{ $field->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('exam_field_ids') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6 position-relative">
                            <label class="form-label">Sân thi</label>
                            <select name="stadium_id" id="select_stadium_id" class="form-select">
                                <option value="">Chọn sân thi</option>
                                @foreach($stadiums as $stadium)
                                    <option value="{{ $stadium->id }}" {{ old('stadium_id') == $stadium->id ? 'selected' : '' }}>
                                        {{ $stadium->location }}
                                    </option>
                                @endforeach
                            </select>
                            @error('stadium_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 position-relative">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Đã lên lịch</option>
                                <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                            @error('status') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                        @error('description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-2"></i> <span>Hủy</span>
                </button>
                <button type="submit" class="btn btn-primary" form="formExamScheduleModal">
                    <i class="mdi mdi-content-save-outline me-2"></i> <span>Lưu</span>
                </button>
            </div>
        </div>
    </div>
</div>



<!-- Modal Edit Kế Hoạch Thi -->
<div class="modal fade" id="examScheduleEditModal" tabindex="-1" aria-labelledby="examScheduleEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow rounded-4">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa kế hoạch thi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formExamScheduleEditModal" method="POST" id="editExamScheduleForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="modal_name" value="examScheduleEditModal">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Thời gian bắt đầu</label>
                            <input type="datetime-local" name="start_time" id="edit_start_time" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Thời gian kết thúc</label>
                            <input type="datetime-local" name="end_time" id="edit_end_time" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sân thi</label>
                        <select name="stadium_id" id="edit_stadium_id" class="form-select">
                            <option value="">Chọn sân thi</option>
                            @foreach($stadiums as $stadium)
                                <option value="{{ $stadium->id }}">{{ $stadium->location }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="card">
                        <div class="row g-3 mb-3 card-body">
                            <label class="form-label">Hạng thi</label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach($rankings as $ranking)
                                    <div class="form-check" style="min-width: 150px;">
                                        <input 
                                            type="checkbox" 
                                            name="ranking_ids[]" 
                                            value="{{ $ranking->id }}" 
                                            id="edit_ranking_{{ $ranking->id }}"
                                            class="form-check-input edit_ranking_checkbox"
                                        >
                                        <label for="edit_ranking_{{ $ranking->id }}" class="form-check-label">
                                            {{ $ranking->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('ranking_ids') 
                                <div class="text-danger mt-1">{{ $message }}</div> 
                            @enderror
                        </div>
                    </div>

                    <div class="card">
                        <div class="row g-3 mb-3 card-body">
                            <label class="form-label">Môn thi</label>
                            <div class="form-check">
                                @foreach($examFields as $field)
                                    <div class="form-check form-check-inline">
                                        <input 
                                            type="checkbox" 
                                            name="exam_field_ids[]" 
                                            value="{{ $field->id }}" 
                                            id="edit_exam_field_{{ $field->id }}"
                                            class="form-check-input edit_exam_field_checkbox"
                                        >
                                        <label for="edit_exam_field_{{ $field->id }}" class="form-check-label">
                                            {{ $field->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('exam_field_ids') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" id="edit_description" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" id="edit_status" class="form-select">
                            <option value="scheduled">Đã lên lịch</option>
                            <option value="canceled">Đã hủy</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-2"></i> <span>Hủy</span>
                </button>
                <button type="submit" class="btn btn-primary" form="formExamScheduleEditModal">
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
        $('#ranking_ids').select2({
            placeholder: 'Chọn Hạng thi',
            width: '100%',
            dropdownParent: $('#examScheduleModal')
        });
        $('#exam_field_ids').select2({
            placeholder: 'Chọn Môn thi',
            width: '100%',
            dropdownParent: $('#examScheduleModal')
        });
        $('#stadium_id').select2({
            placeholder: 'Chọn sân',
            width: '100%',
            allowClear: true
        });
        $('#edit_stadium_id').select2({
            placeholder: 'Chọn sân',
            width: '100%',
            allowClear: true
        });
        $('#select_stadium_id').select2({
            placeholder: 'Chọn sân',
            width: '100%',
            allowClear: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        // $('#edit_ranking_ids, #edit_exam_field_ids').select2({
        //     width: '100%',
        // });

        $('.edit-btn').click(function() {
            const id = $(this).data('id');
            const start_time = $(this).data('start_time');
            const end_time = $(this).data('end_time');
            const stadium_id = $(this).data('stadium_id');
            const description = $(this).data('description');
            const status = $(this).data('status');
            const rankings = $(this).data('rankings');
            const examfields = $(this).data('examfields');
            
            $('#edit_start_time').val(start_time);
            $('#edit_end_time').val(end_time);
            $('#edit_description').val(description);
            $('#edit_status').val(status);

            // Clear all checkboxes first
            $('.edit_exam_field_checkbox, .edit_ranking_checkbox').prop('checked', false);

            // Set checked for exam fields
            examfields.forEach(function(id) {
                $('#edit_exam_field_' + id).prop('checked', true);
            });

            // Set checked for rankings
            rankings.forEach(function(id) {
                $('#edit_ranking_' + id).prop('checked', true);
            });
            $('#edit_stadium_id').val(stadium_id).trigger('change');

            $('#editExamScheduleForm').attr('action', `/admin/exam-schedules/${id}`);

            new bootstrap.Modal(document.getElementById('examScheduleEditModal')).show();
        });

    });

</script>
@endsection
