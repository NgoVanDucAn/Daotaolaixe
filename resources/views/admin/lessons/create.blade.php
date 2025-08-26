
@extends('layouts.admin')

@section('content')
<form action="{{ route('lessons.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="curriculum_id" class="form-label">Giáo trình</label>
        <select name="curriculum_id" class="form-control">
            <option value="">-- Chọn giáo trình --</option>
            @foreach($curriculums as $curriculum)
                <option value="{{ $curriculum->id }}" {{ old('curriculum_id') == $curriculum->id ? 'selected' : '' }}>{{ $curriculum->name}}</option>
            @endforeach
        </select>
        @error('curriculum_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="title" class="form-label">Tên Bài học</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
        @error('title')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Mô tả Bài học</label>
        <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
        @error('description')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="sequence" class="form-label">Thứ tự Bài học</label>
        <input type="text" class="form-control currency-inpu" id="sequence" name="sequence" value="{{ old('sequence') }}">
        @error('sequence')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <h4>Bộ câu hỏi</h4>
    <div id="quizSetsContainer">
        <!-- Đây là nơi các quiz set sẽ được thêm vào -->
    </div>

    <button type="button" class="btn btn-success" onclick="addQuizSet()">Thêm bộ câu hỏi</button>
    <button type="submit" class="btn btn-primary">Lưu bài học</button>
</form>

<script>
let quizSetIndex = 0;

// Thêm một Bộ quiz mới
function addQuizSet() {
    let quizSetHtml = `
        <div class="quiz-set mt-3 p-3 border">
            <h5>Bộ câu hỏi <span>${quizSetIndex + 1}</span></h5>
            <div class="row">
                <div class="col-3">
                    <label>Chương:</label>
                    <input type="text" name="quiz_sets[${quizSetIndex}][title]" class="form-control mb-2" required>
                </div>
                <div class="col-9">
                    <label>Tên chương:</label>
                    <input type="text" name="quiz_sets[${quizSetIndex}][description]" class="form-control mb-2" required>
                </div>
            </div>

            <div id="quizzesContainer${quizSetIndex}">
                <!-- Đây là nơi chứa các Quiz -->
            </div>
            
            <button type="button" class="btn btn-warning" onclick="addQuiz(${quizSetIndex})">Thêm câu hỏi</button>
            <button type="button" class="btn btn-danger" onclick="removeElement(this)">Xóa bộ câu hỏi</button>
        </div>`;
    
    document.getElementById("quizSetsContainer").insertAdjacentHTML('beforeend', quizSetHtml);
    quizSetIndex++;
}

// Thêm một quiz vào bộ quiz
function addQuiz(quizSetIdx) {
    let quizIndex = document.querySelectorAll(`#quizzesContainer${quizSetIdx} .quiz`).length;
    
    let quizHtml = `
        <div class="quiz mt-2 p-2 border">
            <label>Câu hỏi:</label>
            <input type="text" name="quiz_sets[${quizSetIdx}][quizzes][${quizIndex}][question]" class="form-control mb-2" required>
            <label>Có bắt buộc đúng hay không:</label>
            <input type="checkbox" name="quiz_sets[${quizSetIdx}][quizzes][${quizIndex}][mandatory]" class="mb-2" value="1">
            </br>
            <div class="row">
                <div class="col-3">
                    <label>Tên câu hỏi:</label>
                    <input type="text" name="quiz_sets[${quizSetIdx}][quizzes][${quizIndex}][name]" class="form-control mb-2" required>
                </div>
                <div class="col-9">
                    <label>Hình ảnh:</label>
                    <input type="file" name="quiz_sets[${quizSetIdx}][quizzes][${quizIndex}][image]" class="form-control" required>
                </div>
            </div>
            <label>Giải thích:</label>
            <input type="text" name="quiz_sets[${quizSetIdx}][quizzes][${quizIndex}][explanation]" class="form-control mb-2" required>
            <div class="row">
                <div class="col-7">
                    <label>Nội dung mẹo:</label>
                    <input type="text" name="quiz_sets[${quizSetIdx}][quizzes][${quizIndex}][tip]" class="form-control mb-2">
                </div>
                <div class="col-5">
                    <label>Hình ảnh cho mẹo:</label>
                    <input type="file" name="quiz_sets[${quizSetIdx}][quizzes][${quizIndex}][tip_image]" class="form-control">
                </div>
            </div>

            <div id="optionsContainer${quizSetIdx}_${quizIndex}">
                <!-- Đây là nơi chứa các lựa chọn -->
            </div>

            <button type="button" class="btn btn-info" onclick="addOption(${quizSetIdx}, ${quizIndex})">Thêm đáp án</button>
            <button type="button" class="btn btn-danger" onclick="removeElement(this)">Xóa câu hỏi</button>
        </div>`;
    
    document.getElementById(`quizzesContainer${quizSetIdx}`).insertAdjacentHTML('beforeend', quizHtml);
}

// Thêm một đáp án vào quiz
function addOption(quizSetIdx, quizIdx) {
    let optionIndex = document.querySelectorAll(`#optionsContainer${quizSetIdx}_${quizIdx} .option`).length;

    let optionHtml = `
        <div class="option mt-1 p-1 border">
            <label>Đáp án:</label>
            <input type="text" name="quiz_sets[${quizSetIdx}][quizzes][${quizIdx}][options][${optionIndex}][option]" class="form-control mb-1" required>
            <label>
                <input type="checkbox" name="quiz_sets[${quizSetIdx}][quizzes][${quizIdx}][options][${optionIndex}][is_correct]" value="1">
                Đáp án đúng
            </label>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeElement(this)">Xóa</button>
        </div>`;
    
    document.getElementById(`optionsContainer${quizSetIdx}_${quizIdx}`).insertAdjacentHTML('beforeend', optionHtml);
}

// Xóa phần tử bất kỳ
function removeElement(element) {
    element.parentElement.remove();
}
</script>
@endsection
