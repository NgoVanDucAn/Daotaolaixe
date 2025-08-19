@extends('layouts.admin')

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
            href="{{ route('lessons.create') }}" 
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Thêm bài học</span>
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <a href="{{ route('lessons.create') }}" 
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 1%; z-index: 99;">
                +
        </a>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên Bài học</th>
                        <th>Giáo trình</th>
                        <th>Thứ tự</th>
                        <th>Hành động</th>
                        <th>Cập nhật câu dễ sai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lessons as $lesson)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $lesson->title }}</td>
                            <td>{{ $lesson->curriculum->name }}</td>
                            <td>{{ $lesson->sequence }}</td>
                            <td>
                                <a 
                                    href="{{ route('lessons.show', $lesson->id) }}" 
                                    class="btn btn-sm btn-info m-1" 
                                    style="padding: 2px 12px;"
                                >
                                    <i class="mdi mdi-eye-outline"></i>
                                </a>
                                <a 
                                    href="{{ route('lessons.edit', $lesson->id) }}" 
                                     class="btn btn-sm btn-warning m-1"
                                >
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                                <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateWrongModal" data-lesson-id="{{ $lesson->id }}">Cập nhật Wrong</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal cập nhật câu hỏi dễ sai -->
<div class="modal fade" id="updateWrongModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="updateWrongForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="modal_name" value="updateWrongModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật trường Wrong cho Quiz</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="wrong-error" class="alert alert-danger d-none"></div>
                    <input type="hidden" name="lesson_id">
                    <div class="mb-3">
                        <label for="quiz_ids" class="form-label">Danh sách ID Quiz (phân cách bằng dấu phẩy)</label>
                        <input type="text" class="form-control" name="quiz_ids" id="quiz_ids" placeholder="23,56,99,44,234,12,433" required>
                    </div>
                    <div class="mb-3">
                        <label for="wrong_value" class="form-label">Giá trị Wrong</label>
                        <select class="form-control" name="wrong_value" id="wrong_value" required>
                            <option value="1">True</option>
                            <option value="0">False</option>
                        </select>
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
    document.querySelectorAll('[data-bs-target="#updateWrongModal"]').forEach(button => {
        button.addEventListener('click', function () {
            const lessonId = this.dataset.lessonId;
            document.getElementById('updateWrongForm').querySelector('[name="lesson_id"]').value = lessonId;
            document.getElementById('wrong-error').classList.add('d-none');
            document.getElementById('wrong-error').innerHTML = '';
            document.getElementById('updateWrongForm').reset();
        });
    });

    document.getElementById('updateWrongForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const lessonId = formData.get('lesson_id');
        const errorDiv = document.getElementById('wrong-error');

        errorDiv.classList.add('d-none');
        errorDiv.innerHTML = '';

        fetch('/quizzes/update-wrong', {
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
            // Hiển thị thông báo thành công (có thể cập nhật DOM nếu cần)
            alert('Cập nhật trường Wrong thành công cho ' + data.updated_count + ' quiz!');

            // Đóng modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('updateWrongModal'));
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
</script>
@endsection
