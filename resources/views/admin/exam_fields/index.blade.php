@extends('layouts.admin')

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
            href="{{ route('exam_fields.create') }}" 
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Thêm địa điểm thi</span>
        </a>
    </div>
@endsection

@section('title', 'Danh sách lĩnh vực thi')
@section('content')
<div class="card">
    <div class="card-body">
        <a href="{{ route('exam_fields.create') }}" 
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 1%; z-index: 99;">
                +
        </a>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên lĩnh vực thi</th>
                        <th>Loại môn học</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $key = ($examFields->currentPage() - 1) * $examFields->perPage();
                    @endphp
                    @foreach ($examFields as $examField)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $examField->name }}</td>
                            <td>{{ $examField->type_label }}</td>
                            <td>{{ $examField->description }}</td>
                            <td>
                                <a href="{{ route('exam_fields.edit', $examField->id) }}" class="btn btn-warning"><i class="fa-solid fa-user-pen"></i></a>
                                <form action="{{ route('exam_fields.destroy', $examField->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $examFields->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
