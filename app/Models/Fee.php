<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $payment_date Ngày thanh toán
 * @property int $amount Số tiền đã đóng
 * @property int $student_id Tài khoản học viên
 * @property int $is_received Trạng thái thanh toán
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $course_id Khóa học người dùng tham gia
 * @property-read \App\Models\Course|null $course
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|Fee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereIsReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_date',
        'amount',
        'student_id',
        'fee_type',
        'collector_id',
        'is_received',
        'note',
        'course_student_id',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    protected $appends = ['fee_type_vi'];

    public static function getTypeOptions(): array
    {
        return [
            1 => 'Học phí',
            2 => 'Lệ phí đăng ký xe chip',
            3 => 'Lệ phí cọc chip',
            4 => 'Lệ phí đưa đón',
            5 => 'Hết môn lý thuyết',
            6 => 'Hết môn thực hành',
            7 => 'Thi tốt nghiệp',
            8 => 'Khác',
        ];
    }

    public function getFeeTypeLabelAttribute(): string
    {
        return self::getTypeOptions()[$this->fee_type] ?? 'Không xác định';
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function courseStudent()
    {
        return $this->belongsTo(CourseStudent::class, 'course_student_id');
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }
    public function getFeeTypeViAttribute(): string
    {
        return $this->fee_type_label;
    }

}

