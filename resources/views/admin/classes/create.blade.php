@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Create Class</h1>
        <form action="{{ route('classes.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Course</label>
                <select name="course_id" class="form-control select2" required>
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Students</label>
                <select name="students[]" class="form-control select2" multiple>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" 
                            {{ in_array($student->id, old('students', [])) ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Users</label>
                <select name="users[]" class="form-control select2" multiple>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" 
                            {{ in_array($user->id, old('users', [])) ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Start Date</label>
                <input type="date" placeholder="dd/mm/yyyy"name="start_date" class="form-control real-date" autocomplete="off">
            </div>
            <div class="mb-3">
                <label>End Date</label>
                <input type="date" placeholder="dd/mm/yyyy"name="end_date" class="form-control real-date" autocomplete="off">
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select[multiple]').select2({
            placeholder: "Chọn nhiều",
            allowClear: true
            });

            $('select:not([multiple])').select2({
                placeholder: "Chọn một giá trị",
                allowClear: true
            });
        });
    </script>
@endsection
