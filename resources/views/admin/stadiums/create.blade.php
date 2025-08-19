@extends('layouts.admin')

@section('content')
<div class="container">
    <form action="{{ route('stadiums.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="location" class="form-label">Địa điểm</label>
            <textarea name="location" class="form-control" required>{{ old('location') }}</textarea>
            @error('location')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="google_maps_url" class="form-label">Google Maps URL</label>
            <input type="url" name="google_maps_url" class="form-control" value="{{ old('google_maps_url') }}">
            @error('google_maps_url')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="note" class="form-label">Ghi chú</label>
            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Lưu</button>
    </form>
</div>
@endsection
