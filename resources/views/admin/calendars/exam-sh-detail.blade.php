@extends('layouts.admin')

@section('content')

@include('admin.calendars.exam-detail-common', [
    'exam' => 'sh',
    'description' => 'Sát hạch',
    'back' => 'calendars.exam-sh',
    'removeFilter'=> 'calendars.exam-sh-detail',
    'typeAccept' => 'exam_certification',
    'courseType' => 2
])
@endsection
