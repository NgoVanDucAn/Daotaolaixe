<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarStudentExamField extends Model
{
    use HasFactory;

    protected $table = 'calendar_student_exam_field';

    protected $fillable = [
        'calendar_student_id',
        'exam_field_id',
        'attempt_number',
        'answer_ratio',
        'exam_all_status',
        'exam_status',
        'remarks',
    ];

    public function calendarStudent()
    {
        return $this->belongsTo(CalendarStudent::class, 'calendar_student_id', 'id');
    }

    public function examField()
    {
        return $this->belongsTo(ExamField::class, 'exam_field_id', 'id');
    }
}
