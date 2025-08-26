@extends('layouts.admin')

@section('content')

@include('admin.calendars.exam-common', [
    'exam' => 'th',
    'description' => 'Thực hành',
    'detailRoute' => 'calendars.exam-hmth-detail',
    'removeFilter'=>'calendars.exam-hmth',
    'typeAccept' => 'exam_practice',
    'courseType' => 5
])

@endsection
