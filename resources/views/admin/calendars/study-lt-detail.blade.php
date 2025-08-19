@extends('layouts.admin')

@section('content')



@include('admin.calendars.study-detail-common', [
    'study' => 'lt',
    'back' => 'calendars.study-lt',
    'removeFilter'=> route('calendars.study-lt-detail'),
    'description' => 'Lịch học lý thuyết - cabin',
    'typeAccept' => 'study_theory'
])
@endsection
