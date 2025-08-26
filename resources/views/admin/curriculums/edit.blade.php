@extends('layouts.admin')

@section('content')
    <form action="{{ route('curriculums.update', $curriculum) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name" class="form-label">Tên giáo trình:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $curriculum->name }}" required>
        </div>

        <div class="form-group">
            <label for="rank_name" class="form-label">Loại bằng:</label>
            <input type="text" name="rank_name" id="rank_name" class="form-control" value="{{ $curriculum->rank_name }}" required>
        </div>

        <div class="form-group">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $curriculum->title }}" required>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $curriculum->description }}</textarea>
        </div>
        
        <button type="submit" class="btn btn-warning mt-3">Cập nhật</button>
    </form>
@endsection
