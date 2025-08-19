@extends('layouts.admin')

@section('content')
    <div class="container">
        <form action="{{ route('exam_sets.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tên Bộ Đề</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Hạng Bằng</label>
                <select name="license_level" id="license_level" class="form-control" required>
                    <option value="">-- Chọn Hạng Bằng --</option>
                    @foreach($rankings as $ranking)
                        <option value="{{ $ranking->id  }}" {{ old('license_level') == $ranking->id  ? 'selected' : '' }}>
                            {{ $ranking->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Loại Bộ Đề</label>
                <select name="type" id=type class="form-control select2" required>
                    <option value="">-- Chọn loại bộ đề --</option>
                    <option value="Đề thi thử" {{ old('type') == 'Đề thi thử' ? 'selected' : '' }}>Đề thi thử</option>
                    <option value="Đề ôn tập" {{ old('type') == 'Đề ôn tập' ? 'selected' : '' }}>Đề ôn tập</option>
                    <option value="Câu hỏi ôn tập" {{ old('type') == 'Câu hỏi ôn tập' ? 'selected' : '' }}>Câu hỏi ôn tập</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Chọn Bài Học</label>
                <select id="lessonSelect" name="lessonSelect" class="form-control select2">
                    <option value="">-- Chọn Bài Học --</option>
                    @foreach($data as $lesson)
                        <option value="{{ $lesson['lesson_id'] }}">{{ $lesson['lesson_name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div id="quizSetsContainer"></div>

            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="{{ route('exam_sets.index') }}" class="btn btn-secondary">Hủy</a>
        </form>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Chọn loại bộ đề",
                    allowClear: true
                });
                $('#license_level').select2({
                    placeholder: "Chọn Hạng Bằng",
                    allowClear: true
                });
                $('#type').select2({
                    placeholder: "Chọn Bài Học",
                    allowClear: true
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                // Khi thay đổi bài học
                $('#lessonSelect').on('change', function() {
                    var lessonId = $(this).val();
                    if (lessonId) {
                        var lessonData = @json($data); // Lấy toàn bộ dữ liệu lesson từ controller

                        // Tìm bài học với lesson_id đã chọn
                        var selectedLesson = lessonData.find(function(lesson) {
                            return lesson.lesson_id == lessonId;
                        });

                        // Kiểm tra nếu bài học có dữ liệu quiz_sets
                        if (selectedLesson && selectedLesson.quiz_sets) {
                            var quizSetsHtml = '';
                            selectedLesson.quiz_sets.forEach(function(quizSet, index) {
                                quizSetsHtml += `<div class="mb-3">
                                                    <label class="form-label">${quizSet.quiz_set_name}</label>
                                                    <select name="quiz_sets[${index}][]" class="form-control select2" multiple="multiple">
                                                        <option value="">-- Chọn Câu Hỏi --</option>`;
                                                        
                                quizSet.quizzes.forEach(function(quiz) {
                                    quizSetsHtml += `<option value="${quiz.quiz_id}">Câu ${quiz.quiz_id}: ${quiz.question}</option>`;
                                });

                                quizSetsHtml += `</select>
                                                </div>`;
                            });

                            // Hiển thị các quiz_sets
                            $('#quizSetsContainer').html(quizSetsHtml);
                            $('.select2').select2();  // Khởi tạo lại select2 cho các select mới
                            
                        }
                    } else {
                        // Nếu không có bài học được chọn, xóa nội dung quiz_sets
                        $('#quizSetsContainer').html('');
                    }
                });
            });
        </script>
    </div>
@endsection
