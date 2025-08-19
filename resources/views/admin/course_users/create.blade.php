@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Thêm mới khóa học người dùng</h1>
        
        <form action="{{ route('course_users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="user_id">Học viên</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">Chọn học viên</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="course_id">Khóa học</label>
                <select name="course_id" id="course_id" class="form-control" required>
                    <option value="">Chọn khóa học</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group position-relative">
                <label for="ngay_khai_giang">Ngày khai giảng</label>
                <input 
                    type="text" 
                    placeholder="dd/mm/yyyy"
                    name="ngay_khai_giang"
                    id="ngay_khai_giang" 
                    class="form-control real-date" autocomplete="off" 
                    value="{{ old('ngay_khai_giang') ? \Carbon\Carbon::parse(old('ngay_khai_giang'))->format('d/m/Y') : '' }}"
                >
            </div>

            <div class="form-group position-relative">
                <label for="ngay_be_giang">Ngày bế giảng</label>
                <input 
                    type="text" 
                    placeholder="dd/mm/yyyy"
                    name="ngay_be_giang" 
                    id="ngay_be_giang" 
                    class="form-control real-date" autocomplete="off" 
                    value="{{ old('ngay_be_giang') ? \Carbon\Carbon::parse(old('ngay_be_giang'))->format('d/m/Y') : '' }}"
                >
            </div>

            <div class="form-group position-relative">
                <label for="ngay_hoc_cabin">Ngày học cabin</label>
                <input 
                    type="text" 
                    placeholder="dd/mm/yyyy"
                    name="ngay_hoc_cabin" 
                    id="ngay_hoc_cabin" 
                    class="form-control real-date" autocomplete="off" 
                    value="{{ old('ngay_hoc_cabin') ? \Carbon\Carbon::parse(old('ngay_hoc_cabin'))->format('d/m/Y') : '' }}"
                >
            </div>

            <div class="form-group">
                <label for="exam_field_id">Lĩnh vực thi</label>
                <select name="exam_field_id" id="exam_field_id" class="form-control">
                    <option value="">Chọn lĩnh vực thi</option>
                    @foreach($examFields as $examField)
                        <option value="{{ $examField->id }}" {{ old('exam_field_id') == $examField->id ? 'selected' : '' }}>{{ $examField->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group position-relative">
                <label for="contract_date">Ngày ký hợp đồng</label>
                <input 
                    type="text" 
                    placeholder="dd/mm/yyyy"
                    name="contract_date" 
                    id="contract_date" 
                    class="form-control real-date" autocomplete="off" 
                    value="{{ old('contract_date') ? \Carbon\Carbon::parse(old('contract_date'))->format('d/m/Y') : '' }}"
                >
            </div>

            <div class="form-group">
                <label for="note">Ghi chú</label>
                <textarea name="note" id="note" class="form-control">{{ old('note') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('course_users.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection
