@extends('layouts.admin')

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => url()->previous(),
            'title' => 'Quay về trang trước'
        ])
    </div>
@endsection

@section('content')
<div class="container position-relative mt-5">
    <div id="examSetLoading" style="position:absolute;z-index:10;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.7);display:flex;align-items:center;justify-content:center;">
        <div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div id="examSetFormWrap" style="display:none;">
        <form action="{{ route('exam_sets.store') }}" method="POST" id="examSetForm">
            @csrf
            <div class="row">

                <div class="col-md-9">
                    <div class="card card-outline card-primary mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Danh sách câu hỏi</h5>
                            <div class="d-flex justify-content-end">
                                <input type="text" id="quizSearchInput" class="form-control form-control-sm" placeholder="Tìm kiếm câu hỏi...">
                            </div>
                        </div>
                        <div class="card-body" id="quizList" style="max-height:500px; overflow-y:auto;">


                            <div class="text-muted">Vui lòng chọn hạng bằng để hiển thị câu hỏi.</div>
                        </div>
                    </div>
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h5 class="card-title">Câu hỏi đã chọn (<span id="selectedQuizCount">0</span>)</h5>
                        </div>
                        <div class="card-body" id="selectedQuizList">
                            <div class="text-muted">Chưa chọn câu hỏi nào.</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-outline card-primary mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Thông tin bộ đề</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Phương tiện</label><br>
                                <div id="vehicleTypeRadios">
                                    <label class="me-3"><input type="radio" name="vehicleType" value="1"> Ô tô</label>
                                    <label><input type="radio" name="vehicleType" value="0" checked> Xe máy</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hạng bằng</label>
                                <select id="rankingId" name="license_level" class="form-control" required></select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Loại bộ đề</label>
                                <select name="type" class="form-control" required>
                                    <option value="Đề thi thử">Đề thi thử</option>
                                    <option value="Đề ôn tập">Đề ôn tập</option>
                                    <option value="Câu hỏi ôn tập">Câu hỏi ôn tập</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tên Bộ Đề</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thời gian làm bài (phút)</label>
                                <input type="number" name="time_do" class="form-control" min="1" step="1" required placeholder="Nhập số phút làm bài">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Lưu</button>
                        <a href="{{ route('exam_sets.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </div>
            </div>

            <input type="hidden" name="quiz_ids" id="quizIdsInput">
            <input type="hidden" name="lessonSelect" id="lessonIdInput">
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    // Lấy dữ liệu từ API editor_data
    let editorData = null;
    let selectedQuizIds = [];


    function renderRankings(vehicleType) {
        let html = '<option value="">-- Chọn hạng bằng --</option>';
        if (!editorData) return $('#rankingId').html(html);
        editorData.rankings.filter(r => r.vehicle_type == vehicleType).forEach(r => {
            html += `<option value="${r.id}">${r.name}</option>`;
        });
        $('#rankingId').html(html);
    }

    let allCurrentQuizzes = [];
    let quizSearchText = '';

    function renderQuizList(rankingId) {
        if (!editorData) return;
        // Lấy lessonIds theo rankingId
        const lessonIds = editorData.lessonRanking.filter(lr => lr.ranking_id == rankingId).map(lr => lr.lesson_id);
        // Gán lesson_id đầu tiên (nếu có) vào input ẩn lessonSelect
        if (lessonIds.length > 0) {
            $('#lessonIdInput').val(lessonIds[0]);
        } else {
            $('#lessonIdInput').val('');
        }
        // Lấy quizSets theo lesson
        const quizSets = editorData.quizSets.filter(qs => lessonIds.includes(qs.lesson_id));
        let quizzes = [];
        quizSets.forEach(qs => {
            quizzes = quizzes.concat(editorData.quizzes.filter(q => q.quiz_set_id == qs.id));
        });
        allCurrentQuizzes = quizzes;
        updateQuizList();
    }

    function updateQuizList() {
        let quizzes = allCurrentQuizzes;
        if (quizSearchText && quizSearchText.trim() !== '') {
            const search = quizSearchText.trim().toLowerCase();
            quizzes = quizzes.filter(q =>
                (q.name && q.name.toLowerCase().includes(search)) ||
                (q.question && q.question.toLowerCase().includes(search))
            );
        }
        if (!quizzes.length) {
            $('#quizList').html('<div class="text-muted">Không có câu hỏi nào cho hạng bằng này.</div>');
            return;
        }
        let html = `<table class="table table-sm"><thead>
        <tr>
            <th></th>
            <th>Tên câu hỏi</th>
            <th>Nội dung</th>
            <th>Hình ảnh</th>
        </tr>
    </thead><tbody>`;
        quizzes.forEach(q => {
            html += `<tr>
            <td><input type="checkbox" class="quiz-checkbox" value="${q.id}" ${selectedQuizIds.includes(q.id) ? 'checked' : ''}></td>
            <td>${q.name || ''}</td>
            <td>${q.question || ''}</td>
            <td>${q.image ? `<img src="https://cdn.dtlx.app/question/${q.image}" style="max-width:80px;cursor:pointer;" class="quiz-image-clickable" data-img="https://cdn.dtlx.app/question/${q.image}">` : ''}</td>
        </tr>`;
        });
        html += '</tbody></table>';
        $('#quizList').html(html);
    }

    function updateSelectedQuizList() {
        $('#selectedQuizCount').text(selectedQuizIds.length);
        if (!selectedQuizIds.length) {
            $('#selectedQuizList').html('<div class="text-muted">Chưa chọn câu hỏi nào.</div>');
            $('#quizIdsInput').val('');
            return;
        }
        const selected = editorData.quizzes.filter(q => selectedQuizIds.includes(q.id));
        let html = `<table class="table  table-sm"><thead>
        <tr>
            <th>STT</th>
            <th>Tên câu hỏi</th>
            <th>Nội dung</th>
            <th>Hình ảnh</th>
            <th></th>
        </tr>
    </thead><tbody>`;
        selected.forEach((q, index) => {
            html += `<tr>
            <td>${index + 1}</td>
            <td>Câu hỏi: ${q.name || ''}</td>
            <td>${q.question || ''}</td>
            <td>${q.image ? `<img src=\"https://cdn.dtlx.app/question/${q.image}\" style=\"max-width:80px;cursor:pointer;\" class=\"quiz-image-clickable\" data-img=\"https://cdn.dtlx.app/question/${q.image}\">` : ''}</td>
            <td><button type="button" class="btn btn-danger btn-sm remove-quiz" data-id="${q.id}">Xóa</button></td>
        </tr>`;
        });
        html += '</tbody></table>';
        $('#selectedQuizList').html(html);
        $('#quizIdsInput').val(selectedQuizIds.join(','));
    }

    $(document).ready(function() {

        // Click vào ảnh để mở modal Bootstrap
        // Hàm mở modal ảnh chuẩn Bootstrap 5
        function showQuizImageModal(imgUrl) {
            $('#quizImageModalImg').attr('src', imgUrl);
            var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('quizImageModal'));
            modal.show();
        }
        // Click vào ảnh để mở modal
        $(document).on('click', '.quiz-image-clickable', function() {
            showQuizImageModal($(this).data('img'));
        });
        // Khi modal đóng thì clear src để tránh giữ ảnh cũ
        $('#quizImageModal').on('hidden.bs.modal', function() {
            $('#quizImageModalImg').attr('src', '');
        });

        $('#examSetFormWrap').hide();
        $('#examSetLoading').show();

        // Đặt mặc định radio là oto
        $("input[name='vehicleType'][value='1']").prop('checked', true);

        // Lấy dữ liệu từ API
        $.get('/exam_sets/editor_data', function(data) {
            editorData = data;
            // chọn phương tiện 
            $("input[name='vehicleType']").on('change', function() {
                const vType = $("input[name='vehicleType']:checked").val();
                renderRankings(vType);
                $('#rankingId').val('');
                $('#quizList').html('<div class="text-muted">Vui lòng chọn hạng bằng để hiển thị câu hỏi.</div>');
                $('#selectedQuizList').html('<div class="text-muted">Chưa chọn câu hỏi nào.</div>');
                selectedQuizIds = [];
                updateSelectedQuizList();
            });

            // chọn hạng bằng
            $('#rankingId').on('change', function() {
                renderQuizList($(this).val());
                selectedQuizIds = [];
                updateSelectedQuizList();
                quizSearchText = '';
                $('#quizSearchInput').val('');
            });

            // search
            $(document).on('input', '#quizSearchInput', function() {
                quizSearchText = $(this).val();
                updateQuizList();
            });

            renderRankings('1'); // Khởi tạo lần đầu: render rankings cho Ô tô (1)

            $('#rankingId').val('');
            $('#quizList').html('<div class="text-muted">Vui lòng chọn hạng bằng để hiển thị câu hỏi.</div>');
            $('#selectedQuizList').html('<div class="text-muted">Chưa chọn câu hỏi nào.</div>');
            selectedQuizIds = [];
            updateSelectedQuizList();
            // spinner hide, show form
            $('#examSetLoading').hide();
            $('#examSetFormWrap').show();
        });

        // Chọn câu hỏi
        $(document).on('change', '.quiz-checkbox', function() {
            const quizId = parseInt($(this).val());
            if ($(this).is(':checked')) {
                if (!selectedQuizIds.includes(quizId)) selectedQuizIds.push(quizId);
            } else {
                selectedQuizIds = selectedQuizIds.filter(id => id !== quizId);
            }
            updateSelectedQuizList();
        });

        // Xóa câu hỏi khỏi danh sách đã chọn
        $(document).on('click', '.remove-quiz', function() {
            const quizId = parseInt($(this).data('id'));
            selectedQuizIds = selectedQuizIds.filter(id => id !== quizId);
            $('.quiz-checkbox[value="' + quizId + '"]').prop('checked', false);
            updateSelectedQuizList();
        });

        // Submit: gửi quiz_ids dạng chuỗi id, ví dụ: 1,2,3
        $('#examSetForm').on('submit', function() {
            $('#quizIdsInput').val(selectedQuizIds.join(','));
        });
    });
</script>
<style>
    .form-control {
        border-color: #868686ff !important;
    }
</style>

<div class="modal fade" id="quizImageModal" tabindex="-1" aria-labelledby="quizImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="quizImageModalLabel">Xem ảnh câu hỏi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center" style="min-height:60vh;">
                <img id="quizImageModalImg" src="" alt="Quiz Image" style="max-width:100%;max-height:70vh;object-fit:contain;box-shadow:0 0 20px #000;">
            </div>
        </div>
    </div>
</div>
@endsection