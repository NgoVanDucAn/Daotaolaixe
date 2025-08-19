@extends('layouts.admin')

@section('title', 'Chỉnh sửa vi phạm')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('violations.update', $violation->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="violation_no" class="form-label">Mã vi phạm <span class="text-danger">*</span></label>
                    <input type="text" name="violation_no" id="violation_no" class="form-control @error('violation_no') is-invalid @enderror" value="{{ old('violation_no', $violation->violation_no) }}" required>
                    @error('violation_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="entities" class="form-label">Đối tượng vi phạm <span class="text-danger">*</span></label>
                    <input type="text" name="entities" id="entities" class="form-control @error('entities') is-invalid @enderror" value="{{ old('entities', $violation->entities) }}" required>
                    @error('entities')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả <span class="text-danger">*</span></label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $violation->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="fines" class="form-label">Mức phạt <span class="text-danger">*</span></label>
                <input type="text" name="fines" id="fines" class="form-control @error('fines') is-invalid @enderror" value="{{ old('fines', $violation->fines) }}" required>
                @error('fines')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="additional_penalties" class="form-label">Hình phạt bổ sung</label>
                    <input type="text" name="additional_penalties" id="additional_penalties" class="form-control @error('additional_penalties') is-invalid @enderror" value="{{ old('additional_penalties', $violation->additional_penalties) }}">
                    @error('additional_penalties')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="remedial" class="form-label">Biện pháp khắc phục</label>
                    <input type="text" name="remedial" id="remedial" class="form-control @error('remedial') is-invalid @enderror" value="{{ old('remedial', $violation->remedial) }}">
                    @error('remedial')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="other_penalties" class="form-label">Hình phạt khác</label>
                    <input type="text" name="other_penalties" id="other_penalties" class="form-control @error('other_penalties') is-invalid @enderror" value="{{ old('other_penalties', $violation->other_penalties) }}">
                    @error('other_penalties')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Hình ảnh</label>
                    <input type="text" name="image" id="image" class="form-control @error('image') is-invalid @enderror" value="{{ old('image', $violation->image) }}">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="keyword" class="form-label">Từ khóa</label>
                <input type="text" name="keyword" id="keyword" class="form-control @error('keyword') is-invalid @enderror" value="{{ old('keyword', $violation->keyword) }}">
                @error('keyword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="type_id" class="form-label">Loại phương tiện <span class="text-danger">*</span></label>
                    <select name="type_id" id="type_id" class="form-control @error('type_id') is-invalid @enderror" required>
                        <option value="">Chọn loại phương tiện</option>
                        @foreach ($vehicleTypes as $vehicleType)
                            <option value="{{ $vehicleType->id }}" {{ old('type_id', $violation->type_id) == $vehicleType->id ? 'selected' : '' }}>{{ $vehicleType->vehicle_name }}</option>
                        @endforeach
                    </select>
                    @error('type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="topic_id" class="form-label">Chủ đề <span class="text-danger">*</span></label>
                    <select name="topic_id" id="topic_id" class="form-control @error('topic_id') is-invalid @enderror" required>
                        <option value="">Chọn chủ đề</option>
                        @foreach ($topics as $topic)
                            <option value="{{ $topic->id }}" {{ old('topic_id', $violation->topic_id) == $topic->id ? 'selected' : '' }}>{{ $topic->topic_name }}</option>
                        @endforeach
                    </select>
                    @error('topic_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Bookmarks</label>
                <div id="bookmarks-container">
                    @foreach ($violation->bookmarks as $index => $bookmark)
                        <div class="bookmark-row mb-3 border p-3 rounded">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" name="bookmarks[{{ $index }}][bookmark_code]" class="form-control @error('bookmarks.' . $index . '.bookmark_code') is-invalid @enderror" value="{{ $bookmark->bookmark_code }}" placeholder="Mã bookmark" required>
                                    @error('bookmarks.' . $index . '.bookmark_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <select name="bookmarks[{{ $index }}][bookmark_type_id]" class="form-control @error('bookmarks.' . $index . '.bookmark_type_id') is-invalid @enderror" required>
                                        <option value="">Chọn loại bookmark</option>
                                        @foreach ($bookmarkTypes as $bookmarkType)
                                            <option value="{{ $bookmarkType->id }}" {{ $bookmark->bookmark_type_id == $bookmarkType->id ? 'selected' : '' }}>{{ $bookmarkType->bookmark_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('bookmarks.' . $index . '.bookmark_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="bookmarks[{{ $index }}][bookmark_description]" class="form-control @error('bookmarks.' . $index . '.bookmark_description') is-invalid @enderror" value="{{ $bookmark->bookmark_description }}" placeholder="Mô tả bookmark" required>
                                    @error('bookmarks.' . $index . '.bookmark_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-bookmark">Xóa</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary mt-2" id="add-bookmark">Thêm Bookmark</button>
            </div>
            <div class="mb-3">
                <label for="related_violation_ids" class="form-label">Vi phạm liên quan</label>
                <select name="related_violation_ids[]" id="related_violation_ids" class="form-control @error('related_violation_ids') is-invalid @enderror" multiple>
                    @foreach ($violations as $related)
                        <option value="{{ $related->id }}" {{ $violation->relatedViolations->pluck('id')->contains($related->id) ? 'selected' : '' }}>{{ $related->violation_no }}: {{ Str::limit($related->description, 50) }}</option>
                    @endforeach
                </select>
                @error('related_violation_ids')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('violations.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#related_violation_ids').select2({
                placeholder: "Chọn vi phạm liên quan",
                allowClear: true
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            // Truyền bookmarkTypes từ Blade sang JavaScript
            const bookmarkTypes = @json($bookmarkTypes);
            let bookmarkIndex = document.querySelectorAll('.bookmark-row').length;

            // Kiểm tra nút add-bookmark
            const addBookmarkButton = document.getElementById('add-bookmark');
            if (addBookmarkButton) {
                addBookmarkButton.addEventListener('click', function () {
                    console.log('Add bookmark clicked, index:', bookmarkIndex); // Debug

                    const container = document.getElementById('bookmarks-container');
                    const row = document.createElement('div');
                    row.className = 'bookmark-row mb-3 border p-3 rounded';
                    row.innerHTML = `
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="bookmarks[${bookmarkIndex}][bookmark_code]" class="form-control" placeholder="Mã bookmark" required>
                            </div>
                            <div class="col-md-3">
                                <select name="bookmarks[${bookmarkIndex}][bookmark_type_id]" class="form-control" required>
                                    <option value="">Chọn loại bookmark</option>
                                    ${bookmarkTypes.map(type => `
                                        <option value="${type.id}">${type.bookmark_name}</option>
                                    `).join('')}
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="bookmarks[${bookmarkIndex}][bookmark_description]" class="form-control" placeholder="Mô tả bookmark" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-bookmark">Xóa</button>
                            </div>
                        </div>
                    `;
                    container.appendChild(row);
                    bookmarkIndex++;
                });
            } else {
                console.error('Element with ID add-bookmark not found');
            }

            // Xử lý xóa bookmark
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-bookmark')) {
                    e.target.closest('.bookmark-row').remove();
                }
            });
        });
        </script>
@endsection