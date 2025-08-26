@extends('layouts.admin')

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('instructor.index'),
            'title' => 'Quay về trang quản lý giáo viên'
        ])
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @include('admin.users.edit-form', [
            'extraFields' => 'admin.users.instructor-feilds-edit',
        ])
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Chọn hạng bằng",
            allowClear: true
        });
    });
</script>
@endsection
