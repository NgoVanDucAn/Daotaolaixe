@extends('layouts.admin')

@section('content')
    <a href="{{ route('curriculums.create') }}" 
        class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
        style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 1%; z-index: 99;">
            +
    </a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>STT</th>
                <th>Tên giáo trình</th>
                <th>Loại bằng</th>
                <th>Loại giáo trình</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @php
                $key = ($curriculums->currentPage() - 1) * $curriculums->perPage();
            @endphp
            @foreach ($curriculums as $curriculum)
                <tr>
                    <td>
                        <button class="btn btn-info toggle-details" data-curriculum-id="{{ $curriculum->id }}">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </td>
                    <td>{{ ++$key }}</td>
                    <td>{{ $curriculum->name }}</td>
                    <td>{{ $curriculum->rank_name }}</td>
                    <td>{{ $curriculum->title }}</td>
                    <td>{{ $curriculum->description }}</td>
                    <td>
                        <a href="{{ route('curriculums.edit', $curriculum) }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-user-pen"></i></a>
                        <a href="{{ route('curriculums.show', $curriculum) }}" class="btn btn-info btn-sm"><i class="fa-solid fa-eye"></i></a>
                        <form action="{{ route('curriculums.destroy', $curriculum) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </td>
                </tr>
                <tr class="details-row" id="details-{{ $curriculum->id }}" style="display:none;">
                    <td colspan="7">
                        <div>
                            <h5>Bài học (Lessons)</h5>
                            @if($curriculum->lessons->isNotEmpty())
                                <table class="table mt-3 table-dark table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tên bài học</th>
                                            <th>Trình tự bài học</th>
                                            <th>Mô tả</th>
                                            <th>Trạng thái hiển thị</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($curriculum->lessons as $lesson)
                                            <tr>
                                                <td>{{ $lesson->title }}</td>
                                                <td>{{ $lesson->sequence }}</td>
                                                <td>{{ $lesson->description }}</td>
                                                <td>{{ $lesson->visibility }}</td>
                                                <td>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">Không có bài học nào.</p>
                            @endif
                        </div>

                        <div>
                            <h5>Quiz Sets</h5>
                            @php
                                $quizSets = $curriculum->lessons->pluck('quizSets')->flatten();
                            @endphp
                            @if($quizSets->isNotEmpty())
                                <table class="table mt-3 table-dark table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tên bộ câu hỏi</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($quizSets as $quizSet)
                                            <tr>
                                                <td>{{ $quizSet->name }}</td>
                                                <td>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">Không có bộ câu hỏi nào.</p>
                            @endif
                        </div>

                        <div>
                            <h5>Assignments</h5>
                            @php
                                $assignments = $quizSets->pluck('assignments')->flatten();
                            @endphp
                            @if($assignments->isNotEmpty())
                                <table class="table mt-3 table-dark table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tên bộ câu hỏi</th>
                                            <th>Mô tả</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($assignments as $assignment)
                                            <tr>
                                                <td>{{ $assignment->title }}</td>
                                                <td>{{ $assignment->description }}</td>
                                                <td>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">Không có bài tập nào.</p>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $curriculums->links('pagination::bootstrap-5') }}

    <script>
        document.querySelectorAll('.toggle-details').forEach(button => {
            button.addEventListener('click', function() {
                const curriculumId = this.getAttribute('data-curriculum-id');
                const detailsRow = document.getElementById(`details-${curriculumId}`);
                
                if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                    detailsRow.style.display = 'table-row';
                } else {
                    detailsRow.style.display = 'none';
                }

                const icon = this.querySelector('i');
                icon.classList.toggle('fa-chevron-down');
                icon.classList.toggle('fa-chevron-up');
            });
        });
    </script>
@endsection
