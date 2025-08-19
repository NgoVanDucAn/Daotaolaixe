@extends('layouts.admin')

@section('content')

@include('admin.calendars.study-detail-common', [
    'study' => 'th',
    'back' => 'calendars.study-th',
    'removeFilter'=> route('calendars.study-th-detail'),
    'description' => 'Lịch làm việc giáo viên',
    'typeAccept' => 'study_practice'
])
@endsection
