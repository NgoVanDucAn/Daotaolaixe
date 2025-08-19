@extends('layouts.admin')
@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('vehicles.index'),
            'title' => 'Quay về trang quản lý xe'
        ])
    </div>
@endsection
@section('content')
<div class="container- fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('vehicles.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="mb-3 col-12 col-md-4">
                        <label>Biển số</label>
                        <input type="text" name="license_plate" class="form-control @error('license_plate') is-invalid @enderror" value="{{ old('license_plate') }}" required>
                        @error('license_plate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3 col-12 col-md-4">
                        <label>Model</label>
                        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model') }}" required>
                        @error('model')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3 col-12 col-md-4">
                        <label>Hạng</label>
                        <select name="ranking_id" class="form-control @error('ranking_id') is-invalid @enderror" required>
                            <option value="">-- Chọn hạng --</option>
                            @foreach($rankings as $ranking)
                                <option value="{{ $ranking->id }}" {{ old('ranking_id') == $ranking->id ? 'selected' : '' }}>
                                    {{ $ranking->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('ranking_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3 col-12 col-md-4">
                        <label>Loại xe</label>
                        <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type') }}" required>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3 col-12 col-md-4">
                        <label>Màu sắc</label>
                        <input type="text" name="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color') }}" required>
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3 col-12 col-md-4">
                        <label>Số GPTL</label>
                        <input type="text" name="training_license_number" class="form-control @error('training_license_number') is-invalid @enderror" value="{{ old('training_license_number') }}">
                        @error('training_license_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3 col-12 col-md-4">
                        <label>Năm sản xuất</label>
                        <input type="number" name="manufacture_year" class="form-control @error('manufacture_year') is-invalid @enderror" value="{{ old('manufacture_year') }}" required>
                        @error('manufacture_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3 col-12 col-md-4">
                        <label>Ghi chú</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
        
                <button type="submit" class="btn btn-primary mt-3 ps-2 pe-2 pt-0 pb-0"><b><i class="mdi mdi-content-save fs-4"></i>Lưu</b></button>
                {{-- <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Hủy</a> --}}
            </form>
        </div>
    </div>
</div>
@endsection
