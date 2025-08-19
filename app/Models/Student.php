<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Normalizer;

/**
 * 
 *
 * @property int $id
 * @property string $student_code Mã học viên
 * @property int|null $card_id Mã số thẻ được đính kèm khi thực hiện sát hạch
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string|null $image
 * @property string|null $gender
 * @property \Illuminate\Support\Carbon|null $dob Date of Birth
 * @property string|null $identity_card Identity Card Number
 * @property string|null $address
 * @property string $status User status
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $description Mô tả học viên, có thể lưu các thông tin khác như có quan tâm đến các bằng khác không, mức độ quan tâm
 * @property \Illuminate\Support\Carbon|null $became_student_at
 * @property bool $is_student
 * @property int|null $sale_support người chăm sóc khách hàng
 * @property int|null $lead_source nguồn khách hàng
 * @property int|null $converted_by người xác nhận đóng phí và chuyển đổi tài khoản khách hàng thành học viên
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $trainee_id id của học viên trên hệ thống sát hạch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \App\Models\User|null $convertedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Fee> $courseFees
 * @property-read int|null $course_fees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Fee> $fees
 * @property-read int|null $fees_count
 * @property-read \App\Models\LeadSource|null $leadSource
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lesson> $lessons
 * @property-read int|null $lessons_count
 * @property-read \App\Models\User|null $saleSupport
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentExamField> $studentExamFields
 * @property-read int|null $student_exam_fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentStatus> $studentStatuses
 * @property-read int|null $student_statuses_count
 * @method static \Illuminate\Database\Eloquent\Builder|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereBecameStudentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereConvertedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereIdentityCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereIsStudent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereLeadSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereSaleSupport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereStudentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereTraineeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Student extends Authenticatable
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'student_code', 'card_id', 'name', 'email', 'phone', 'image', 'gender', 'dob', 'identity_card',
        'address', 'date_of_profile_set', 'status', 'status_lead', 'password', 'description', 'became_student_at',
        'is_student', 'sale_support', 'lead_source', 'converted_by', 'trainee_id', 'interest_level', 'is_lead', 'ranking_id', 'fee_ranking', 'paid_fee'
    ];

    protected $casts = [
        'date_of_profile_set' => 'date',
        'dob' => 'date',
        'email_verified_at' => 'datetime',
        'became_student_at' => 'datetime',
        'is_student' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'interest_level_label',
        'status_lead_label',
    ];

    public static function getOptions(string $field): array
    {
        return match ($field) {
            'interest_level' => [
                1 => 'Thấp',
                2 => 'Trung bình',
                3 => 'Cao',
            ],
            'status_lead' => [
                1 => 'Mới',
                2 => 'Đã liên hệ',
                3 => 'Đã chuyển đổi',
                4 => 'Mất',
                5 => 'Đã đóng',
            ],
            default => [],
        };
    }

    public function getInterestLevelLabelAttribute(): string
    {
        return self::getOptions('interest_level')[$this->interest_level] ?? 'Không xác định';
    }

    public function getStatusLeadLabelAttribute(): string
    {
        return self::getOptions('status_lead')[$this->status_lead] ?? 'Không xác định';
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            $str = $student->name;

            $str = Normalizer::normalize($str, Normalizer::FORM_D);
            $str = preg_replace('/\pM/u', '', $str);
            $str = mb_strtolower(preg_replace('/[^a-zA-Z\s]/', '', $str));

            $parts = preg_split('/\s+/', trim($str));

            $initials = '';
            $initials = implode('', array_map(fn($part) => mb_substr($part, 0, 1), array_slice($parts, 0, -1)));
            $lastName = end($parts);
            $studentCode = $lastName . $initials;
            $student->student_code = $studentCode;
        });
    }

    public function scopeWithVehicleType($query, $vehicleType)
    {
        return $query->where(function ($q) use ($vehicleType) {
            $q->whereHas('ranking', function ($q1) use ($vehicleType) {
                $q1->where('vehicle_type', $vehicleType);
            })->orWhereHas('courses.ranking', function ($q2) use ($vehicleType) {
                $q2->where('vehicle_type', $vehicleType);
            });
        });
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function saleSupport()
    {
        return $this->belongsTo(User::class, 'sale_support');
    }

    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class, 'lead_source');
    }

    public function convertedBy()
    {
        return $this->belongsTo(User::class, 'converted_by');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_students', 'student_id', 'course_id')
                    ->withPivot([
                        'id', 'contract_date', 'contract_image', 'graduation_date', 'teacher_id', 'stadium_id', 'note', 'health_check_date',
                        'sale_id', 'hours', 'km', 'status', 'tuition_fee', 
                        'start_date', 'end_date', 'cabin_learning_date', 'exam_field_id', 'gifted_chip_hours', 'reserved_chip_hours'
                    ])
                    ->withTimestamps();
    }

    public function fees()
    {
        return $this->hasMany(Fee::class, 'student_id');
    }

    public function courseFees()
    {
        return $this->hasManyThrough(Fee::class, CourseStudent::class, 'student_id', 'course_student_id', 'id', 'id');
    }

    public function courseStudents()
    {
        return $this->hasMany(CourseStudent::class);
    }

    public function assignments()
    {
        return $this->belongsToMany(Assignment::class, 'student_assignments')
                    ->withPivot('status', 'score', 'note')
                    ->withTimestamps();
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_student')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function studentExamFields()
    {
        return $this->hasMany(StudentExamField::class);
    }

    public function studentStatuses()
    {
        return $this->hasMany(StudentStatus::class);
    }

    public function ranking()
    {
        return $this->belongsTo(Ranking::class);
    }
}
