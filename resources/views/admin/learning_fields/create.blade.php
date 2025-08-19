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
    <form action="{{ route('learning_fields.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Tên</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="price">Giá (nghìn/giờ)</label>
            <input type="text" name="price" id="price" step="0.01" class="form-control currency-input" value="{{ old('price', 0) }}">
            @error('price') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
    
        <div class="mb-3">
            <label for="teaching_mode">Hình thức giảng dạy</label>
            <select name="teaching_mode" id="teaching_mode" class="form-control">
                <option value="0" {{ old('teaching_mode') == '0' ? 'selected' : '' }}>1 kèm 1</option>
                <option value="1" {{ old('teaching_mode') == '1' ? 'selected' : '' }}>Dạy nhiều người cùng lúc</option>
            </select>
            @error('teaching_mode') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-check">
            <input type="checkbox" name="is_practical" id="is_practical"
                   class="form-check-input"
                   value="1"
                   {{ old('is_practical') == 1 ? 'checked' : '' }}>
            <label for="is_practical" class="form-check-label">Là môn thực hành</label>
        </div>

        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Lưu</button>
    </form>
@endsection
