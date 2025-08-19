{{-- @extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-body position-relative">

        <!-- Nút Thêm -->
        <button
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#examScheduleModal"
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed shadow"
            style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 2%; z-index: 999;">
            +
        </button>

        <h4 class="mb-3">Danh sách kế hoạch thi</h4>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>Thời gian</th>
                    <th>Hạng thi</th>
                    <th>Môn thi</th>
                    <th>Sân thi</th>
                    <th>Mô tả</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($examSchedules as $schedule)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $schedule->start_time }}<br>{{ $schedule->end_time }}</td>
                        <td>
                            @foreach($schedule->rankings as $ranking)
                                <span class="badge bg-info text-dark mb-1">{{ $ranking->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @foreach($schedule->examFields as $field)
                                <span class="badge bg-secondary mb-1">{{ $field->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ $schedule->stadium->location ?? '-' }}</td>
                        <td>{{ $schedule->description }}</td>
                        <td>
                            <span class="badge {{ $schedule->status === 'scheduled' ? 'bg-success' : 'bg-danger' }}">
                                {{ $schedule->status === 'scheduled' ? 'Đã lên lịch' : 'Đã hủy' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('exam-schedules.show', $schedule) }}" class="btn btn-sm btn-info mb-1">Xem</a>
                            <button  
                                type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#editExamScheduleModal"
                                class="btn btn-sm btn-warning mb-1">
                                Sửa
                            </button>
                            <form action="{{ route('exam-schedules.destroy', $schedule) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Xác nhận xóa?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger mb-1">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Không có kế hoạch thi nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tạo Kế Hoạch Thi -->
<div class="modal fade" id="examScheduleModal" tabindex="-1" aria-labelledby="examScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow rounded-4">
            <div class="modal-header">
                <h5 class="modal-title">Tạo kế hoạch thi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <form action="{{ route('exam-schedules.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Thời gian bắt đầu</label>
                            <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time') }}">
                            @error('start_time') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Thời gian kết thúc</label>
                            <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time') }}">
                            @error('end_time') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sân thi</label>
                        <select name="stadium_id" class="form-select">
                            <option value="">Chọn sân thi</option>
                            @foreach($stadiums as $stadium)
                                <option value="{{ $stadium->id }}" {{ old('stadium_id') == $stadium->id ? 'selected' : '' }}>
                                    {{ $stadium->location }}
                                </option>
                            @endforeach
                        </select>
                        @error('stadium_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Hạng thi</label>
                            <select name="ranking_ids[]" class="form-select" multiple>
                                @foreach($rankings as $ranking)
                                    <option value="{{ $ranking->id }}" {{ in_array($ranking->id, old('ranking_ids', [])) ? 'selected' : '' }}>
                                        {{ $ranking->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ranking_ids') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Môn thi</label>
                            <select name="exam_field_ids[]" class="form-select" multiple>
                                @foreach($examFields as $field)
                                    <option value="{{ $field->id }}" {{ in_array($field->id, old('exam_field_ids', [])) ? 'selected' : '' }}>
                                        {{ $field->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('exam_field_ids') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                        @error('description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Đã lên lịch</option>
                            <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                        @error('status') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu kế hoạch</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection --}}
