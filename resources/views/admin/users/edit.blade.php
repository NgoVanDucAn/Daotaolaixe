@extends('layouts.admin')

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('users.index'),
            'title' => 'Quay về trang người dùng'
        ])
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-body">
            @include('admin.users.edit-form', [
                'extraFields' => 'admin.users.role-edit',
            ])
        </div>
    </div>
</div>
@endsection
