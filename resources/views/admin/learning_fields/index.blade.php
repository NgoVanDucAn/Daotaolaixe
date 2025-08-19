@extends('layouts.admin')

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
            href="{{ route('learning_fields.create') }}" 
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Thêm lĩnh vực học</span>
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <a href="{{ route('learning_fields.create') }}" 
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 40px; height: 40px; font-size: 30px; bottom: 3%; right: 60px; z-index: 99;">
                +
        </a>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên</th>
                        <th>Giá thành giảng dạy</th>
                        <th>Hình thức giảng dạy</th>
                        <th>Loại môn học</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($learningFields as $field)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $field->name }}</td>
                            <td>{{ $field->price }}</td>
                            <td>
                                {{ ($field->teaching_mode == 0) ? '1 kèm 1' : 'Dạy nhiều người cùng lúc' }}
                            </td>
                            <td>{{ $field->type_label }}</td>
                            <td>{{ $field->description }}</td>
                            <td>
                                <a href="{{ route('learning_fields.edit', $field) }}" class="btn btn-warning ps-2 pe-2 pt-0 pb-0">
                                    <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                                </a>
                                <form action="{{ route('learning_fields.destroy', $field) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger ps-2 pe-2 pt-0 pb-0" onclick="return confirm('Xóa lĩnh vực học này?')">
                                        <i class="mdi mdi-trash-can-outline fs-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $learningFields->links() }}
        </div>
    </div>
</div>
@endsection
