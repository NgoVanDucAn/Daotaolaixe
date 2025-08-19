@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('leadSource.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Tên nguồn:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('description') }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Mô tả:</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary mt-3">Tạo</button>
        </form>
    </div>
</div>
@endsection
