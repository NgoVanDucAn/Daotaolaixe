@extends('layouts.admin')

@section('content')
    <form action="{{ route('curriculums.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name" class="form-label">Tên giáo trình:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="rank_name" class="form-label">Loại bằng:</label>
            <input type="text" name="rank_name" id="rank_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="title" class="form-label">Loại giáo trình:</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Mô tả:</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        
        <button type="submit" class="btn btn-success mt-3">Create</button>
    </form>
@endsection
