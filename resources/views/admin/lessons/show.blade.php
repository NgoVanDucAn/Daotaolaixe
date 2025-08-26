@extends('layouts.admin')

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('lessons.index'),
            'title' => 'Quay về trang quản lý bài học'
        ])
    </div>
@endsection

@section('content')
<div class="container">
    <h2>{{ $lesson->title }}</h2>
    <p><strong>Mô tả:</strong> {{ $lesson->description }}</p>
    <p><strong>Giáo trình</strong> {{ $lesson->curriculum->name }}</p>
    <p><strong>Trình tự trong giáo trình</strong> {{ $lesson->sequence }}</p>

    {{-- Hiển thị danh sách Quiz Sets và Assignments --}}
    <h3>Danh sách Quiz Sets</h3>
    @foreach($lesson->quizSets as $quizSet)
        <div class="card my-3">
            <div class="card-body">
                <h4>{{ $quizSet->name }}</h4>

                {{-- Hiển thị danh sách câu hỏi --}}
                <h5>Danh sách câu hỏi:</h5>
                <ul>
                    @foreach($quizSet->quizzes as $quiz)
                        <li>
                            <strong>{{ $quiz->question }}</strong>
                            <ul>
                                @foreach($quiz->quizOptions as $option)
                                    <li>{{ $option->option_text }} 
                                        @if($option->is_correct) ✅ @endif
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>

                {{-- Hiển thị danh sách Assignments của Quiz Set --}}
                <h5>Danh sách Assignments của Quiz Set</h5>
                @if($quizSet->assignments->isEmpty())
                    <p>Không có bài tập nào cho quiz set này.</p>
                @else
                    @foreach($quizSet->assignments as $assignment)
                        <div class="card my-2">
                            <div class="card-body">
                                <h5>{{ $assignment->title }}</h5>
                                <p>{{ $assignment->description }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endforeach

    <a href="{{ route('lessons.index') }}" class="btn btn-primary">Quay lại danh sách bài học</a>
</div>
@endsection
