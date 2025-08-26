@extends('layouts.admin')

@section('content')

@include('admin.calendars.exam-detail-common', [
    'exam' => 'th',
    'description' => 'Thực hành',
    'back' => 'calendars.exam-hmth',
    'removeFilter'=> 'calendars.exam-hmth-detail',
    'typeAccept' => 'exam_theory',
    'courseType' => 6
])
@endsection
