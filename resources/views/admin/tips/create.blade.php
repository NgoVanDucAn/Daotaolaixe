@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Thêm mẹo</h3>
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Đã có lỗi xảy ra:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <button type="button" class="btn btn-outline-primary mb-2" data-bs-toggle="modal" data-bs-target="#addPageModal">
        + Thêm chương mô phỏng
    </button>
    <form action="{{ route('tips.store') }}" method="POST">
        @csrf
        <div id="tips-wrapper">
            @php
                $tips = old('tips', [[]]); // Mặc định một tip-item nếu không có old data
            @endphp
            @foreach ($tips as $index => $tip)
                <div class="tip-item border p-3 mb-3">
                    <label><strong>Loại mẹo:</strong></label><br>
                    <div class="mb-2">
                        <label><input type="radio" name="tips[{{$index}}][type_tip]" value="1" class="type-tip-radio" {{ old("tips.$index.type_tip", 1) == 1 ? 'checked' : '' }}> Ô tô</label>
                        <label class="ms-3"><input type="radio" name="tips[{{$index}}][type_tip]" value="2" class="type-tip-radio" {{ old("tips.$index.type_tip") == 2 ? 'checked' : '' }}> Xe máy</label>
                        <label class="ms-3"><input type="radio" name="tips[{{$index}}][type_tip]" value="3" class="type-tip-radio" {{ old("tips.$index.type_tip") == 3 ? 'checked' : '' }}> Mô phỏng</label>
                    </div>
                    @error("tips.$index.type_tip")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <div class="select-wrapper">
                        {{-- Type 1 --}}
                        <select name="tips[{{$index}}][quiz_set_id]" class="form-select quizset-select type-1-select" {{ old("tips.$index.type_tip", 1) != 1 ? 'disabled' : '' }}>
                            <option value="">-- Chọn chương (ô tô) --</option>
                            @foreach ($quizSetsType1 as $set)
                                <option value="{{ $set->id }}" {{ old("tips.$index.quiz_set_id") == $set->id ? 'selected' : '' }}>{{ $set->name }} - {{ $set->description }}</option>
                            @endforeach
                        </select>
                        @error("tips.$index.quiz_set_id")
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        {{-- Type 2 --}}
                        <select name="tips[{{$index}}][quiz_set_id]" class="form-select quizset-select type-2-select {{ old("tips.$index.type_tip", 1) != 2 ? 'd-none' : '' }}" {{ old("tips.$index.type_tip", 1) != 2 ? 'disabled' : '' }}>
                            <option value="">-- Chọn chương (xe máy) --</option>
                            @foreach ($quizSetsType2 as $set)
                                <option value="{{ $set->id }}" {{ old("tips.$index.quiz_set_id") == $set->id ? 'selected' : '' }}>{{ $set->name }} - {{ $set->description }}</option>
                            @endforeach
                        </select>

                        {{-- Type 3 --}}
                        <div class="type-3-wrapper {{ old("tips.$index.type_tip", 1) != 3 ? 'd-none' : '' }}">
                            <select name="tips[{{$index}}][page_id]" class="form-select page-id-select" {{ old("tips.$index.type_tip", 1) != 3 ? 'disabled' : '' }}>
                                <option value="">-- Chọn chương mô phỏng --</option>
                                @forelse ($pages as $page)
                                    <option value="{{ $page->id }}" {{ old("tips.$index.page_id") == $page->id ? 'selected' : '' }}>{{ $page->chapter_name }} - {{ $page->title }}</option>
                                @empty
                                    <option value="" disabled>Chưa có chương mô phỏng</option>
                                @endforelse
                            </select>
                            @error("tips.$index.page_id")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Nội dung mẹo:</label>
                        <textarea name="tips[{{$index}}][content_question]" class="form-control" rows="2">{{ old("tips.$index.content_question") }}</textarea>
                        @error("tips.$index.content_question")
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Danh sách câu hỏi</label>
                        <textarea name="tips[{{$index}}][question]" class="form-control" rows="2">{{ old("tips.$index.question") }}</textarea>
                        @error("tips.$index.question")
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" id="add-tip" class="btn btn-success">+ Thêm mẹo khác</button>
        <button type="submit" class="btn btn-primary">Lưu tất cả</button>
    </form>
    <div class="modal fade" id="addPageModal" tabindex="-1" aria-labelledby="addPageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm chương mô phỏng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addPageForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="mb-3">
                            <label class="form-label">Tên chương</label>
                            <input type="text" class="form-control" name="chapter_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Hàm khởi tạo trạng thái select cho một tip-item
    function initializeTipItem(wrapper, tipIndex) {
        const typeTipRadios = wrapper.querySelectorAll(`input[name="tips[${tipIndex}][type_tip]"]`);
        let selectedType = '1'; // Mặc định là 1 (ô tô)

        typeTipRadios.forEach(radio => {
            if (radio.checked) {
                selectedType = radio.value;
            }
        });

        wrapper.querySelectorAll('.quizset-select, .type-3-wrapper select').forEach(el => {
            el.classList.add('d-none');
            el.disabled = true;
        });

        if (selectedType == 1) {
            const select = wrapper.querySelector('.type-1-select');
            select.classList.remove('d-none');
            select.disabled = false;
        } else if (selectedType == 2) {
            const select = wrapper.querySelector('.type-2-select');
            select.classList.remove('d-none');
            select.disabled = false;
        } else if (selectedType == 3) {
            const wrapperType3 = wrapper.querySelector('.type-3-wrapper');
            const select = wrapperType3.querySelector('select');
            wrapperType3.classList.remove('d-none');
            select.classList.remove('d-none');
            select.disabled = false;
        }
    }

    // Khởi tạo trạng thái cho tất cả tip-item
    document.querySelectorAll('.tip-item').forEach((wrapper, index) => {
        initializeTipItem(wrapper, index);
    });

    // Xử lý sự kiện thay đổi type-tip
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('type-tip-radio')) {
            const wrapper = e.target.closest('.tip-item');
            const type = e.target.value;
            const tipIndex = wrapper.querySelector('input[name$="[type_tip]"]').name.match(/tips\[(\d+)\]/)[1];

            wrapper.querySelectorAll('.quizset-select, .type-3-wrapper select').forEach(el => {
                el.classList.add('d-none');
                el.disabled = true;
            });

            if (type == 1) {
                const select = wrapper.querySelector('.type-1-select');
                select.classList.remove('d-none');
                select.disabled = false;
            } else if (type == 2) {
                const select = wrapper.querySelector('.type-2-select');
                select.classList.remove('d-none');
                select.disabled = false;
            } else if (type == 3) {
                const wrapperType3 = wrapper.querySelector('.type-3-wrapper');
                const select = wrapperType3.querySelector('select');
                wrapperType3.classList.remove('d-none');
                select.classList.remove('d-none');
                select.disabled = false;
            }
        }
    });

    // Thêm mẹo khác (clone tip-item)
    let tipIndex = {{ count(old('tips', [[]])) }};
    document.getElementById('add-tip').addEventListener('click', function () {
        const first = document.querySelector('.tip-item');
        const clone = first.cloneNode(true);

        clone.querySelectorAll('input, select, textarea').forEach(el => {
            if (el.name) {
                el.name = el.name.replace(/\[\d+\]/, `[${tipIndex}]`);
            }
            if (el.type !== 'radio') el.value = '';
            if (el.type === 'radio') el.checked = (el.value == 1);
        });

        // Xóa thông báo lỗi cũ trong clone
        clone.querySelectorAll('.text-danger').forEach(el => el.textContent = '');

        initializeTipItem(clone, tipIndex);
        document.getElementById('tips-wrapper').appendChild(clone);
        tipIndex++;
    });

    // Xử lý modal
    const modalSubmitButton = document.querySelector('#addPageModal .btn.btn-primary');
    modalSubmitButton.addEventListener('click', function (e) {
        e.preventDefault();
        const modalForm = document.querySelector('#addPageModal form');
        if (!modalForm) {
            console.error("Form không tồn tại trong modal!");
            return;
        }

        // Xóa lỗi cũ
        modalForm.querySelectorAll('.text-danger').forEach(el => el.textContent = '');

        const formData = new FormData(modalForm);
        const submitButton = modalForm.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        fetch("{{ route('pages.ajaxCreate') }}", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const page = data.page;
                document.querySelectorAll('select[name$="[page_id]"]').forEach(select => {
                    const option = document.createElement('option');
                    option.value = page.id;
                    option.textContent = `${page.chapter_name} - ${page.title}`;
                    select.appendChild(option);
                });
                const modal = bootstrap.Modal.getInstance(document.getElementById('addPageModal'));
                modal.hide();
                modalForm.reset();
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorEl = modalForm.querySelector(`#${field}_error`);
                        if (errorEl) {
                            errorEl.textContent = data.errors[field][0];
                        }
                    });
                } else {
                    alert("Có lỗi xảy ra khi thêm chương!");
                }
            }
        })
        .catch(error => {
            console.error("Lỗi:", error);
            alert("Lỗi khi gửi dữ liệu!");
        })
        .finally(() => {
            submitButton.disabled = false;
        });
    });
});
</script>
@endsection
