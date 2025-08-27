<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $student_id
 * @property int $course_id
 * @property int $learning_field_id
 * @property float $hours tổng số giờ học
 * @property float $km số km chạy được
 * @property float $night_hours số giờ chạy đêm
 * @property float $auto_hours số giờ chạy tự động
 * @property int $status 0: chưa hoàn thành, 1: đã hoàn thành, 2: đã bỏ
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course $course
 * @property-read \App\Models\LearningField $learningField
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereAutoHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereLearningFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereNightHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StudentStatus extends Model
{
    use HasFactory;

    protected $table = 'student_statuses';

    protected $fillable = [
        'course_student_id',
        'learning_field_id',
        'hours',
        'km',
        'night_hours',
        'auto_hours',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courseStudent()
    {
        return $this->belongsTo(CourseStudent::class, 'course_student_id');
    }

    public function learningField()
    {
        return $this->belongsTo(\App\Models\LearningField::class, 'learning_field_id');
    }

}
