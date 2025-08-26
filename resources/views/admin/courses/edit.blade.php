@extends('layouts.admin')

@section('title', 'Chỉnh sửa khóa học')

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('courses.index'),
            'title' => 'Quay về trang quản lý khóa học'
        ])
    </div>
@endsection

@section('content')
    <form action="{{ route('courses.update', $course->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="code" class="form-label">Mã khóa học:</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $course->code) }}" required>
                    @error('code') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="curriculum_id" class="form-label">Giáo trình:</label>
                    <select name="curriculum_id" class="form-control">
                        @foreach($curriculums as $curriculum)
                            <option value="{{ $curriculum->id }}" {{ $course->curriculum_id == $curriculum->id ? 'selected' : '' }}>
                                {{ $curriculum->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('curriculum_id') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="number_bc" class="form-label">Số BC:</label>
                    <input type="text" name="number_bc" class="form-control" value="{{ old('number_bc', $course->number_bc) }}" required>
                    @error('number_bc') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="date_bci" class="form-label">Ngày BC:</label>
                    <input type="date" placeholder="dd/mm/yyyy"name="date_bci" class="form-control" value="{{ old('date_bci', \Carbon\Carbon::parse($course->date_bci)->format('Y-m-d')) }}" required>
                    @error('date_bci') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="start_date" class="form-label">Khai giảng:</label>
                    <input type="date" placeholder="dd/mm/yyyy"name="start_date" class="form-control" value="{{ old('start_date', \Carbon\Carbon::parse($course->start_date)->format('Y-m-d')) }}" required>
                    @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="end_date" class="form-label">Bế giảng:</label>
                    <input type="date" placeholder="dd/mm/yyyy"name="end_date" class="form-control" value="{{ old('end_date', \Carbon\Carbon::parse($course->end_date)->format('Y-m-d')) }}" required>
                    @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="dat_date" class="form-label">Ngày học DAT:</label>
                    <input type="date" placeholder="dd/mm/yyyy"name="dat_date" class="form-control" value="{{ old('dat_date', \Carbon\Carbon::parse($course->dat_date)->format('Y-m-d')) }}" required>
                    @error('dat_date') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="cabin_date" class="form-label">Ngày học Cabin:</label>
                    <input type="date" placeholder="dd/mm/yyyy"name="cabin_date" class="form-control" value="{{ old('cabin_date', \Carbon\Carbon::parse($course->cabin_date)->format('Y-m-d')) }}" required>
                    @error('cabin_date') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="decision_kg" class="form-label">Quyết định khóa học:</label>
                    <input type="text" name="decision_kg" class="form-control" value="{{ old('decision_kg', $course->decision_kg) }}" required>
                    @error('decision_kg') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="duration" class="form-label">Số giờ học:</label>
                    <input type="number" name="duration" class="form-control" value="{{ old('duration', $course->duration) }}" required>
                    @error('duration') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="tuition_fee" class="form-label">Học phí:</label>
                    <input type="number" name="tuition_fee" class="form-control" value="{{ old('tuition_fee', $course->tuition_fee) }}" required>
                    @error('tuition_fee') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Lĩnh vực thi</label>
                    <div>
                        <input type="checkbox" id="selectAllExamFields" onclick="toggleCheckboxes('exam_fields', 'selectAllExamFields')" /> Chọn tất cả
                    </div>
                    <div class="row">
                        @foreach($examFields as $field)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="exam_fields[]" value="{{ $field->id }}" 
                                        id="exam_field_{{ $field->id }}" 
                                        {{ $course->examFields->contains('id', $field->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="exam_field_{{ $field->id }}">{{ $field->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Môn học</label>
                    <div>
                        <input type="checkbox" id="selectAllLearningFields" onclick="toggleCheckboxes('learning_fields', 'selectAllLearningFields')" /> Chọn tất cả
                    </div>
                    <div class="row">
                        @foreach($learningFields as $field)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="learning_fields[]" value="{{ $field->id }}" 
                                        id="learning_field_{{ $field->id }}" 
                                        {{ $course->learningFields->contains('id', $field->id) ? 'checked' : '' }}
                                        onchange="toggleInputFields({{ $field->id }})">
                                    <label class="form-check-label" for="learning_field_{{ $field->id }}">{{ $field->name }}</label>
                                </div>
                                
                                <!-- Input cho giờ -->
                                <div class="form-group" id="learning_field_hours_{{ $field->id }}" style="display: {{ $course->learningFields->contains('id', $field->id) ? 'block' : 'none' }};">
                                    <label for="hours[{{ $field->id }}]" class="form-label">Số giờ:</label>
                                    <input type="number" name="hours[{{ $field->id }}]" class="form-control" value="{{ old('hours.' . $field->id, $course->learningFields->where('id', $field->id)->first()->pivot->hours ?? '') }}" step="0.001">
                                    @error('hours.' . $field->id)
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Input cho km -->
                                <div class="form-group" id="learning_field_km_{{ $field->id }}" style="display: {{ $course->learningFields->contains('id', $field->id) ? 'block' : 'none' }};">
                                    <label for="km[{{ $field->id }}]" class="form-label">Số km:</label>
                                    <input type="number" name="km[{{ $field->id }}]" class="form-control" value="{{ old('km.' . $field->id, $course->learningFields->where('id', $field->id)->first()->pivot->km ?? '') }}" step="0.001">
                                    @error('km.' . $field->id)
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="code" class="form-label">Mã khóa học</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $course->code) }}" required>
                    @error('code') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>
            {{-- @dd($course) --}}
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="ranking_id" class="form-label">Hạng</label>
                        <select name="ranking_id" class="form-control" required>
                            <option value="">-- Chọn hạng GPLX --</option>
                            @foreach($rankings as $ranking)
                                <option value="{{ $ranking->id }}" {{ $course->ranking_id == $ranking->id ? 'selected' : '' }}>
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

                <div class="col-12 col-md-6">
                    <div class="form-group position-relative">
                        <label for="cabin_date" class="form-label">Ngày học Cabin</label>
                        <input type="text" placeholder="dd/mm/yyyy"name="cabin_date" class="form-control real-date" autocomplete="off" value="{{ old('cabin_date', \Carbon\Carbon::parse($course->cabin_date)->format('Y-m-d')) }}" required>
                        @error('cabin_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group position-relative">
                        <label for="dat_date" class="form-label">Ngày học DAT</label>
                        <input type="text" placeholder="dd/mm/yyyy"name="dat_date" class="form-control real-date" autocomplete="off" value="{{ old('dat_date', \Carbon\Carbon::parse($course->dat_date)->format('Y-m-d')) }}" required>
                        @error('dat_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group position-relative">
                        <label for="date_bci" class="form-label">Ngày BCI</label>
                        <input type="text" placeholder="dd/mm/yyyy"name="date_bci" class="form-control real-date" autocomplete="off" value="{{ old('date_bci', \Carbon\Carbon::parse($course->date_bci)->format('Y-m-d')) }}" required>
                        @error('date_bci') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group position-relative">
                        <label for="start_date" class="form-label">Ngày bắt đầu</label>
                        <input type="text" placeholder="dd/mm/yyyy"name="create" class="form-control real-date" autocomplete="off" value="{{ old('start_date', \Carbon\Carbon::parse($course->start_date)->format('Y-m-d')) }}" required>
                        @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group position-relative">
                        <label for="end_date" class="form-label">Ngày kết thúc</label>
                        <input type="text" placeholder="dd/mm/yyyy"name="end_date" class="form-control real-date" autocomplete="off" value="{{ old('end_date', \Carbon\Carbon::parse($course->end_date)->format('Y-m-d')) }}" required>
                        @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="tuition_fee" class="form-label">Học phí</label>
                        <input type="text" name="tuition_fee" class="form-control currency-input" value="{{ old('tuition_fee', $course->tuition_fee) }}" required>
                        @error('tuition_fee') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="decision_kg" class="form-label">Quyết định KG</label>
                        <input type="text" name="decision_kg" class="form-control" value="{{ old('decision_kg', $course->decision_kg) }}" required>
                        @error('decision_kg') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="duration" class="form-label">Thời gian</label>
                        <input type="text" name="duration" class="form-control currency-input" value="{{ old('duration', $course->duration) }}" required>
                        @error('duration') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-end">
            <button type="" class="btn btn-secondary mt-3 me-2">Hủy</button>
            <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
        </div>
    </form>

    <script>
        function toggleInputFields(fieldId) {
            var hoursInput = document.getElementById('learning_field_hours_' + fieldId);
            var kmInput = document.getElementById('learning_field_km_' + fieldId);
            var checkbox = document.getElementById('learning_field_' + fieldId);

            // Kiểm tra checkbox có được tích hay không
            if (checkbox.checked) {
                hoursInput.style.display = 'block'; // Hiển thị input giờ
                kmInput.style.display = 'block'; // Hiển thị input km
            } else {
                hoursInput.style.display = 'none'; // Ẩn input giờ
                kmInput.style.display = 'none'; // Ẩn input km

                // Xóa giá trị của các input khi checkbox bị bỏ tích
                document.querySelector('input[name="hours_' + fieldId + '"]').value = '';
                document.querySelector('input[name="km_' + fieldId + '"]').value = '';
            }
        }

        // Hàm "Chọn tất cả" cho các checkbox
        function toggleCheckboxes(fieldName, selectAllId) {
            var checkboxes = document.getElementsByName(fieldName + '[]');
            var selectAll = document.getElementById(selectAllId);

            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAll.checked;
                toggleInputFields(checkbox.value); // Cập nhật hiển thị input
            });
        }
    </script>
@endsection
