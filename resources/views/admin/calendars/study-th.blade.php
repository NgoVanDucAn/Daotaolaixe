@extends('layouts.admin')

@section('content')

@include('admin.calendars.study-common', [
    'study' => 'th',
    'description' => 'Thực hành',
    'titleQuantity' => 'Tổng số giáo viên',
    'titleModal'=>'Thêm lịch làm việc giáo viên',
    // 'btnAdd' => 'Tạo lịch học mới'
    'detailRoute' => 'calendars.study-th-detail',
    'removeFilter'=>'calendars.study-th',
    'typeAccept' => 'study_practice',
    'courseType' => '4'
])

@endsection
