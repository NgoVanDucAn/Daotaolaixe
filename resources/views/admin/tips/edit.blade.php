@extends('layouts.admin')

@section('content')
<div class="container">
    <form action="{{ route('tips.update', $tip->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="tip_type" class="form-label">Loại mẹo</label>
            <select name="tip_type" id="tip_type" class="form-select @error('tip_type') is-invalid @enderror" required>
                <option value="1" {{ $tip->tip_type == 1 ? 'selected' : '' }}>Ô tô</option>
                <option value="2" {{ $tip->tip_type == 2 ? 'selected' : '' }}>Xe máy</option>
                <option value="3" {{ $tip->tip_type == 3 ? 'selected' : '' }}>Mô phỏng</option>
            </select>
            @error('tip_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="program_id" class="form-label">Chương</label>
            <select name="{{ $tip->tip_type == 3 ? 'page_id' : 'quiz_set_id' }}" id="program_id" class="form-select @error($tip->tip_type == 3 ? 'page_id' : 'quiz_set_id') is-invalid @enderror" required>
                <option value="" disabled {{ !$tip->quiz_set_id && !$tip->page_id ? 'selected' : '' }}>Chọn chương</option>
                @if ($tip->tip_type == 3)
                    @if ($pages->isEmpty())
                        <option value="">Không có chương</option>
                    @else
                        @foreach ($pages as $page)
                            <option value="{{ $page->id }}" {{ $tip->page_id == $page->id ? 'selected' : '' }}>
                                {{ $page->chapter_name }} - {{ $page->title }}
                            </option>
                        @endforeach
                    @endif
                @elseif ($tip->tip_type == 1)
                    @if ($quizSetsType1->isEmpty())
                        <option value="">Không có chương</option>
                    @else
                        @foreach ($quizSetsType1 as $set)
                            <option value="{{ $set->id }}" {{ $tip->quiz_set_id == $set->id ? 'selected' : '' }}>
                                {{ $set->name }} - {{ $set->description }}
                            </option>
                        @endforeach
                    @endif
                @elseif ($tip->tip_type == 2)
                    @if ($quizSetsType2->isEmpty())
                        <option value="">Không có chương</option>
                    @else
                        @foreach ($quizSetsType2 as $set)
                            <option value="{{ $set->id }}" {{ $tip->quiz_set_id == $set->id ? 'selected' : '' }}>
                                {{ $set->name }} - {{ $set->description }}
                            </option>
                        @endforeach
                    @endif
                @endif
            </select>
            @error($tip->tip_type == 3 ? 'page_id' : 'quiz_set_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Nội dung mẹo</label>
            <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="2" required>{{ old('content', $tip->content) }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="question" class="form-label">Danh sách câu hỏi</label>
            <textarea name="question" id="question" class="form-control @error('question') is-invalid @enderror" rows="2" required>{{ old('question', is_array($tip->question) ? implode("\n", $tip->question) : $tip->question) }}</textarea>
            <small class="form-text text-muted">Nhập mỗi câu hỏi trên một dòng.</small>
            @error('question')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('tips.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

<style>
    /* Responsive cho select và textarea trên mobile */
    @media (max-width: 576px) {
        .form-select, .form-control {
            font-size: 14px;
        }
        .mb-3 {
            margin-bottom: 1rem !important;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipTypeSelect = document.getElementById('tip_type');
    const programIdSelect = document.getElementById('program_id');

    if (!tipTypeSelect || !programIdSelect) {
        console.error('Không tìm thấy #tip_type hoặc #program_id');
        return;
    }

    // Dữ liệu chương từ Blade
    const programs = {
        '1': [
            @if (!$quizSetsType1->isEmpty())
                @foreach ($quizSetsType1 as $set)
                    {
                        id: '{{ $set->id }}',
                        text: '{{ addslashes($set->name . ' - ' . $set->description) }}',
                        selected: {{ $tip->quiz_set_id == $set->id ? 'true' : 'false' }}
                    },
                @endforeach
            @endif
        ],
        '2': [
            @if (!$quizSetsType2->isEmpty())
                @foreach ($quizSetsType2 as $set)
                    {
                        id: '{{ $set->id }}',
                        text: '{{ addslashes($set->name . ' - ' . $set->description) }}',
                        selected: {{ $tip->quiz_set_id == $set->id ? 'true' : 'false' }}
                    },
                @endforeach
            @endif
        ],
        '3': [
            @if (!$pages->isEmpty())
                @foreach ($pages as $page)
                    {
                        id: '{{ $page->id }}',
                        text: '{{ addslashes($page->chapter_name . ' - ' . $page->title) }}',
                        selected: {{ $tip->page_id == $page->id ? 'true' : 'false' }}
                    },
                @endforeach
            @endif
        ]
    };

    function updateProgramOptions(tipType) {
        console.log('Updating programs for tip_type:', tipType); // Debug

        // Xóa tất cả option hiện tại
        programIdSelect.innerHTML = '';

        // Cập nhật name của select
        programIdSelect.name = tipType === '3' ? 'page_id' : 'quiz_set_id';

        // Thêm placeholder
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Chọn chương';
        placeholder.disabled = true;
        placeholder.selected = true;
        programIdSelect.appendChild(placeholder);

        // Lấy danh sách chương
        const programList = programs[tipType] || [];

        if (programList.length === 0) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'Không có chương';
            programIdSelect.appendChild(option);
        } else {
            programList.forEach(program => {
                const option = document.createElement('option');
                option.value = program.id;
                option.textContent = program.text;
                if (program.selected) {
                    option.selected = true;
                    placeholder.selected = false;
                }
                programIdSelect.appendChild(option);
            });
        }
    }

    // Khởi tạo danh sách chương
    updateProgramOptions(tipTypeSelect.value);

    // Lắng nghe sự kiện change
    tipTypeSelect.addEventListener('change', function () {
        console.log('tip_type changed to:', this.value); // Debug
        updateProgramOptions(this.value);
    });
});
</script>
@endsection