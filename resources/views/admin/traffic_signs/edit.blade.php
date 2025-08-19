@extends('layouts.admin')

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('traffic-signs.index'),
            'title' => 'Quay về trang quản lý biển báo'
        ])
    </div>
@endsection

@section('content')
<div class="container mt-4">
    <form action="{{ route('traffic-signs.update', $trafficSign->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="type" class="form-label">Loại Biển Báo</label>
            <select name="type" class="form-control @error('type') is-invalid @enderror">
                <option value="">-- Chọn loại --</option>
                @foreach(\App\Models\TrafficSign::getTypeOptions() as $key => $label)
                    <option value="{{ $key }}" {{ old('type', $trafficSign->type) == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Mã Biển Báo</label>
            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $trafficSign->code) }}">
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Tên Biển Báo</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $trafficSign->name) }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Hình ảnh</label>
            @if($trafficSign->image)
                <div class="mb-2">
                    <img src="{{ asset('storage/question/' . $trafficSign->image) }}" alt="Biển báo" width="150">
                </div>
            @endif
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $trafficSign->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('traffic-signs.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
