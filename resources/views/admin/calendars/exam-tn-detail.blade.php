@extends('layouts.admin')

@section('content')

@include('admin.calendars.exam-detail-common', [
    'exam' => 'tn',
    'description' => 'Tốt nghiệp',
    'back' => 'calendars.exam-tn',
    'removeFilter'=> 'calendars.exam-tn-detail',
    'typeAccept' => 'exam_graduation',
    'courseType' => 1
])
@endsection
