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
 * @property \Illuminate\Support\Carbon|null $contract_date Ngày ký hợp đồng
 * @property string|null $contract_image
 * @property \Illuminate\Support\Carbon|null $graduation_date Ngày tốt nghiệp
 * @property int|null $teacher_id
 * @property int|null $stadium_id Sân tập
 * @property string|null $note Ghi chú thông tin đặc biệt liên quan đến khóa học và học viên
 * @property \Illuminate\Support\Carbon|null $health_check_date Ngày khám sức khỏe
 * @property int|null $sale_id
 * @property float $hours Số giờ đã học
 * @property string $km
 * @property int $status Trạng thái khóa học (active, inactive)
 * @property int|null $tuition_fee Học phí khóa học
 * @property \Illuminate\Support\Carbon|null $start_date Ngày khai giảng
 * @property \Illuminate\Support\Carbon|null $end_date Ngày bế giảng
 * @property \Illuminate\Support\Carbon|null $cabin_learning_date Ngày học cabin
 * @property int|null $exam_field_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course $course
 * @property-read \App\Models\ExamField|null $examField
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Fee> $fees
 * @property-read int|null $fees_count
 * @property-read \App\Models\Student $student
 * @property-read \App\Models\User|null $teacher
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereCabinLearningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereContractDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereContractImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereExamFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereGraduationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereHealthCheckDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent wherePracticeField($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereTuitionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CourseStudent extends Model
{
    use HasFactory;

    protected $table = 'course_students';

    protected $fillable = [
        'student_id',
        'course_id',
        'stadium_id',
        'reserved_chip_hours',
        'gifted_chip_hours',
        'contract_date',
        'contract_image',
        'graduation_date',
        'teacher_id',
        'note',
        'health_check_date',
        'sale_id',
        'hours',
        'km',
        'status',
        'tuition_fee',
        'start_date',
        'end_date',
        'cabin_learning_date',
        'exam_field_id',
    ];

    protected $casts = [
        'contract_date' => 'datetime',
        'graduation_date' => 'datetime',
        'health_check_date' => 'datetime',
        'cabin_learning_date' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

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
        return $this->belongsTo(ExamField::class, 'exam_field_id');
    }

    public function fees()
    {
        return $this->hasMany(Fee::class, 'course_student_id');
    }

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function sale()
    {
        return $this->belongsTo(User::class, 'sale_id');
    }

    public function examSchedule()
    {
        return $this->belongsTo(ExamSchedule::class, 'exam_schedule_id');
    }

    public function calendars()
    {
        return $this->belongsToMany(Calendar::class, 'calendar_course_student', 'course_student_id', 'calendar_id')
            ->withPivot([
                'score', 'correct_answers', 'exam_status',
                'attempt_number', 'exam_number', 'exam_fee_paid_at',
                'remarks', 'hours', 'km', 'night_hours', 'auto_hours', 'pickup'
            ])
            ->withTimestamps();
    }

    public function calendarExam()
    {
        return $this->belongsToMany(Calendar::class, 'calendar_student_exam_field', 'calendar_student_id', 'exam_field_id')
            ->withPivot([
                'attempt_number', 'answer_ratio', 'exam_all_status',
                'exam_status', 'remarks'
            ])
            ->withTimestamps();
    }

    public function studentStatuses()
    {
        return $this->hasMany(\App\Models\StudentStatus::class, 'course_student_id'); // ✅
    }

    public function studentExamFields()
    {
        return $this->hasMany(\App\Models\StudentExamField::class, 'course_student_id'); // ✅
    }

}
