<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name Tên lĩnh vực thi
 * @property string|null $description Mô tả lĩnh vực thi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamSchedule> $examSchedules
 * @property-read int|null $exam_schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentExamField> $studentExamFields
 * @property-read int|null $student_exam_fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentStatus> $studentStatuses
 * @property-read int|null $student_statuses_count
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExamField extends Model
{
    use HasFactory;

    protected $table = 'exam_fields';

    protected $fillable = [
        'name', 'is_practical', 'description',
    ];

    public function getTypeLabelAttribute()
    {
        return $this->is_practical ? 'Thực hành' : 'Lý thuyết';
    }

    public function scopePractical($query)
    {
        return $query->where('is_practical', true);
    }

    public function scopeTheory($query)
    {
        return $query->where('is_practical', false);
    }
    
    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_exam_field')->withPivot('hours', 'km')->withTimestamps();
    }

    public function studentExamFields()
    {
        return $this->hasMany(StudentExamField::class);
    }

    public function studentStatuses()
    {
        return $this->hasMany(StudentStatus::class);
    }

    public function examSchedules()
    {
        return $this->belongsToMany(ExamSchedule::class, 'exam_schedule_exam_field');
    }

    public function calendarStudentExamFields()
    {
        return $this->hasMany(CalendarStudentExamField::class, 'exam_field_id', 'id');
    }
}
