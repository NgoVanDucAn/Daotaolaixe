@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('stadiums.index') }}" class="row g-2 mb-4">
            <div class="col-12 col-md-3">
                <label class="form-label fw-bold">Tên sân</label>
                <select name="location" id="location" class="form-select">
                    <option value=""></option>
                    @foreach ($stadiumsAll as $stadium)
                        <option value="{{ $stadium->id }}" {{ request('location') == $stadium->id ? 'selected' : '' }}>{{ $stadium->location }}</option>
                    @endforeach
                </select>
                {{-- <input type="text" name="location" class="form-control" placeholder="Nhập tên sân" value="{{ request('location') }}"> --}}
            </div>
            <div class="col-12 col-md-3">
            <label for="">
                <b>&nbsp</b>
            </label>
            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary mb-1">
                    <b>Tìm Kiếm</b>
                </button>
                <a href="{{ route('stadiums.index') }}" class="btn btn-outline-danger mb-1 ms-2" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
            </div>
        </div>
    </div>
</div>

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a
            {{-- href="{{ route('stadiums.create') }}" --}}
            data-bs-toggle="modal" 
            data-bs-target="#addStadiumModal"
            type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Tạo sân</span>
        </a>
    </div>
@endsection
    
<div class="card">
    <div class="card-body">
        <a 
            {{-- href="{{ route('stadiums.create') }}"  --}}
            data-bs-toggle="modal" 
            data-bs-target="#addStadiumModal"
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 1%; z-index: 99;">
                +
        </a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Địa điểm</th>
                    <th>Ghi chú</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stadiums as $stadium)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if (!empty($stadium->google_maps_url))
                                <a style="font-weight: 600; color: #4C9AFF" href="{{ $stadium->google_maps_url }}" target="_blank" style="text-decoration: none;">
                                    {{ $stadium->location }}
                                </a>
                            @else
                                {{ $stadium->location }}
                            @endif
                        </td>
                        <td>{{ $stadium->note }}</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <a 
                                    href="javascript:void(0);"
                                    class="btn btn-warning ps-2 pe-2 pt-0 pb-0 btn-edit-stadium"
                                    data-id="{{ $stadium->id }}"
                                    data-location="{{ $stadium->location }}"
                                    data-google-maps-url="{{ $stadium->google_maps_url }}"
                                    data-note="{{ $stadium->note }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editStadiumModal"
                                >
                                    <i class="mdi mdi-square-edit-outline fs-4"></i>
                                </a>
                            
                                <form action="{{ route('stadiums.destroy', $stadium) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger ps-2 pe-2 pt-0 pb-0">
                                        <i class="mdi mdi-trash-can-outline fs-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $stadiums->links('pagination::bootstrap-5') }}
    </div>
</div>
{{-- modal create --}}
<div class="modal fade" id="addStadiumModal" tabindex="-1" aria-labelledby="addStadiumModal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStadiumModal">Thêm mới sân thi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('stadiums.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="modal_name" value="addStadiumModal">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="location" class="form-label">Địa điểm</label>
                            <textarea name="location" class="form-control" required>{{ old('location') }}</textarea>
                            @error('location')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="google_maps_url" class="form-label">Google Maps URL</label>
                            <input type="url" name="google_maps_url" class="form-control" value="{{ old('google_maps_url') }}">
                            @error('google_maps_url')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                                <i class="mdi mdi-close me-2"></i>
                                <span>Hủy</span>
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save-outline me-2"></i>
                                <span>Lưu</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- modal edit --}}
<div class="modal fade" id="editStadiumModal" tabindex="-1" aria-labelledby="editStadiumModal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa sân thi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStadiumForm" method="POST">
                    @csrf
                    @method('PUT')  {{-- Bắt buộc --}}
                    <input type="hidden" name="modal_name" value="editStadiumModal">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Địa điểm</label>
                            <textarea name="location" id="editLocation" class="form-control" required></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Google Maps URL</label>
                            <input type="url" name="google_maps_url" id="editGoogleMapsUrl" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea name="note" id="editNote" class="form-control"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-2"></i> <span>Hủy</span>
                </button>
                <button type="submit" class="btn btn-primary" form="editStadiumForm">
                    <i class="mdi mdi-content-save-outline me-2"></i> <span>Lưu</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- fill dữ liệu vào modal --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.btn-edit-stadium');
        const editForm = document.getElementById('editStadiumForm');
        const editLocation = document.getElementById('editLocation');
        const editGoogleMapsUrl = document.getElementById('editGoogleMapsUrl');
        const editNote = document.getElementById('editNote');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const location = this.getAttribute('data-location') || '';
                const googleMapsUrl = this.getAttribute('data-google-maps-url') || '';
                const note = this.getAttribute('data-note') || '';

                editForm.action = `/stadiums/${id}`; // Sửa đúng URL cần thiết
                editLocation.value = location;
                editGoogleMapsUrl.value = googleMapsUrl;
                editNote.value = note;
            });
        });
    });
</script>

@endsection

@section('js')
<script>
    $(document).ready(function() {   
        $('#location').select2({
            placeholder: "-- Chọn sân --",
            allowClear: true,
        });
    });
</script>
@endsection
