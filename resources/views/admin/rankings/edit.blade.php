@extends('layouts.admin')

@section('content')
    <div class="container">
        <form action="{{ route('rankings.update', $ranking) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $ranking->name }}" required>
            </div>
            <div class="form-group">
                <select name="vehicle_type" class="form-control" required>
                    <option value="0" {{ old('vehicle_type', $ranking->vehicle_type ?? '') == 0 ? 'selected' : '' }}>Xe máy</option>
                    <option value="1" {{ old('vehicle_type', $ranking->vehicle_type ?? '') == 1 ? 'selected' : '' }}>Ô tô</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control">{{ $ranking->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="lessons">Lessons</label>
                <select name="lessons[]" class="form-control select2" multiple>
                    @foreach($lessons as $lesson)
                        <option value="{{ $lesson->id }}" 
                            {{ in_array($lesson->id, $ranking->lessons->pluck('id')->toArray()) ? 'selected' : '' }}>
                            {{ $lesson->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
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