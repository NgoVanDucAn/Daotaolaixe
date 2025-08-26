@extends('layouts.admin')

@section('title', 'Thêm mới khóa học')
@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('courses.index'),
            'title' => 'Quay về trang quản lý khóa học'
        ])
    </div>
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('courses.store') }}" method="POST">
            @csrf
            {{-- <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code" class="form-label">Mã khóa học:</label>
                        <input type="text" name="code" class="form-control" placeholder="Mã khóa học" value="{{ old('code') }}" required>
                        @error('code') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="shlx_course_id" class="form-label">ID khóa học trên hệ thống sát hạch:</label>
                        <input type="number" name="shlx_course_id" class="form-control" placeholder="Mã khóa học" value="{{ old('shlx_course_id') }}">
                        @error('shlx_course_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="course_system_code" class="form-label">Mã khóa học trên hệ thống sát hạch:</label>
                        <input type="text" name="course_system_code" class="form-control" placeholder="Mã khóa học" value="{{ old('course_system_code') }}">
                        @error('course_system_code') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="curriculum_id" class="form-label">Giáo trình:</label>
                        <select name="curriculum_id" class="form-control">
                            <option value="">-- Chọn giáo trình --</option>
                            @foreach($curriculums as $curriculum)
                                <option value="{{ $curriculum->id }}" {{ old('curriculum_id') == $curriculum->id ? 'selected' : '' }}>{{ $curriculum->name }}</option>
                            @endforeach
                        </select>
                        @error('curriculum_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="ranking_id" class="form-label">Hạng GPLX:</label>
                        <select name="ranking_id" class="form-control" required>
                            <option value="">-- Chọn hạng GPLX --</option>
                            @foreach($rankings as $ranking)
                                <option value="{{ $ranking->id }}" {{ old('ranking_id') == $ranking->id ? 'selected' : '' }}>
                                    {{ $ranking->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('ranking_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="number_bc" class="form-label">Số BC:</label>
                        <input type="text" name="number_bc" class="form-control" value="{{ old('number_bc') }}" required>
                        @error('number_bc') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="date_bci" class="form-label">Ngày BC:</label>
                        <input type="date" placeholder="dd/mm/yyyy"name="date_bci" class="form-control" value="{{ old('date_bci') }}" required>
                        @error('date_bci') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="start_date" class="form-label">Khai giảng:</label>
                        <input type="date" placeholder="dd/mm/yyyy"name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                        @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="form-label">Bế giảng:</label>
                        <input type="date" placeholder="dd/mm/yyyy"name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                        @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dat_date" class="form-label">Ngày học DAT:</label>
                        <input type="text" name="dat_date" class="form-control" value="{{ old('dat_date') }}" required>
                        @error('dat_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="cabin_date" class="form-label">Ngày học Cabin:</label>
                        <input type="text" name="cabin_date" class="form-control" value="{{ old('cabin_date') }}" required>
                        @error('cabin_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="decision_kg" class="form-label">Quyết định khóa học:</label>
                        <input type="text" name="decision_kg" class="form-control" value="{{ old('decision_kg') }}" required>
                        @error('decision_kg') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="tuition_fee" class="form-label">Học phí:</label>
                        <input type="number" name="tuition_fee" class="form-control" value="{{ old('tuition_fee') }}" required>
                        @error('tuition_fee') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lĩnh vực thi</label>
                        <div>
                            <input type="checkbox" id="selectAllExamFields" onclick="toggleCheckboxes('exam_fields', 'selectAllExamFields')" /> Chọn tất cả
                        </div>
                        <div class="row">
                            @foreach($examFields as $field)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="exam_fields[]" value="{{ $field->id }}" id="exam_field_{{ $field->id }}"
                                            {{ (is_array(old('exam_fields')) && in_array($field->id, old('exam_fields'))) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="exam_field_{{ $field->id }}">{{ $field->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('exam_fields') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Môn học</label>
                        <div>
                            <input type="checkbox" id="selectAllLearningFields" onclick="toggleCheckboxes('learning_fields', 'selectAllLearningFields')" /> Chọn tất cả
                        </div>
                        <div class="row">
                            @foreach($learningFields as $field)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="learning_fields[]" value="{{ $field->id }}" id="learning_field_{{ $field->id }}"
                                            {{ (is_array(old('learning_fields')) && in_array($field->id, old('learning_fields'))) ? 'checked' : '' }}
                                            onclick="toggleHourKmFields({{ $field->id }})">
                                        <label class="form-check-label" for="learning_field_{{ $field->id }}">{{ $field->name }}</label>
                                        <div id="learning_field_{{ $field->id }}_details" class="extra-fields" style="display:none;">
                                            <label for="hours_{{ $field->id }}">Số giờ:</label>
                                            <input type="number" name="hours[{{ $field->id }}]" id="hours_{{ $field->id }}" class="form-control" placeholder="Số giờ" value="{{ old('hours.' . $field->id) }}">
                                            @error('hours.' . $field->id)
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <label for="km_{{ $field->id }}">Số km:</label>
                                            <input type="number" name="km[{{ $field->id }}]" id="km_{{ $field->id }}" class="form-control" placeholder="Số km" value="{{ old('km.' . $field->id) }}">
                                            @error('km.' . $field->id)
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('learning_fields') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="code" class="form-label">Mã khóa học</label>
                        <input type="text" name="code" class="form-control" placeholder="Mã khóa học" value="{{ old('code') }}" required>
                        @error('code') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                        
                    {{-- <div class="form-group">
                        <label for="shlx_course_id" class="form-label">ID khóa học trên hệ thống sát hạch</label>
                        <input type="number" name="shlx_course_id" class="form-control" placeholder="Mã khóa học" value="{{ old('shlx_course_id') }}">
                        @error('shlx_course_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="course_system_code" class="form-label">Mã khóa học trên hệ thống sát hạch</label>
                        <input type="text" name="course_system_code" class="form-control" placeholder="Mã khóa học" value="{{ old('course_system_code') }}">
                        @error('course_system_code') <div class="text-danger">{{ $message }}</div> @enderror
                    </div> --}}

                    {{-- <div class="form-group">
                        <label for="curriculum_id" class="form-label">Giáo trình</label>
                        <select name="curriculum_id" class="form-control">
                            <option value="">-- Chọn giáo trình --</option>
                            @foreach($curriculums as $curriculum)
                                <option value="{{ $curriculum->id }}" {{ old('curriculum_id') == $curriculum->id ? 'selected' : '' }}>{{ $curriculum->name }}</option>
                            @endforeach
                        </select>
                        @error('curriculum_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div> --}}
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="ranking_id" class="form-label">Hạng</label>
                            <select name="ranking_id" class="form-control" required>
                                <option value="">-- Chọn hạng GPLX --</option>
                                @foreach($rankings as $ranking)
                                    <option value="{{ $ranking->id }}" {{ old('ranking_id') == $ranking->id ? 'selected' : '' }}>
                                        {{ $ranking->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ranking_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="ranking_id" class="form-label">Hạng GP</label>
                            <select name="ranking_id" class="form-control" required>
                                <option value="">-- Chọn hạng GPLX --</option>
                                @foreach($rankings as $ranking)
                                    <option value="{{ $ranking->id }}" {{ old('ranking_id') == $ranking->id ? 'selected' : '' }}>
                                        {{ $ranking->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ranking_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                            
                    {{-- <div class="form-group">
                        <label for="number_bc" class="form-label">Số BC</label>
                        <input type="text" name="number_bc" class="form-control" value="{{ old('number_bc') }}" required>
                        @error('number_bc') <div class="text-danger">{{ $message }}</div> @enderror
                    </div> --}}

                    <div class="col-12 col-md-6">
                        <div class="form-group position-relative">
                            <label for="cabin_date" class="form-label">Ngày học Cabin</label>
                            <input 
                                type="text" 
                                placeholder="dd/mm/yyyy"
                                name="cabin_date" 
                                class="form-control real-date" autocomplete="off" 
                                value="{{ old('cabin_date') ? \Carbon\Carbon::parse(old('cabin_date'))->format('d/m/Y') : '' }}"
                                required
                            >
                            @error('cabin_date') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group position-relative">
                            <label for="dat_date" class="form-label">Ngày học DAT</label>
                            <input 
                                type="text" 
                                placeholder="dd/mm/yyyy"
                                name="dat_date" 
                                class="form-control real-date" autocomplete="off" 
                                value="{{ old('dat_date') ? \Carbon\Carbon::parse(old('dat_date'))->format('d/m/Y') : '' }}"
                                required
                            >
                            @error('dat_date') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group position-relative">
                            <label for="date_bci" class="form-label">Ngày BCI</label>
                            <input 
                                type="text" 
                                placeholder="dd/mm/yyyy"
                                name="date_bci" 
                                class="form-control real-date" autocomplete="off" 
                                value="{{ old('date_bci') ? \Carbon\Carbon::parse(old('date_bci'))->format('d/m/Y') : '' }}"
                                required
                            >
                            @error('date_bci') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group position-relative">
                            <label for="start_date" class="form-label">Ngày bắt đầu</label>
                            <input 
                                type="text"
                                placeholder="dd/mm/yyyy"
                                name="create" 
                                class="form-control real-date" autocomplete="off" 
                                value="{{ old('start_date') ? \Carbon\Carbon::parse(old('start_date'))->format('d/m/Y') : '' }}"
                                required
                            >
                            @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group position-relative">
                            <label for="end_date" class="form-label">Ngày kết thúc</label>
                            <input 
                                type="text" 
                                placeholder="dd/mm/yyyy"
                                name="end_date" 
                                class="form-control real-date" autocomplete="off"
                                value="{{ old('end_date') ? \Carbon\Carbon::parse(old('end_date'))->format('d/m/Y') : '' }}"
                                required
                            >
                            @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>


                    {{-- <div class="form-group">
                        <label for="start_date" class="form-label">Khai giảng</label>
                        <input type="date" placeholder="dd/mm/yyyy"name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                        @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="form-label">Bế giảng</label>
                        <input type="date" placeholder="dd/mm/yyyy"name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                        @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div> --}}
                    
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="tuition_fee" class="form-label">Học phí</label>
                            <input type="text" name="tuition_fee" class="form-control currency-input" value="{{ old('tuition_fee') }}" required>
                            @error('tuition_fee') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="decision_kg" class="form-label">Quyết định KG</label>
                            <input type="text" name="decision_kg" class="form-control" value="{{ old('decision_kg') }}" required>
                            @error('decision_kg') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="duration" class="form-label">Thời gian</label>
                            <input type="text" name="duration" class="form-control currency-input" value="{{ old('duration') }}" required>
                            @error('duration') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- <div class="mb-3">
                        <label class="form-label">Lĩnh vực thi</label>
                        <div>
                            <input type="checkbox" id="selectAllExamFields" onclick="toggleCheckboxes('exam_fields', 'selectAllExamFields')" /> Chọn tất cả
                        </div>
                        <div class="row">
                            @foreach($examFields as $field)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="exam_fields[]" value="{{ $field->id }}" id="exam_field_{{ $field->id }}"
                                            {{ (is_array(old('exam_fields')) && in_array($field->id, old('exam_fields'))) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="exam_field_{{ $field->id }}">{{ $field->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('exam_fields') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Môn học</label>
                        <div>
                            <input type="checkbox" id="selectAllLearningFields" onclick="toggleCheckboxes('learning_fields', 'selectAllLearningFields')" /> Chọn tất cả
                        </div>
                        <div class="row">
                            @foreach($learningFields as $field)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="learning_fields[]" value="{{ $field->id }}" id="learning_field_{{ $field->id }}"
                                            {{ (is_array(old('learning_fields')) && in_array($field->id, old('learning_fields'))) ? 'checked' : '' }}
                                            onclick="toggleHourKmFields({{ $field->id }})">
                                        <label class="form-check-label" for="learning_field_{{ $field->id }}">{{ $field->name }}</label>
                                        <div id="learning_field_{{ $field->id }}_details" class="extra-fields" style="display:none;">
                                            <label for="hours_{{ $field->id }}">Số giờ</label>
                                            <input type="number" name="hours[{{ $field->id }}]" id="hours_{{ $field->id }}" class="form-control" placeholder="Số giờ" value="{{ old('hours.' . $field->id) }}">
                                            @error('hours.' . $field->id)
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <label for="km_{{ $field->id }}">Số km</label>
                                            <input type="number" name="km[{{ $field->id }}]" id="km_{{ $field->id }}" class="form-control" placeholder="Số km" value="{{ old('km.' . $field->id) }}">
                                            @error('km.' . $field->id)
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('learning_fields') <div class="text-danger">{{ $message }}</div> @enderror
                    </div> --}}
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Lưu</button>
        </form>
    </div>
</div>
@endsection
@section('js')
    <script>
        function toggleCheckboxes(fieldName, selectAllId) {
            var checkboxes = document.getElementsByName(fieldName + '[]');
            var selectAll = document.getElementById(selectAllId);

            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAll.checked;
                toggleHourKmFields(checkbox.value);
            });
        }

        function toggleHourKmFields(fieldId) {
            var fieldDetails = document.getElementById('learning_field_' + fieldId + '_details');
            var checkbox = document.getElementById('learning_field_' + fieldId);

            // Hiển thị hoặc ẩn phần giờ và km dựa vào trạng thái checkbox
            if (checkbox && fieldDetails) {
                fieldDetails.style.display = checkbox.checked ? 'block' : 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const checkedLearningFields = document.querySelectorAll('input[name="learning_fields[]"]:checked');

            checkedLearningFields.forEach(function(checkbox) {
                toggleHourKmFields(checkbox.value);
            });
        });
    </script>
@endsection
