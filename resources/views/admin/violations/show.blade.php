@extends('layouts.admin')

@section('title', 'Chi tiết vi phạm')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Mã vi phạm:</strong> {{ $violation->violation_no }}</p>
                <p><strong>Mô tả:</strong> {{ $violation->description }}</p>
                <p><strong>Đối tượng vi phạm:</strong> {{ $violation->entities }}</p>
                <p><strong>Mức phạt:</strong> {{ $violation->fines }}</p>
                <p><strong>Hình phạt bổ sung:</strong> {{ $violation->additional_penalties ?? 'Không có' }}</p>
                <p><strong>Biện pháp khắc phục:</strong> {{ $violation->remedial ?? 'Không có' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Hình phạt khác:</strong> {{ $violation->other_penalties ?? 'Không có' }}</p>
                <p><strong>Hình ảnh:</strong> {{ $violation->image ?? 'Không có' }}</p>
                <p><strong>Từ khóa:</strong> {{ $violation->keyword ?? 'Không có' }}</p>
                <p><strong>Loại phương tiện:</strong> {{ optional($violation->vehicleType)->vehicle_name ?? 'Không xác định' }}</p>
                <p><strong>Chủ đề:</strong> {{ optional($violation->topic)->topic_name ?? 'Không xác định' }}</p>
            </div>
        </div>
        <hr>
        <h5>Bookmarks:</h5>
        @if ($violation->bookmarks->isNotEmpty())
            <ul class="list-group mb-3">
                @foreach ($violation->bookmarks as $bookmark)
                    <li class="list-group-item">{{ $bookmark->bookmark_code }} ({{ optional($bookmark->bookmarkType)->bookmark_name }}): {{ $bookmark->bookmark_description }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Không có bookmark.</p>
        @endif
        <h5>Vi phạm liên quan:</h5>
        @if ($violation->relatedViolations->isNotEmpty())
            <ul class="list-group">
                @foreach ($violation->relatedViolations as $related)
                    <li class="list-group-item">{{ $related->violation_no }}: {{ $related->description }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Không có vi phạm liên quan.</p>
        @endif
        <div class="mt-4">
            <a href="{{ route('violations.index') }}" class="btn btn-secondary">Quay lại</a>
            <a href="{{ route('violations.edit', $violation->id) }}" class="btn btn-warning">Chỉnh sửa</a>
        </div>
    </div>
</div>
@endsection