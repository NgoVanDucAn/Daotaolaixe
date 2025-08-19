@extends('layouts.admin')

@section('content')

@include('admin.calendars.exam-common', [
    'exam' => 'tn',
    'description' => 'Tốt nghiệp',
    'detailRoute' => 'calendars.exam-tn-detail',
    'removeFilter'=>'calendars.exam-tn',
    'typeAccept' => 'exam_graduation',
    'courseType' => 1
])

@endsection
