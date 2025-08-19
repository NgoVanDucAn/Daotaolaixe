@extends('layouts.admin')

@section('content')

@include('admin.calendars.exam-common', [
    'exam' => 'sh',
    'description' => 'Sát hạch',
    'detailRoute' => 'calendars.exam-sh-detail',
    'removeFilter'=>'calendars.exam-sh',
    'typeAccept' => 'exam_certification',
    'courseType' => 2
])
@endsection
