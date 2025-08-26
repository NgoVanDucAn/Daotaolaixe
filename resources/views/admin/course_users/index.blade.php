@extends('layouts.admin')

@section('title', 'Quan ly nguoi dung khoa hoc')

@section('content')
    <div class="container">
        <a href="{{ route('course_users.create') }}" 
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 1%; z-index: 99;">
                +
        </a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Học viên</th>
                    <th>Khóa học</th>
                    <th>Ngày ký hợp đồng</th>
                    <th>Trạng thái thi lý thuyết</th>
                    <th>Trạng thái thi thực hành</th>
                    <th>Trạng thái thi tốt nghiệp</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $key = ($courseUsers->currentPage() - 1) * $courseUsers->perPage();
                @endphp
                @foreach ($courseUsers as $courseUser)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $courseUser->user->name }}</td>
                        <td>{{ $courseUser->course->name }}</td>
                        <td>{{ $courseUser->contract_date ? $courseUser->contract_date->format('d/m/Y') : 'Chưa có' }}</td>
                        <td>{{ $courseUser->theory_exam ? 'Đã thi' : 'Chưa thi' }}</td>
                        <td>{{ $courseUser->practice_exam ? 'Đã thi' : 'Chưa thi' }}</td>
                        <td>{{ $courseUser->graduation_exam ? 'Đã thi' : 'Chưa thi' }}</td>
                        <td>
                            <a href="{{ route('course_users.edit', $courseUser->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="{{ route('course_users.edit', $courseUser->id) }}" class="btn btn-info btn-sm">Xem</a>
                            <form action="{{ route('course_users.destroy', $courseUser->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $courseUsers->links('pagination::bootstrap-5') }}
    </div>
@endsection
