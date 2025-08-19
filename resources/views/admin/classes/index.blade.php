@extends('layouts.admin')

@section('content')
<div class="page-title-box">             
    <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2">
        <div class="flex-grow-1">
            <h4 class="font-18 mb-0">Danh sách lớp</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0);">Thanh điều hướng</a></li>
                <li class="breadcrumb-item active">Danh sách lớp</li>
            </ol>
        </div>
    </div>
</div>
    <a href="{{ route('classes.create') }}" 
        class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
        style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 1%; z-index: 99;">
            +
    </a>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Khóa học</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Học viên</th>
                <th>Người dùng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classes as $class)
                <tr>
                    <td>{{ $class->id }}</td>
                    <td>{{ $class->name }}</td>
                    <td>{{ $class->course->name }}</td>
                    <td>{{ $class->start_date ? $class->start_date->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $class->end_date  ? $class->end_date->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $class->student_count }}</td>
                    <td>{{ $class->user_count }}</td>
                    <td>
                        <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-warning">Sửa</a>
                        <a href="{{ route('classes.show', $class->id) }}" class="btn btn-success">Hiện</a>
                        <form action="{{ route('classes.destroy', $class->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $classes->links() }}
@endsection
