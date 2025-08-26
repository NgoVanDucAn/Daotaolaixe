@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('leadSource.index') }}" class="row g-2 mb-4">
            <div class="col-12 col-md-3">
                <label class="form-label fw-bold">Tên nguồn</label>
                <input type="text" name="name" class="form-control" placeholder="Nhập tên nguồn" value="{{ request('name') }}">
            </div>
            <div class="col-12 col-md-3">
            <label for="">
                <b>&nbsp</b>
            </label>
            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary mb-1">
                    <b>Tìm Kiếm</b>
                </button>
                <a href="{{ route('leadSource.index') }}" class="btn btn-outline-danger mb-1 ms-2" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
            </div>
        </div>
    </div>
</div>

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a
            {{-- href="{{ route('leadSource.create') }}" --}}
            data-bs-toggle="modal" 
            data-bs-target="#addLeadsourceModal"
            type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Tạo Leadsource</span>
        </a>
    </div>
@endsection

<div class="card">
    <div class="card-body">
        <a 
        {{-- href="{{ route('leadSource.create') }}"  --}}
            data-bs-toggle="modal" 
            data-bs-target="#addLeadsourceModal"
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 40px; height: 40px; font-size: 30px; bottom: 3%; right: 60px; z-index: 99;">
                +
        </a>

        <div class="table-responsive">
            <table class="table mt-3 table-bordered" style="width: max-content; min-width: 100%;">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên nguồn</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leadSources as $leadSource)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $leadSource->name }}</td>
                            <td>{{ $leadSource->description }}</td>
                            <td>
                                <a 
                                    {{-- href="{{ route('leadSource.edit', $leadSource->id) }}"  --}}
                                    class="btn btn-warning ps-2 pe-2 pt-0 pb-0 btn-edit-leadsource"
                                    data-id="{{ $leadSource->id }}"
                                    data-name="{{ $leadSource->name }}"
                                    data-description="{{ $leadSource->description }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editLeadsourceModal"
                                >
                                    <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                                </a>
                                <form action="{{ route('leadSource.destroy', $leadSource->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger ps-2 pe-2 pt-0 pb-0" onclick="return confirm('Bạn có chắc chắn muốn xóa nguồn khách hàng tiềm năng này không?')">
                                        <i class="mdi mdi-trash-can-outline fs-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        {{ $leadSources->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- modal create --}}
<div class="modal fade" id="addLeadsourceModal" tabindex="-1" aria-labelledby="addLeadsourceModal" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLeadsourceModal">Thêm mới nguồn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
            </div>
            <div class="modal-body">
                <form id="formAddLeadSource" action="{{ route('leadSource.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="modal_name" value="addLeadsourceModal">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="name" class="form-label">Tên nguồn</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('description') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-2"></i>
                    <span>Hủy</span>
                </button>
                <button type="submit" class="btn btn-primary" form="formAddLeadSource">
                    <i class="mdi mdi-content-save-outline me-2"></i>
                    <span>Lưu</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- modal edit --}}
<div class="modal fade" id="editLeadsourceModal" tabindex="-1" aria-labelledby="editLeadsourceModal" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa nguồn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editLeadsouceForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="modal_name" value="editLeadsourceModal">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Tên nguồn</label>
                            <textarea name="name" id="editName" class="form-control" required></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea name="description" id="editDescription" class="form-control">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-2"></i> <span>Hủy</span>
                </button>
                <button type="submit" class="btn btn-primary" form="editLeadsouceForm">
                    <i class="mdi mdi-content-save-outline me-2"></i> <span>Lưu</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- fill dữ liệu vào modal --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.btn-edit-leadsource');
        const editForm = document.getElementById('editLeadsouceForm');
        const editName = document.getElementById('editName');
        const editDescription = document.getElementById('editDescription');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name') || '';
                const description = this.getAttribute('data-description') || '';

                editForm.action = `/leadSource/${id}`;
                editName.value = name;
                editDescription.value = description;
            });
        });
    });
</script>


@endsection
