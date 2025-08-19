@extends('layouts.admin')

@section('content')

@include('admin.calendars.exam-common', [
    'exam' => 'lt',
    'description' => 'Lý thuyết',
    'detailRoute' => 'calendars.exam-hmlt-detail',
    'removeFilter'=>'calendars.exam-hmlt',
    'typeAccept' => 'exam_theory',
    'courseType' => 6
])

@endsection
