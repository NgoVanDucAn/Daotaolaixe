@extends('layouts.admin')

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('lessons.index'),
            'title' => 'Quay về trang quản lý bài học'
        ])
    </div>
@endsection

@section('content')
<div class="container">
    <h2>Chỉnh sửa bài học: {{ $lesson->title }}</h2>

    {{-- PHẦN 1: Thông tin bài học --}}
    <div class="card my-3">
        <div class="card-body">
            <h4>Thông tin bài học</h4>
            <p><strong>Tiêu đề:</strong> {{ $lesson->title }}</p>
            <p><strong>Mô tả:</strong> {{ $lesson->description }}</p>
            <p><strong>Trình tự:</strong> {{ $lesson->sequence }}</p>
            <p><strong>Giáo trình:</strong> {{ $lesson->curriculum->name }}</p>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editLessonModal">Chỉnh sửa</button>
        </div>
    </div>

    {{-- PHẦN 2: Quiz Sets --}}
    <div class="card my-3">
        <div class="card-body">
            <h4>Danh sách Quiz Sets</h4>
            <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addQuizSetModal">➕ Thêm Quiz Set</a>
            @foreach($lesson->quizSets as $quizSet)
                <div class="border p-3 mb-3">
                    <h5>{{ $quizSet->name }}</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary mb-2" data-bs-toggle="modal" data-bs-target="#editQuizSetModal" data-id="{{ $quizSet->id }}" data-name="{{ $quizSet->name }}">Chỉnh sửa Quiz Set</a>

                    <h6>Danh sách câu hỏi:</h6>
                    <a href="#" class="btn btn-sm btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addQuizModal" data-quiz-set-id="{{ $quizSet->id }}">➕ Thêm câu hỏi</a>
                    <ul>
                        @foreach($quizSet->quizzes as $quiz)
                            <li>
                                <strong>{{ $quiz->question }}</strong>
                                <a href="#" class="btn btn-sm btn-link" data-bs-toggle="modal" data-bs-target="#editQuizModal" 
                                    data-id="{{ $quiz->id }}" data-question="{{ $quiz->question }}" 
                                    data-options='@json($quiz->quizOptions)'>📝
                                </a>
                                <ul>
                                    @foreach($quiz->quizOptions as $option)
                                        <li>
                                            {{ $option->option_text }}
                                            @if($option->is_correct) ✅ @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
    <a href="{{ route('lessons.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách</a>
</div>

<!-- Modal chỉnh sửa thông tin lesson -->
<div class="modal fade" id="editLessonModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editLessonForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="modal_name" value="editLessonModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa thông tin bài học</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="lesson-error" class="alert alert-danger d-none"></div>
                    <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề</label>
                        <input type="text" class="form-control" name="title" id="edit-title" value="{{ $lesson->title }}">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" id="edit-description">{{ $lesson->description }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal thêm Quiz Set -->
<div class="modal fade" id="addQuizSetModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="addQuizSetForm">
            @csrf
            <input type="hidden" name="modal_name" value="addQuizSetModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Quiz Set</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="quiz-set-error" class="alert alert-danger d-none"></div>
                    <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên Quiz Set</label>
                        <input type="text" class="form-control" name="name" id="quiz-set-name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal chỉnh sửa Quiz Set -->
<div class="modal fade" id="editQuizSetModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editQuizSetForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="modal_name" value="editQuizSetModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa Quiz Set</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="edit-quiz-set-error" class="alert alert-danger d-none"></div>
                    <input type="hidden" name="quiz_set_id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên Quiz Set</label>
                        <input type="text" class="form-control" name="name" id="edit-quiz-set-name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal thêm Quiz -->
<div class="modal fade" id="addQuizModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="addQuizForm">
            @csrf
            <input type="hidden" name="modal_name" value="addQuizModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm câu hỏi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="quiz-error" class="alert alert-danger d-none"></div>
                    <input type="hidden" name="quiz_set_id">
                    <div class="mb-3">
                        <label for="question" class="form-label">Câu hỏi</label>
                        <textarea class="form-control" name="question" id="quiz-question" required></textarea>
                    </div>
                    <div class="mb-3">
                        <h6>Đáp án</h6>
                        <div id="quiz-options">
                            <div class="input-group mb-2 option-group">
                                <input type="text" class="form-control" name="options[]" placeholder="Nhập đáp án" required>
                                <div class="input-group-text">
                                    <input type="checkbox" name="is_correct[]" value="1"> Đúng
                                    <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeOption(this)">Xóa</button>
                                </div>
                            </div>
                            <div class="input-group mb-2 option-group">
                                <input type="text" class="form-control" name="options[]" placeholder="Nhập đáp án" required>
                                <div class="input-group-text">
                                    <input type="checkbox" name="is_correct[]" value="1"> Đúng
                                    <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeOption(this)">Xóa</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addQuizOption('quiz-options')">Thêm đáp án</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal chỉnh sửa Quiz -->
