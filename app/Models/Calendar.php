<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $type Kiểu lịch exam: lịch học, kiểm tra, họp
 * @property string $name Tên lịch
 * @property int $status Trạng thái lịch: 0: chưa hoàn thành, 1: đang diễn ra, 2: đã hoàn thành
 * @property string $priority Mức độ ưu tiên
 * @property string|null $date_start Ngày bắt đầu
 * @property string|null $date_end Ngày kết thúc
 * @property int|null $duration Thời lượng
 * @property string|null $description Mô tả
 * @property int|null $learning_field_id
 * @property int|null $exam_field_id
 * @property string|null $exam_fee
 * @property string|null $exam_fee_deadline
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $location Địa điểm
 * @property int|null $stadium_id
 * @property int|null $exam_schedule_id
 * @property int|null $vehicle_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \App\Models\ExamField|null $examField
 * @property-read \App\Models\ExamSchedule|null $examSchedule
 * @property-read \App\Models\LearningField|null $learningField
 * @property-read \App\Models\Stadium|null $stadium
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @property-read \App\Models\Vehicle|null $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar query()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereExamFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereExamFeeDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereExamFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereExamScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereLearningFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereStadiumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereVehicleId($value)
 * @mixin \Eloquent
 */
class Calendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'level', 'date', 'time', 'name', 'status', 'priority', 'date_start', 'date_end', 'duration', 
        'description', 'learning_field_id', 'exam_field_id', 'exam_fee', 'exam_fee_deadline', 
        'location', 'stadium_id', 'exam_schedule_id', 'vehicle_id',
    ];

    protected $casts = [
        'date_start' => 'datetime',
        'date_end' => 'datetime',
        'date' => 'date',
        'exam_fee_deadline' => 'date',
    ];

    public function courseStudents()
    {
        return $this->belongsToMany(CourseStudent::class, 'calendar_course_student', 'calendar_id', 'course_student_id')
            ->withPivot([
                'score', 'correct_answers', 'exam_status', 'attempt_number', 'exam_number',
                'exam_fee_paid_at', 'remarks', 'hours', 'km', 'night_hours', 'auto_hours', 'pickup'
            ])
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'calendar_user', 'calendar_id', 'user_id')->withPivot('price_at_result','role', 'hours', 'km', 'night_hours', 'auto_hours')->withTimestamps();
    }

    public function examField()
    {
        return $this->belongsTo(ExamField::class);
    }

    public function learningField()
    {
        return $this->belongsTo(LearningField::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function examSchedule()
    {
        return $this->belongsTo(ExamSchedule::class);
    }
    
    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function examFieldsOfCalendar(): Attribute
    {
        return Attribute::get(function () {
            return ExamField::whereIn('id', function ($query) {
                $query->select('exam_field_id')
                    ->from('calendar_student_exam_field')
                    ->whereIn('calendar_student_id', function ($subQuery) {
                        $subQuery->select('id')
                            ->from('calendar_course_student')
                            ->where('calendar_id', $this->id);
                    });
            })->distinct()->get();
        });
    }

    public function calendarStudents()
    {
        return $this->hasMany(CalendarStudent::class, 'calendar_id');
    }
}
