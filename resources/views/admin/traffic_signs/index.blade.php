@extends('layouts.admin')

@section('content')

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
            href="{{ route('traffic-signs.create') }}" 
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Thêm biển báo</span>
        </a>
    </div>
@endsection
<div class="card">
    <div class="card-body">
        <a href="{{ route('traffic-signs.create') }}" 
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 1%; z-index: 99;">
                +
        </a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Mã</th>
                <th>Tên</th>
                <th>Loại</th>
                <th>Mô tả</th>
                <th style="width: 120px">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($signs as $sign)
                <tr>
                    <td>
                        @if($sign->image)
                            <img src="{{ asset('storage/question/' . $sign->image) }}" alt="{{ $sign->name }}" width="60">
                        @else
                            Không có
                        @endif
                    </td>
                    <td>{{ $sign->code }}</td>
                    <td>{{ $sign->name }}</td>
                    <td>{{ $sign->type_label }}</td>
                    <td>{{ $sign->description }}</td>
                    <td>
                        <a href="{{ route('traffic-signs.edit', $sign->id) }}" class="btn btn-sm btn-warning ps-2 pe-2 pt-0 pb-0">
                            <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                        </a>
                        <form action="{{ route('traffic-signs.destroy', $sign->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger ps-2 pe-2 pt-0 pb-0">
                                <i class="mdi mdi-trash-can-outline fs-4"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $signs->links('pagination::bootstrap-5') }}
</div>
@endsection
