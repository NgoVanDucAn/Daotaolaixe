@extends('layouts.admin')



@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('sale.index'),
            'title' => 'Quay về trang quản lý sale'
        ])
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @include('admin.users.edit-form', [
            'extraFields' => '',
        ])
    </div>
</div>
@endsection


