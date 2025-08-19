@extends('layouts.admin')

@section('content')
    <h1>Chi Tiết Lớp: {{ $class->name }}</h1>
    <p><strong>Khóa Học:</strong> {{ $class->name }}</p>
    <p><strong>Ngày Bắt Đầu:</strong> {{ $class->start_date ? $class->start_date->format('d/m/Y') : 'N/A' }}</p>
    <p><strong>Ngày Kết Thúc:</strong> {{ $class->end_date ? $class->end_date->format('d/m/Y') : 'N/A'}}</p>
    <h3>Giáo viên</h3>
    <ul>
        @foreach($class->users as $user)
            <li>{{ $user->name }} - {{ $user->email }}</li>
        @endforeach
    </ul>
    <h3>Thông Tin Khóa Học</h3>
    <ul>
        <li><strong>Khóa học: </strong> {{ $class->course->code }}</li>
        <li><strong>Giáo trình: </strong> {{ $class->course->curriculum->rankGp->name . " " . $class->course->curriculum->title }}</li>
        <li><strong>Ngày bắt đầu: </strong> {{ $class->course->start_date ? $class->course->start_date->format('d/m/Y') : 'N/A' }}</li>
        <li><strong>Ngày kết thúc: </strong> {{ $class->course->end_date ? $class->course->end_date->format('d/m/Y') : 'N/A' }}</li>
    </ul>

    <h3>Danh Sách Học Sinh</h3>
    <table class="table mt-3" style="width: max-content; min-width: 100%;">
        <thead>
            <tr>
                <th>STT</th>
                <th>Họ và Tên</th>
                <th>Email</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($class->students as $index => $student)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>
                        <form action="{{ route('classes.removeStudent', [$class , $student]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('classes.index') }}" class="btn btn-secondary">Back</a>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
        Thêm Học Viên
    </button>


    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Thêm Học Viên vào Lớp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStudentForm" action="{{ route('classes.addStudent') }}" method="POST">
                        @csrf
                        <input type="hidden" name="classroom_id" value="{{ $class->id }}">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Chọn Học Viên</label>
                            <select name="student_id[]" id="student_id" class="form-control" multiple>
                                @foreach($addStudents as $addStudent)
                                    <option value="{{ $addStudent->id }}">{{ $addStudent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Thêm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select[multiple]').select2({
                placeholder: "Chọn nhiều",
                allowClear: true,
                dropdownParent: $('#addStudentModal')
            });

            $('#addStudentModal').on('shown.bs.modal', function () {
                $('#student_id').select2({
                    placeholder: "Chọn nhiều",
                    allowClear: true,
                    dropdownParent: $('#addStudentModal')
                });
            });
        });
    </script>
@endsection
