@extends('layouts.admin')

@section('content')
{{-- <div class="container">
    <form action="{{ route('exam-schedules.update', $examSchedule) }}" method="POST">
        @method('PUT')
        @csrf

        <div class="mb-3">
            <label for="start_time" class="form-label">Thời gian bắt đầu</label>
            <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time', $examSchedule->start_time) }}">
            @error('start_time')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_time" class="form-label">Thời gian kết thúc</label>
            <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time', $examSchedule->end_time) }}">
            @error('end_time')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="stadium_id" class="form-label">Sân thi</label>
            <select name="stadium_id" class="form-select">
                @foreach($stadiums as $stadium)
                    <option value="{{ $stadium->id }}" {{ old('stadium_id', $examSchedule->stadium_id) == $stadium->id ? 'selected' : '' }}>
                        {{ $stadium->location }}
                    </option>
                @endforeach
            </select>
            @error('stadium_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Hạng thi</label>
            <div class="form-check">
                @foreach($rankings as $ranking)
                    <div>
                        <input type="checkbox" name="ranking_ids[]" value="{{ $ranking->id }}"
                            {{ in_array($ranking->id, old('ranking_ids', $examSchedule->rankings->pluck('id')->toArray())) ? 'checked' : '' }}
                            class="form-check-input" id="ranking_{{ $ranking->id }}">
                        <label class="form-check-label" for="ranking_{{ $ranking->id }}">
                            {{ $ranking->name }}
                        </label>
                    </div>
                @endforeach
            </div>
            @error('ranking_ids')
                <small class="text-danger d-block">{{ $message }}</small>
            @enderror
            @error('ranking_ids.*')
                <small class="text-danger d-block">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Môn thi</label>
            <div class="form-check">
                @foreach($examFields as $field)
                    <div>
                        <input type="checkbox" name="exam_field_ids[]" value="{{ $field->id }}"
                            {{ in_array($field->id, old('exam_field_ids', $examSchedule->examFields->pluck('id')->toArray())) ? 'checked' : '' }}
                            class="form-check-input" id="exam_field_{{ $field->id }}">
                        <label class="form-check-label" for="exam_field_{{ $field->id }}">
                            {{ $field->name }}
                        </label>
                    </div>
                @endforeach
            </div>
            @error('exam_field_ids')
                <small class="text-danger d-block">{{ $message }}</small>
            @enderror
            @error('exam_field_ids.*')
                <small class="text-danger d-block">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" class="form-control">{{ old('description', $examSchedule->description) }}</textarea>
            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="scheduled" {{ old('status', $examSchedule->status) == 'scheduled' ? 'selected' : '' }}>Đã lên lịch</option>
                <option value="canceled" {{ old('status', $examSchedule->status) == 'canceled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
</div> --}}


<!-- Modal Edit Kế Hoạch Thi -->
<div class="modal fade" id="examScheduleEditModal" tabindex="-1" aria-labelledby="examScheduleEditModal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow rounded-4">
            <div class="modal-header">
                <h5 class="modal-title">Tạo kế hoạch thi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>


            <form action="{{ route('exam-schedules.update', $examSchedule) }}" method="POST">
                @csrf
                <input type="hidden" name="modal_name" value="examScheduleEditModal">
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Thời gian bắt đầu</label>
                            <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time', $examSchedule->start_time) }}">
                            @error('start_time') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
        
                        <div class="col-md-6">
                            <label class="form-label">Thời gian kết thúc</label>
                            <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time', $examSchedule->end_time) }}">
                            @error('end_time') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
        
                    <div class="mb-3">
                        <label class="form-label">Sân thi</label>
                        <select name="stadium_id" class="form-select">
                            <option value="">Chọn sân thi</option>
                            @foreach($stadiums as $stadium)
                                <option value="{{ $stadium->id }}" {{ old('stadium_id', $examSchedule->stadium_id) == $stadium->id ? 'selected' : '' }}>
                                    {{ $stadium->location }}
                                </option>
                            @endforeach
                        </select>
                        @error('stadium_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
        
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Hạng thi</label>
                            @php
                                $selectedRankingIds = $examSchedule->rankings->pluck('id')->toArray();
                            @endphp
                            <select name="ranking_ids[]" id="ranking_ids" class="form-select select2" multiple>
                                @foreach($rankings as $ranking)
                                    <option value="{{ $ranking->id }}" {{ in_array($ranking->id, $selectedRankingIds) ? 'selected' : '' }}>
                                        {{ $ranking->name }}
                                    </option>
                                @endforeach
                            </select>
        
                            @error('ranking_ids') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
        
                        <div class="col-md-6">
                            <label class="form-label">Môn thi</label>
                            @php
                                $selectedExamFieldIds = $examSchedule->examFields->pluck('id')->toArray();
                            @endphp
                            <select name="exam_field_ids[]" id="exam_field_ids" class="form-select select2" multiple>
                                @foreach($examFields as $field)
                                    <option value="{{ $field->id }}" {{ in_array($field->id, $selectedExamFieldIds) ? 'selected' : '' }}>
                                        {{ $field->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('exam_field_ids') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
        
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control">{{ old('description', $examSchedule->description) }}</textarea>
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
                

    
@endsection
@section('js')
<script>
    // $(document).ready(function() {
    //     $('#ranking_ids').select2({
    //         placeholder: 'Chọn Hạng thi',
    //         width: '100%',
    //         allowClear: true
    //     });
    //     $('#exam_field_ids').select2({
    //         placeholder: 'Chọn Môn thi',
    //         width: '100%',
    //         allowClear: true
    //     });
    // });


    $(document).ready(function() {
        $('#ranking_ids').select2({
            placeholder: 'Chọn một tùy chọn',
            width: '100%',
        });
        $('#exam_field_ids').select2({
            placeholder: 'Chọn một tùy chọn',
            width: '100%',
        });
    });
</script>

@endsection