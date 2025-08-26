<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarStudent extends Model
{
    use HasFactory;

    protected $table = 'calendar_course_student';

    protected $fillable = [
        'calendar_id',
        'student_id',
        'score',
        'correct_answers',
        'exam_status',
        'attempt_number',
        'exam_number',
        'exam_fee_paid_at',
        'remarks',
        'hours',
        'km',
        'night_hours',
        'auto_hours',
        'pickup',
    ];

    protected $casts = [
        'exam_fee_paid_at' => 'datetime',
        'pickup' => 'boolean',
        'hours' => 'float',
        'km' => 'float',
        'night_hours' => 'float',
        'auto_hours' => 'float',
    ];

    public function courseStudent()
    {
        return $this->belongsTo(CourseStudent::class);
    }

    public function examFields()
    {
        return $this->hasMany(CalendarStudentExamField::class, 'calendar_student_id', 'id');
    }

    public function student()
    {
        return $this->hasOneThrough(
            Student::class,
            CourseStudent::class,
            'id',
            'id',
            'course_student_id',
            'student_id'
        );
    }
}
