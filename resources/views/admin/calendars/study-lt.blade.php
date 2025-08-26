@extends('layouts.admin')

@section('content')

@include('admin.calendars.study-common', [
    'study' => 'lt',
    'description' => 'Lý thuyết',
    'titleModal'=>'Thêm lịch lý thuyết - cabin',
    'titleQuantity' => 'Tổng số lượng học viên',
    'detailRoute' => 'calendars.study-lt-detail',
    'removeFilter'=>'calendars.study-lt',
    'typeAccept' => 'study_theory',
    'courseType' => '3'
])

@endsection
