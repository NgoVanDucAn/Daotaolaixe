@extends('layouts.admin')

@section('content')

@include('admin.calendars.exam-detail-common', [
    'exam' => 'lt',
    'description' => 'Lý thuyết',
    'back' => 'calendars.exam-hmlt',
    'removeFilter'=> 'calendars.exam-hmlt-detail',
    'typeAccept' => 'exam_theory',
    'courseType' => 6
])
@endsection
