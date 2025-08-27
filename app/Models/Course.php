<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int|null $ranking_id
 * @property string $code Mã khóa học
 * @property int|null $shlx_course_id ID của khóa học trên hệ thống sát hạch
 * @property string|null $course_system_code Mã khóa học trên hệ thống sát hạch
 * @property int|null $curriculum_id Loại khóa học như cơ bản nâng cao
 * @property string|null $number_bc Số hồ sơ quản lý khóa học, báo cáo
 * @property \Illuminate\Support\Carbon|null $date_bci Ngày bắt đầu báo cáo khóa học
 * @property \Illuminate\Support\Carbon|null $start_date Ngày bắt đầu khóa học
 * @property \Illuminate\Support\Carbon|null $end_date Ngày kết thúc khóa học
 * @property int|null $number_students Số lượng học viên
 * @property string|null $decision_kg Lưu thông tin quyết định giấy tờ liên quan của khóa học
 * @property int|null $duration Tổng số giờ học của khóa học
 * @property string $km số km cần để hoàn thành khóa học
 * @property string $required_km số km cần để hoàn thành khóa học
 * @property int|null $tuition_fee Học phí khóa học
 * @property int $status Trạng thái khóa học (active, inactive)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \App\Models\Curriculum|null $curriculum
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamField> $examFields
 * @property-read int|null $exam_fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Fee> $fees
 * @property-read int|null $fees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LearningField> $learningFields
 * @property-read int|null $learning_fields_count
 * @property-read \App\Models\Ranking|null $ranking
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentExamField> $studentExamFields
 * @property-read int|null $student_exam_fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentStatus> $studentStatuses
 * @property-read int|null $student_statuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCourseSystemCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCurriculumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDateBci($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDecisionKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereNumberBc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereNumberStudents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereRankingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereRequiredKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereShlxCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereTuitionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'shlx_course_id',
        'course_system_code',
        'curriculum_id',
        'ranking_id',
        'number_bc',
        'date_bci',
        'start_date',
        'end_date',
        'cabin_date',
        'dat_date',
        'number_students',
        'decision_kg',
        'duration',
        'km',
        'min_automatic_car_hours',
        'min_night_hours',
        'tuition_fee',
        'status',
        'duration_days',
    ];

    protected $casts = [
        'date_bci' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'cabin_date' => 'date',
        'dat_date' => 'date',
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }

    public function ranking()
    {
        return $this->belongsTo(Ranking::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')->withPivot('role')->withTimestamps();
    }

    public function calendars()
    {
        return $this->belongsToMany(Calendar::class, 'calendar_course')->withTimestamps();
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_students')
                    ->withPivot([
                        'id', 'contract_date', 'contract_image', 'graduation_date', 'teacher_id', 'stadium_id', 'note', 'health_check_date',
                        'sale_id', 'hours', 'km', 'status', 'tuition_fee',
                        'start_date', 'end_date', 'cabin_learning_date', 'exam_field_id', 'gifted_chip_hours', 'reserved_chip_hours'
                    ])
                    ->withTimestamps();
    }

    public static function countAndUpdateStudents($courseId)
    {
        $course = Course::findOrFail($courseId);

        $totalStudents = $course->students()->count();

        $course->update(['number_students' => $totalStudents]);

        return $totalStudents;
    }

    public function examFields()
    {
        return $this->belongsToMany(ExamField::class, 'course_exam_field');
    }

    public function learningFields()
    {
        return $this->belongsToMany(LearningField::class, 'course_learning_field')->withPivot('hours', 'km')->withTimestamps();
    }

}
