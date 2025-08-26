@extends('layouts.admin')

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => url()->previous(),
            'title' => 'Quay về trang trước'
        ])
    </div>
@endsection

@section('title', 'Thêm mới môn thi')

@section('content')
    <form action="{{ route('exam_fields.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Tên lĩnh vực thi:</label>
            <input type="text" name="name" class="form-control" placeholder="Tên lĩnh vực thi" value="{{ old('name') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-check">
            <input type="checkbox" name="is_practical" id="is_practical"
                   class="form-check-input"
                   value="1"
                   {{ old('is_practical') == 1 ? 'checked' : '' }}>
            <label for="is_practical" class="form-check-label">Là môn thực hành</label>
        </div>

        <div class="form-group">
            <label for="description">Mô tả:</label>
            <input type="text" name="description" class="form-control" placeholder="Mô tả" value="{{ old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
@endsection
