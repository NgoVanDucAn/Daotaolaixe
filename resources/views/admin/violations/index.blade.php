@extends('layouts.admin')

@section('title', 'Danh sách vi phạm')

@section('content')
<a href="{{ route('violations.create') }}" class="btn btn-primary mb-3">Thêm vi phạm</a>
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Mã vi phạm</th>
                    <th>Mô tả</th>
                    <th>Đối tượng</th>
                    <th>Loại phương tiện</th>
                    <th>Chủ đề</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($violations as $violation)
                    <tr>
                        <td>{{ $violation->violation_no }}</td>
                        <td>{{ Str::limit($violation->description, 50) }}</td>
                        <td>{{ $violation->entities }}</td>
                        <td>{{ optional($violation->vehicleType)->vehicle_name ?? 'Không xác định' }}</td>
                        <td>{{ optional($violation->topic)->topic_name ?? 'Không xác định' }}</td>
                        <td>
                            <a href="{{ route('violations.show', $violation->id) }}" class="btn btn-info btn-sm">Xem</a>
                            <a href="{{ route('violations.edit', $violation->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('violations.destroy', $violation->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có vi phạm nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection