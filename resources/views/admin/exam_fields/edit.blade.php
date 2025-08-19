@extends('layouts.admin')

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('exam_fields.index'),
            'title' => 'Quay về trang lĩnh vực thi'
        ])
    </div>
@endsection

@section('content')
    <form action="{{ route('exam_fields.update', $examField->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Laravel sử dụng PUT cho update -->

        <div class="form-group">
            <label for="name">Tên lĩnh vực thi:</label>
            <input type="text" name="name" class="form-control" placeholder="mã khóa học" value="{{ old('name', $examField->name ?? '') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check">
            <input type="checkbox" name="is_practical" id="is_practical"
                class="form-check-input"
                value="1"
                {{ old('is_practical', $examField->is_practical ?? false) == 1 ? 'checked' : '' }}>
            <label for="is_practical" class="form-check-label">Là môn thực hành</label>
        </div>

        <div class="form-group">
            <label for="description">Mô tả:</label>
            <input type="text" name="description" class="form-control" placeholder="loại khóa học" value="{{ old('description', $examField->description ?? '') }}" required>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
@endsection