<div class="modal fade" id="editQuizModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editQuizForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="modal_name" value="editQuizModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa câu hỏi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="edit-quiz-error" class="alert alert-danger d-none"></div>
                    <input type="hidden" name="quiz_id">
                    <div class="mb-3">
                        <label for="question" class="form-label">Câu hỏi</label>
                        <textarea class="form-control" name="question" id="edit-quiz-question" required></textarea>
                    </div>
                    <div class="mb-3">
                        <h6>Đáp án</h6>
                        <div id="edit-quiz-options"></div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addQuizOption('edit-quiz-options')">Thêm đáp án</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('editLessonForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const lessonId = formData.get('lesson_id');
        const errorDiv = document.getElementById('lesson-error');

        // Reset lỗi
        errorDiv.classList.add('d-none');
        errorDiv.innerHTML = '';

        fetch(`/lessons/${lessonId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) throw response;
            return response.json();
        })
        .then(data => {
            // Cập nhật DOM
            document.querySelector('.card-body h2').innerText = `Chỉnh sửa bài học: ${data.title}`;
            document.querySelector('.card-body p:nth-child(1)').innerHTML = `<strong>Tiêu đề:</strong> ${data.title}`;
            document.querySelector('.card-body p:nth-child(2)').innerHTML = `<strong>Mô tả:</strong> ${data.description}`;

            // Đóng modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editLessonModal'));
            modal.hide();
        })
        .catch(async error => {
            const err = await error.json();
            errorDiv.classList.remove('d-none');
            errorDiv.innerHTML = err.message || 'Đã có lỗi xảy ra';
            if (err.errors) {
                errorDiv.innerHTML += '<ul>' + Object.values(err.errors).map(e => `<li>${e}</li>`).join('') + '</ul>';
            }
        });
    });
    document.getElementById('addQuizSetForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const errorDiv = document.getElementById('quiz-set-error');

        errorDiv.classList.add('d-none');
        errorDiv.innerHTML = '';

        fetch('/quiz-sets', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) throw response;
            return response.json();
        })
        .then(data => {
            // Thêm Quiz Set mới vào DOM
            const quizSetContainer = document.querySelector('.card.my-3 .card-body');
            const newQuizSet = `
                <div class="border p-3 mb-3">
                    <h5>${data.name}</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary mb-2" data-bs-toggle="modal" data-bs-target="#editQuizSetModal" data-id="${data.id}" data-name="${data.name}">Chỉnh sửa Quiz Set</a>
                    <h6>Danh sách câu hỏi:</h6>
                    <ul></ul>
                </div>`;
            quizSetContainer.insertAdjacentHTML('beforeend', newQuizSet);

            // Đóng modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addQuizSetModal'));
            modal.hide();
            form.reset();
        })
        .catch(async error => {
            const err = await error.json();
            errorDiv.classList.remove('d-none');
            errorDiv.innerHTML = err.message || 'Đã có lỗi xảy ra';
            if (err.errors) {
                errorDiv.innerHTML += '<ul>' + Object.values(err.errors).map(e => `<li>${e}</li>`).join('') + '</ul>';
            }
        });
    });

    // Chỉnh sửa Quiz Set
    document.querySelectorAll('[data-bs-target="#editQuizSetModal"]').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const form = document.getElementById('editQuizSetForm');
            form.querySelector('[name="quiz_set_id"]').value = id;
            form.querySelector('[name="name"]').value = name;
        });
    });

    document.getElementById('editQuizSetForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const quizSetId = formData.get('quiz_set_id');
        const errorDiv = document.getElementById('edit-quiz-set-error');

        errorDiv.classList.add('d-none');
        errorDiv.innerHTML = '';

        fetch(`/quiz-sets/${quizSetId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) throw response;
            return response.json();
        })
        .then(data => {
            // Cập nhật DOM
            const quizSetElement = document.querySelector(`[data-id="${quizSetId}"]`).closest('.border.p-3');
            quizSetElement.querySelector('h5').innerText = data.name;
            quizSetElement.querySelector('[data-bs-target="#editQuizSetModal"]').dataset.name = data.name;

            // Đóng modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editQuizSetModal'));
            modal.hide();
        })
        .catch(async error => {
            const err = await error.json();
            errorDiv.classList.remove('d-none');
            errorDiv.innerHTML = err.message || 'Đã có lỗi xảy ra';
            if (err.errors) {
                errorDiv.innerHTML += '<ul>' + Object.values(err.errors).map(e => `<li>${e}</li>`).join('') + '</ul>';
            }
        });
    });

    function addQuizOption(containerId = 'quiz-options') {
        const container = document.getElementById(containerId);
        const newOption = `
            <div class="input-group mb-2 option-group">
                <input type="text" class="form-control" name="options[]" placeholder="Nhập đáp án" required>
                <div class="input-group-text">
                    <input type="checkbox" name="is_correct[]" value="1"> Đúng
                    <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeOption(this)">Xóa</button>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', newOption);
    }

    function removeOption(button) {
        const container = button.closest('.option-group').parentElement;
        if (container.querySelectorAll('.option-group').length > 2) {
            button.closest('.option-group').remove();
        } else {
            alert('Phải có ít nhất 2 đáp án!');
        }
    }

    // Thêm Quiz
    document.querySelectorAll('[data-bs-target="#addQuizModal"]').forEach(button => {
        button.addEventListener('click', function () {
            const quizSetId = this.dataset.quizSetId;
            document.getElementById('addQuizForm').querySelector('[name="quiz_set_id"]').value = quizSetId;
            document.getElementById('quiz-error').classList.add('d-none');
            document.getElementById('quiz-error').innerHTML = '';
        });
    });

    document.getElementById('addQuizForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const quizSetId = formData.get('quiz_set_id');
        const errorDiv = document.getElementById('quiz-error');

        errorDiv.classList.add('d-none');
        errorDiv.innerHTML = '';

        fetch('/quizzes', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) throw response;
            return response.json();
        })
        .then(data => {
            // Thêm Quiz mới vào DOM
            const quizList = document.querySelector(`[data-id="${quizSetId}"]`).closest('.border.p-3').querySelector('ul');
            const newQuiz = `
                <li>
                    <strong>${data.question}</strong>
                    <a href="#" class="btn btn-sm btn-link" data-bs-toggle="modal" data-bs-target="#editQuizModal" 
                    data-id="${data.id}" data-question="${data.question}" 
                    data-options='${JSON.stringify(data.quizOptions)}'>📝</a>
                    <ul>
                        ${data.quizOptions.map(option => `
                            <li>${option.option_text} ${option.is_correct ? '✅' : ''}</li>
                        `).join('')}
                    </ul>
                </li>`;
            quizList.insertAdjacentHTML('beforeend', newQuiz);

            // Đóng modal và reset form
            const modal = bootstrap.Modal.getInstance(document.getElementById('addQuizModal'));
            modal.hide();
            form.reset();
            document.getElementById('quiz-options').innerHTML = `
                <div class="input-group mb-2 option-group">
                    <input type="text" class="form-control" name="options[]" placeholder="Nhập đáp án" required>
                    <div class="input-group-text">
                        <input type="checkbox" name="is_correct[]" value="1"> Đúng
                        <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeOption(this)">Xóa</button>
                    </div>
                </div>
                <div class="input-group mb-2 option-group">
                    <input type="text" class="form-control" name="options[]" placeholder="Nhập đáp án" required>
                    <div class="input-group-text">
                        <input type="checkbox" name="is_correct[]" value="1"> Đúng
                        <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeOption(this)">Xóa</button>
                    </div>
                </div>`;
        })
        .catch(async error => {
            const err = await error.json();
            errorDiv.classList.remove('d-none');
            errorDiv.innerHTML = err.message || 'Đã có lỗi xảy ra';
            if (err.errors) {
                errorDiv.innerHTML += '<ul>' + Object.values(err.errors).map(e => `<li>${e}</li>`).join('') + '</ul>';
            }
        });
    });

    // Chỉnh sửa Quiz
    document.querySelectorAll('[data-bs-target="#editQuizModal"]').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const question = this.dataset.question;
            const options = JSON.parse(this.dataset.options);
            const form = document.getElementById('editQuizForm');
            form.querySelector('[name="quiz_id"]').value = id;
            form.querySelector('[name="question"]').value = question;
            const optionsContainer = document.getElementById('edit-quiz-options');
            optionsContainer.innerHTML = options.map(option => `
                <div class="input-group mb-2 option-group">
                    <input type="text" class="form-control" name="options[]" value="${option.option_text}" required>
                    <div class="input-group-text">
                        <input type="checkbox" name="is_correct[]" value="1" ${option.is_correct ? 'checked' : ''}> Đúng
                        <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeOption(this)">Xóa</button>
                    </div>
                </div>
            `).join('');
            document.getElementById('edit-quiz-error').classList.add('d-none');
            document.getElementById('edit-quiz-error').innerHTML = '';
        });
    });

    document.getElementById('editQuizForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const quizId = formData.get('quiz_id');
        const errorDiv = document.getElementById('edit-quiz-error');

        errorDiv.classList.add('d-none');
        errorDiv.innerHTML = '';

        fetch(`/quizzes/${quizId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) throw response;
            return response.json();
        })
        .then(data => {
            // Cập nhật DOM
            const quizElement = document.querySelector(`[data-id="${quizId}"]`).closest('li');
            quizElement.querySelector('strong').innerText = data.question;
            quizElement.querySelector('[data-bs-target="#editQuizModal"]').dataset.question = data.question;
            quizElement.querySelector('[data-bs-target="#editQuizModal"]').dataset.options = JSON.stringify(data.quizOptions);
            quizElement.querySelector('ul').innerHTML = data.quizOptions.map(option => `
                <li>${option.option_text} ${option.is_correct ? '✅' : ''}</li>
            `).join('');

            // Đóng modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editQuizModal'));
            modal.hide();
        })
        .catch(async error => {
            const err = await error.json();
            errorDiv.classList.remove('d-none');
            errorDiv.innerHTML = err.message || 'Đã có lỗi xảy ra';
            if (err.errors) {
                errorDiv.innerHTML += '<ul>' + Object.values(err.errors).map(e => `<li>${e}</li>`).join('') + '</ul>';
            }
        });
    });
</script>
@endsection
