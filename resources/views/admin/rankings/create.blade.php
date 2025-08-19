@extends('layouts.admin')

@section('content')
    <div class="container">
        <form action="{{ route('rankings.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <select name="vehicle_type" class="form-control" required>
                    <option value="0" {{ old('vehicle_type') == 0 ? 'selected' : '' }}>Xe máy</option>
                    <option value="1" {{ old('vehicle_type') == 1 ? 'selected' : '' }}>Ô tô</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="lessons">Lessons</label>
                <select name="lessons[]" class="form-control select2" multiple>
                    @foreach($lessons as $lesson)
                        <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Chọn bộ câu hỏi",
            allowClear: true
        });
    });
</script>
@endsection