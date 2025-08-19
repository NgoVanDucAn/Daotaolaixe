@extends('layouts.admin')

@section('title', 'Danh sách Tips')

@section('content')
    <h1>Danh sách Tips</h1>

    <!-- Thông báo thành công -->
    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <!-- Kiểm tra nếu không có tips -->
    @empty($formattedTips)
        <p class="no-data">Không có tips nào trong cơ sở dữ liệu.</p>
    @else
        <!-- Bảng hiển thị tips -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Chuỗi gốc</th>
                    <th>Chuỗi đã định dạng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($formattedTips as $tip)
                    <tr>
                        <td>{{ $tip->id }}</td>
                        <td>{{ $tip->original_content }}</td>
                        <td>{{ $tip->formatted_content }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endempty
@endsection