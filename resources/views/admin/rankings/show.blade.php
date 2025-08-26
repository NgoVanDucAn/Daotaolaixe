@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Rank GPS Details</h2>
        <p><strong>ID:</strong> {{ $rank_gp->id }}</p>
        <p><strong>Tên:</strong> {{ $rank_gp->name }}</p>
        <p><strong>Mô tả:</strong> {{ $rank_gp->description }}</p>

        <h3 class="mt-4">Giáo trình giảng dạy</h3>
        @if($curriculums->isEmpty())
            <p>Không có giáo trình giảng dạy nào với hạng bằng này</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($curriculums as $curriculum)
                        <tr>
                            <td>{{ $curriculum->title }}</td>
                            <td>{{ $curriculum->description }}</td>
                            <td>
                                <a href="{{ route('curriculums.show', $curriculum) }}" class="btn btn-info btn-sm">Xem</a>
                                <a href="{{ route('curriculums.edit', $curriculum) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <form action="{{ route('curriculums.destroy', $curriculum) }}" method="POST" style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <a href="{{ route('rank_gps.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
    </div>
@endsection
