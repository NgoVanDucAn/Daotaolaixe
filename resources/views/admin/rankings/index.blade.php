@extends('layouts.admin')


@section('content')

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <button type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
            data-bs-toggle="modal" 
            data-bs-target="#createRankingModal"
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Thêm hạng bằng lái</span>
        </button>
    </div>
@endsection
{{-- <button 
    type="button" 
    class="btn btn-primary mb-3" 
    data-bs-toggle="modal" 
    data-bs-target="#createRankingModal"
>
    Thêm hạng bằng lái
</button> --}}
<div class="card">
    <div class="card-body">
        <a href="{{ route('rankings.create') }}" 
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 1%; z-index: 99;">
                +
        </a>
        <div class="table-responsive">
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th class="text-nowrap">Phí mặc định</th>
                        <th>Mô tả</th>
                        <th style="width: 120px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ranks as $rank)
                        <tr>
                            <td class="text-nowrap">{{ $rank->name }}</td>
                            <td class="text-nowrap">{{ number_format($rank->fee_ranking ?? 0, 0, ',', '.') }} đ</td>
                            <td>{{ $rank->description }}</td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-warning btn-sm ps-2 pe-2 pt-0 pb-0" onclick="openEditRankingModal({{ $rank->id }})" data-bs-toggle="modal" data-bs-target="#editRankingModal">
                                    <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                                </a>
                                <form action="{{ route('rankings.destroy', $rank) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm ps-2 pe-2 pt-0 pb-0" onclick="return confirm('Bạn chắc chắn muốn xóa không?')">
                                        <i class="mdi mdi-trash-can-outline fs-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $ranks->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- Modal create -->
<div class="modal fade" id="createRankingModal" tabindex="-1" aria-labelledby="createRankingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRankingModalLabel">Thêm hạng bằng lái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body modal-body">
                <form id="formCreateRankingModal" action="{{ route('rankings.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Tên hạng</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="fee_ranking">Lệ phí</label>
                        <input type="text" name="fee_ranking" id="fee_ranking" step="0.01" class="form-control currency-input" value="{{ old('fee_ranking') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="vehicle_type" class="form-label">Loại xe</label>
                        <select name="vehicle_type" class="form-control" required>
                            <option value="0" {{ old('vehicle_type') == 0 ? 'selected' : '' }}>Xe máy</option>
                            <option value="1" {{ old('vehicle_type') == 1 ? 'selected' : '' }}>Ô tô</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea name="description" id="description" class="form-control" value="{{ old('description') }}"></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="lessons" class="form-label">Chọn bài học</label>
                        <select name="lessons[]" class="form-control select2" multiple>
                            @foreach($lessons as $lesson)
                                <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-2"></i> <span>Hủy</span>
                </button>
                <button type="submit" class="btn btn-primary" form="formCreateRankingModal">
                    <i class="mdi mdi-content-save-outline me-2"></i> <span>Lưu</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal edit -->
<div class="modal fade" id="editRankingModal" tabindex="-1" aria-labelledby="editRankingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRankingModalLabel">Chỉnh sửa hạng bằng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <form id="editRankingForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="ranking_id" name="ranking_id">

                    <div class="form-group mb-3">
                        <label for="edit_name">Tên hạng</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_fee">Lệ phí</label>
                        <input type="text" name="fee_ranking" id="edit_fee" class="form-control currency-input" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_vehicle_type">Loại xe</label>
                        <select name="vehicle_type" id="edit_vehicle_type" class="form-control" required>
                            <option value="0">Xe máy</option>
                            <option value="1">Ô tô</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_description">Mô tả</label>
                        <textarea name="description" id="edit_description" class="form-control"></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_lessons">Chọn bài học</label>
                        <select name="lessons[]" id="edit_lessons" class="form-control select2" multiple></select>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-2"></i> <span>Hủy</span>
                </button>
                <button type="submit" class="btn btn-primary" form="editRankingForm">
                    <i class="mdi mdi-content-save-outline me-2"></i> <span>Lưu</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            let isInitialized = false;

            $('#createRankingModal').on('shown.bs.modal', function () {
                if (!isInitialized) {
                    $('.select2').select2({
                        dropdownParent: $('#createRankingModal'),
                        width: '100%'
                    });
                    isInitialized = true;
                }
            });
        });
    </script>
    <script>
        function openEditRankingModal(id) {
            fetch(`/rankings/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    const ranking = data.ranking;
                    const lessons = data.lessonAlls;
                    document.getElementById('edit_name').value = ranking.name;
                    document.getElementById('edit_fee').value = ranking.fee_ranking ? parseInt(ranking.fee_ranking).toLocaleString('vi-VN') : '';
                    document.getElementById('edit_vehicle_type').value = ranking.vehicle_type;
                    document.getElementById('edit_description').value = ranking.description || '';
                    document.getElementById('ranking_id').value = ranking.id;

                    const lessonsSelect = document.getElementById('edit_lessons');
                    lessonsSelect.innerHTML = '';
                    lessons.forEach(lesson => {
                        const option = document.createElement('option');
                        option.value = lesson.id;
                        option.text = lesson.title;
                        lessonsSelect.appendChild(option);
                    });

                    $('#edit_lessons').select2({
                        placeholder: 'Chọn bài học',
                        allowClear: true,
                        multiple: true
                    });
                    $('#edit_lessons').val(ranking.lessons).trigger('change');
                    document.getElementById('editRankingForm').action = `/rankings/${id}`;
                })
                .catch(error => console.error('Lỗi khi lấy dữ liệu:', error));
        }
    </script>
@endsection
