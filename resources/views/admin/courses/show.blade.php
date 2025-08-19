@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">course Details</h1>

    <div class="card bg-white p-6 rounded-2xl shadow-lg">
        <p><strong>Mã khóa học:</strong> {{ $course->code }}</p>
        <p><strong>Giáo trình:</strong> {{ $course->curriculum->name ?? 'N/A' }}</p>
        <p><strong>Số hồ sơ báo cáo:</strong> {{ ucfirst($course->number_bc) ?? 'N/A' }}</p>
        <p><strong>Ngày bắt đầu báo cáo:</strong> {{ $course->date_bci ? $course->date_bci->format('d/m/Y') : 'N/A' }}</p>
        <p><strong>Ngày bắt đầu:</strong> {{ $course->start_date ? $course->start_date->format('d/m/Y') : 'N/A' }}</p>
        <p><strong>Ngày kết thúc:</strong> {{ $course->end_date ? $course->end_date->format('d/m/Y') : 'N/A' }}</p>
        <p><strong>Số lượng học viên:</strong> {{ $course->number_students ?? 'N/A' }}</p>
        <p><strong>Quyết định khóa học:</strong> {{ $course->decision_kg ?? 'N/A' }}</p>
        <p><strong>Số giờ học:</strong> {{ $course->duration ?? 'N/A' }}</p>
        <p><strong>Học phí:</strong> {{ $course->tuition_fee ?? 'N/A' }}</p> 
        <p><strong>Trạng thái:</strong>
                <span class="badge bg-info">{{ $course->status == 1 ? "Active" : "Inactive" }}</span>
        </p>
    </div>

    <a href="{{ route('courses.index') }}" class="btn btn-primary mt-4">Back To Courses List</a>
</div>
@endsection
