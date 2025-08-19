@extends('layouts.admin')

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('exam_sets.index'),
            'title' => 'Quay về trang quản lý bộ đề thi'
        ])
    </div>
@endsection

@section('content')
    <div class="container">
        <form action="{{ route('exam_sets.update', $examSet->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Tên Bộ Đề:</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name', $examSet->name) }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">

                <div class="col-6 mb-3">
                    <label for="license_level" class="form-label">Hạng bằng:</label>
                    <select name="license_level" id="license_level" class="form-control" required>
                        <option value="">-- Chọn Hạng Bằng --</option>
                        @foreach($rankings as $ranking)
                            <option value="{{ $ranking->id }}" 
                                {{ old('license_level', $examSet->license_level) == $ranking->id ? 'selected' : '' }}>
                                {{ $ranking->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('license_level')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-6 mb-3">
                    <label for="type" class="form-label">Kiểu bộ đề:</label>
                    <select name="type" id="type" class="form-control select2" required>
                        <option value="">-- Chọn loại bộ đề --</option>
                        <option value="Đề thi thử" {{ old('type', $examSet->type) == 'Đề thi thử' ? 'selected' : '' }}>Đề thi thử</option>
                        <option value="Đề ôn tập" {{ old('type', $examSet->type) == 'Đề ôn tập' ? 'selected' : '' }}>Đề ôn tập</option>
                        <option value="Câu hỏi ôn tập" {{ old('type', $examSet->type) == 'Câu hỏi ôn tập' ? 'selected' : '' }}>Câu hỏi ôn tập</option>
                    </select>
                    @error('type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả:</label>
                <input type="text" name="description" id="description" class="form-control"
                    value="{{ old('description', $examSet->description) }}" required>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Cập Nhật</button>
            <a href="{{ route('exam_sets.index') }}" class="btn btn-secondary">Hủy</a>
        </form>

        <hr>

        <!-- Danh sách Câu Hỏi -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Danh Sách Câu Hỏi Của Bộ Đề</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuizModal">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Câu Hỏi</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($examSet->quizzes as $index => $quiz)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $quiz->question }}</td>
                        <td>
                            <form action="{{ route('exam_sets.remove_question', [$examSet->id, $quiz['id']]) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa câu hỏi này khỏi bộ đề này không?')"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal add quiz -->
        <div class="modal fade" id="addQuizModal" tabindex="-1" aria-labelledby="addQuizModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addQuizModalLabel">Thêm Câu Hỏi vào Bộ Đề</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('exam_sets.add_question', $examSet->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="modal_name" value="addQuizModal">
                            <div class="form-group">
                                <label for="quiz_id">Chọn Câu Hỏi</label>
                                <select name="quiz_id" id="quiz_id" class="form-select select2">
                                    <option value="">Chọn câu hỏi</option>
                                    @foreach($optionalQuizzes as $quiz)
                                        <option value="{{ $quiz['id'] }}">Câu {{ $quiz['id'] }}: {{ $quiz['question'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="quiz_mandatory_id">Chọn Câu Hỏi Điểm Liệt</label>
                                <select name="quiz_mandatory_id" id="quiz_mandatory_id" class="form-select">
                                    <option value="">Chọn câu hỏi điểm liệt</option>
                                    @foreach($mandatoryQuizzes as $quiz)
                                        <option value="{{ $quiz['id'] }}">Câu {{ $quiz['id'] }}: {{ $quiz['question'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-primary">Thêm Câu Hỏi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
         $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Chọn loại bộ đề",
                allowClear: true,
                dropdownParent: $('#addQuizModal'),
            });
         })
    </script>
@endsection
