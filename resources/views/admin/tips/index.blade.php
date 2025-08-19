@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('tips.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm mẹo..." value="{{ $search }}">
                        <button type="submit" class="btn btn-primary">Tìm</button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="type_tip" class="form-select" id="type_tip_select">
                        <option value="">Tất cả loại mẹo</option>
                        <option value="1" {{ $selectedTypeTip == 1 ? 'selected' : '' }}>Ô tô</option>
                        <option value="2" {{ $selectedTypeTip == 2 ? 'selected' : '' }}>Xe máy</option>
                        <option value="3" {{ $selectedTypeTip == 3 ? 'selected' : '' }}>Mô phỏng</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="program_id" class="form-select" id="program_id_select">
                        <option value="">Tất cả chương</option>
                        @if ($selectedTypeTip == 3)
                            @foreach ($pages as $page)
                                <option value="{{ $page->id }}" {{ $selectedProgramId == $page->id ? 'selected' : '' }}>{{ $page->chapter_name }} - {{ $page->title }}</option>
                            @endforeach
                        @elseif ($selectedTypeTip == 1)
                            @foreach ($quizSetsType1 as $set)
                                <option value="{{ $set->id }}" {{ $selectedProgramId == $set->id ? 'selected' : '' }}>{{ $set->name }} - {{ $set->description }}</option>
                            @endforeach
                        @elseif ($selectedTypeTip == 2)
                            @foreach ($quizSetsType2 as $set)
                                <option value="{{ $set->id }}" {{ $selectedProgramId == $set->id ? 'selected' : '' }}>{{ $set->name }} - {{ $set->description }}</option>
                            @endforeach
                        @else
                            @foreach ($quizSetsType1->merge($quizSetsType2) as $set)
                                <option value="{{ $set->id }}" {{ $selectedProgramId == $set->id ? 'selected' : '' }}>{{ $set->name }} - {{ $set->description }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('tips.index') }}" class="btn btn-secondary">Xóa bộ lọc</a>
                    <a href="{{ route('tips.create') }}" class="btn btn-primary">+ Thêm mẹo</a>
                    <a href="{{ route('tips.content') }}" class="btn btn-primary">Format Content</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs mb-3" id="tipsTabs" role="tablist">
            @foreach ([1 => 'Ô tô', 2 => 'Xe máy', 3 => 'Mô phỏng'] as $typeTip => $label)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $selectedTypeTip == $typeTip || ($selectedTypeTip == '' && $loop->first) ? 'active' : '' }}"
                            id="type-{{ $typeTip }}-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#type-{{ $typeTip }}"
                            type="button"
                            role="tab"
                            aria-controls="type-{{ $typeTip }}"
                            aria-selected="{{ $selectedTypeTip == $typeTip || ($selectedTypeTip == '' && $loop->first) ? 'true' : 'false' }}">
                        {{ $label }} ({{ collect($groupedTips[$typeTip])->sum('total_count') }})
                    </button>
                </li>
            @endforeach
        </ul>

    <!-- Nội dung tabs -->
        <div class="tab-content" id="tipsTabContent">
            @foreach ([1 => 'Ô tô', 2 => 'Xe máy', 3 => 'Mô phỏng'] as $typeTip => $label)
                <div class="tab-pane fade {{ $selectedTypeTip == $typeTip || ($selectedTypeTip == '' && $loop->first) ? 'show active' : '' }}"
                    id="type-{{ $typeTip }}"
                    role="tabpanel"
                    aria-labelledby="type-{{ $typeTip }}-tab">
                    @if (empty($groupedTips[$typeTip]))
                        <div class="alert alert-info">
                            Không tìm thấy mẹo nào cho {{ $label }}.
                        </div>
                    @else
                        <div class="accordion" id="programAccordion{{ $typeTip }}">
                            @foreach ($groupedTips[$typeTip] as $groupKey => $group)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $typeTip }}-{{ $groupKey }}">
                                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $typeTip }}-{{ $groupKey }}"
                                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                aria-controls="collapse{{ $typeTip }}-{{ $groupKey }}">
                                            @if ($groupKey == 'no_program')
                                                Không có chương
                                            @else
                                                {{ $group['program'] ? ($typeTip == 3 ? $group['program']->chapter_name . ' - ' . $group['program']->title : $group['program']->name . ' - ' . $group['program']->description) : 'N/A' }}
                                            @endif
                                            ({{ $group['total_count'] }} mẹo)
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $typeTip }}-{{ $groupKey }}"
                                        class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                        aria-labelledby="heading{{ $typeTip }}-{{ $groupKey }}"
                                        data-bs-parent="#programAccordion{{ $typeTip }}">
                                        <div class="accordion-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="content-column">Nội dung mẹo</th>
                                                            <th>Danh sách câu hỏi</th>
                                                            <th class="action-column">Hành động</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($group['tips'] as $tip)
                                                            <tr>
                                                                <td class="content-cell">{{ $tip->content }}</td>
                                                                <td>{{ implode(', ', $tip->question) }}</td>
                                                                <td>
                                                                    <a href="{{ route('tips.edit', $tip->id) }}" class="btn btn-sm btn-warning">Chỉnh sửa</a>
                                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $tip->id }}">Xóa</button>
                                                                </td>
                                                            </tr>

                                                            <!-- Modal xác nhận xóa -->
                                                            <div class="modal fade" id="deleteModal{{ $tip->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $tip->id }}" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="deleteModalLabel{{ $tip->id }}">Xác nhận xóa</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            Bạn có chắc muốn xóa mẹo "{{ $tip->content }}" không?
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                                            <form action="{{ route('tips.destroy', $tip->id) }}" method="POST" style="display:inline;">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-danger">Xóa</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Phân trang cho chương -->
                                            <div class="mt-3">
                                                {{ $group['tips']->appends(['search' => $search, 'type_tip' => $selectedTypeTip, 'program_id' => $selectedProgramId])->links('pagination::bootstrap-5') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .content-column {
        min-width: 300px;
    }

    .content-cell {
        white-space: normal;
        word-wrap: break-word;
        max-height: 100px;
        overflow-y: auto;
        font-size: 14px;
    }

    .action-column {
        min-width: 150px;
    }

    .accordion-body {
        overflow-x: auto;
        padding: 1rem;
    }

    @media (max-width: 576px) {
        .content-column {
            min-width: 200px;
        }
        .content-cell {
            font-size: 12px;
        }
        .action-column {
            min-width: 120px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Cập nhật danh sách chương khi thay đổi type_tip
    const typeTipSelect = document.getElementById('type_tip_select');
    const programIdSelect = document.getElementById('program_id_select');

    typeTipSelect.addEventListener('change', function () {
        const typeTip = this.value;
        programIdSelect.innerHTML = '<option value="">Tất cả chương</option>';

        if (typeTip == 3) {
            // Load Pages cho Mô phỏng
            @foreach ($pages as $page)
                const option = document.createElement('option');
                option.value = '{{ $page->id }}';
                option.textContent = '{{ $page->chapter_name }} - {{ $page->title }}';
                @if ($selectedProgramId == $page->id)
                    option.selected = true;
                @endif
                programIdSelect.appendChild(option);
            @endforeach
        } else if (typeTip == 1) {
            // Load QuizSets cho Ô tô
            @foreach ($quizSetsType1 as $set)
                const option = document.createElement('option');
                option.value = '{{ $set->id }}';
                option.textContent = '{{ $set->name }} - {{ $set->description }}';
                @if ($selectedProgramId == $set->id)
                    option.selected = true;
                @endif
                programIdSelect.appendChild(option);
            @endforeach
        } else if (typeTip == 2) {
            // Load QuizSets cho Xe máy
            @foreach ($quizSetsType2 as $set)
                const option = document.createElement('option');
                option.value = '{{ $set->id }}';
                option.textContent = '{{ $set->name }} - {{ $set->description }}';
                @if ($selectedProgramId == $set->id)
                    option.selected = true;
                @endif
                programIdSelect.appendChild(option);
            @endforeach
        } else {
            // Load tất cả QuizSets khi không chọn type_tip
            @foreach ($quizSetsType1->merge($quizSetsType2) as $set)
                const option = document.createElement('option');
                option.value = '{{ $set->id }}';
                option.textContent = '{{ $set->name }} - {{ $set->description }}';
                @if ($selectedProgramId == $set->id)
                    option.selected = true;
                @endif
                programIdSelect.appendChild(option);
            @endforeach
        }
    });
});
</script>
@endsection