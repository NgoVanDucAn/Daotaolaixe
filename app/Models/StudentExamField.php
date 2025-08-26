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
 * @property int $exam_field_id
 * @property int $attempt_number Số lần đã thi
 * @property int $status lưu lại trạng thái môn thi đó: 0 là chưa hoàn thành, 1 là hoàn thành
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course $course
 * @property-read \App\Models\ExamField $examField
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereAttemptNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereExamFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StudentExamField extends Model
{
    use HasFactory;

    protected $table = 'student_exam_fields';

    protected $fillable = [
        'student_id',
        'course_id',
        'exam_field_id',
        'type_exam',
        'attempt_number',
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

    public function examField()
    {
        return $this->belongsTo(ExamField::class);
    }
}
