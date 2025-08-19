@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">{{ $curriculum->name }}</h4>
                <p><strong>Loại bằng:</strong> {{ $curriculum->rank_name }}</p>
                <p><strong>Loại giáo trình:</strong> {{ $curriculum->title }}</p>
                <p><strong>Mô tả:</strong> {{ $curriculum->description }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h5>Courses</h5>
                @if($curriculum->courses->isNotEmpty())
                    <ul class="list-group">
                        @foreach($curriculum->courses as $course)
                            <li class="list-group-item">{{ $course->title }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Không có khóa học nào.</p>
                @endif
            </div>

            <div class="col-md-6">
                <h5>Lessons</h5>
                @if($curriculum->lessons->isNotEmpty())
                    <ul class="list-group">
                        @foreach($curriculum->lessons as $lesson)
                            <li class="list-group-item">{{ $lesson->title }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Không có bài học nào.</p>
                @endif
            </div>
        </div>

        <h5 class="mt-4">Assignments</h5>
        @php
            $assignments = $curriculum->lessons->pluck('quizSets')->flatten()->pluck('assignments')->flatten();
        @endphp

        @if($assignments->isNotEmpty())
            <ul class="list-group">
                @foreach($assignments as $assignment)
                    <li class="list-group-item">{{ $assignment->title }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Không có bài tập nào.</p>
        @endif

        <a href="{{ route('curriculums.index') }}" class="btn btn-secondary mt-4">Quay lại</a>
    </div>
@endsection
