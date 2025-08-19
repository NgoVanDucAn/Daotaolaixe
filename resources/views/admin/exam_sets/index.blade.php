@extends('layouts.admin')

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
            href="{{ route('exam_sets.create') }}"
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Thêm bộ đề</span>
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <a href="{{ route('exam_sets.create') }}" 
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 1%; z-index: 99;">
            +
        </a>
        <div class="tab-content mt-5">
            <!-- Tabs for License Levels -->
            <ul class="nav nav-tabs" id="licenseLevelTabs" role="tablist">
                @foreach ($examSets as $licenseLevel => $types)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $licenseLevel }}-tab" data-bs-toggle="tab" href="#tab-{{ $licenseLevel }}" role="tab">
                        {{ $types['license_level_name'] ? mb_strtoupper($types['license_level_name']) : '' }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="licenseLevelTabsContent">
                @foreach ($examSets as $licenseLevel => $types)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $licenseLevel }}" role="tabpanel">
                        @foreach ($types['exam_sets'] as $type => $examSetsByType)
                            <div class="mt-3">
                                <h5>Loại Đề: {{ ucwords(str_replace('_', ' ', $type)) }}</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tên Bộ Đề</th>
                                            <th>Số Câu Hỏi</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($examSetsByType as $examSet)
                                            <tr>
                                                <td>{{ $examSet['exam_set_name'] }}</td>
                                                <td>{{ $examSet['quiz_count'] }}</td>
                                                <td>
                                                    <a href="{{ route('exam_sets.edit', $examSet['exam_set_id']) }}" class="btn btn-warning btn"><i class="fa-solid fa-user-pen"></i></a>
                                                    <button class="btn btn-info toggle-btn" data-bs-target="#examSetQuestions-{{ $examSet['exam_set_id'] }}" aria-expanded="false" aria-controls="examSetQuestions-{{ $examSet['exam_set_id'] }}"><i class="fa-solid fa-eye"></i></button>
                                                    <form action="{{ route('exam_sets.destroy', $examSet['exam_set_id']) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')"><i class="fa-solid fa-trash-can"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <!-- Collapse content for questions -->
                                            <tr class="collapse mt-2" id="examSetQuestions-{{ $examSet['exam_set_id'] }}">
                                                <td colspan="3">
                                                    <!-- Table to display quizzes under the exam set -->
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Câu Hỏi</th>
                                                                <th>Đáp Án</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($examSet['quizzes'] as $quiz)
                                                                <tr>
                                                                    <td>{{ $quiz['question'] }}</td>
                                                                    <td>
                                                                        <ul>
                                                                            @foreach ($quiz['options'] as $option)
                                                                                <li>{{ $option['option_text'] }} {{ $option['is_correct'] ? '(Đúng)' : '' }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap 5 JS & Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <!-- Custom Script for Toggle Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtns = document.querySelectorAll('.toggle-btn');
            toggleBtns.forEach(function (btn) {
                btn.addEventListener('click', function (event) {
                    const targetId = btn.getAttribute('data-bs-target');
                    const targetElement = document.querySelector(targetId);

                    // Kiểm tra xem bảng câu hỏi đã được mở hay chưa
                    if (targetElement.classList.contains('show')) {
                        // Nếu bảng câu hỏi đang mở, ẩn nó đi
                        targetElement.classList.remove('show');
                    } else {
                        // Trước khi mở bảng câu hỏi mới, ẩn tất cả các bảng câu hỏi khác
                        const allCollapseElements = document.querySelectorAll('.collapse.show');
                        allCollapseElements.forEach(function (collapse) {
                            collapse.classList.remove('show');
                        });

                        // Sau đó mở bảng câu hỏi hiện tại
                        targetElement.classList.add('show');
                    }

                    targetElement.scrollIntoView({
                        behavior: 'smooth', // Cuộn mượt mà
                        block: 'start'      // Đảm bảo cuộn lên phần đầu của phần tử
                    });
                });
            });

            // Đóng bảng câu hỏi khi người dùng nhấn ra ngoài
            document.addEventListener('click', function (event) {
                if (!event.target.closest('.collapse') && !event.target.closest('.toggle-btn')) {
                    const collapses = document.querySelectorAll('.collapse.show');
                    collapses.forEach(function (collapse) {
                        collapse.classList.remove('show');
                    });
                }
            });
        });
    </script>
@endsection
