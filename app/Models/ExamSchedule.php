<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $start_time
 * @property string $end_time
 * @property int $stadium_id Sân thi
 * @property string|null $description
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamField> $examFields
 * @property-read int|null $exam_fields_count
 * @property-read string|null $status_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ranking> $rankings
 * @property-read int|null $rankings_count
 * @property-read \App\Models\Stadium $stadium
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereStadiumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExamSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
        'stadium_id',
        'description',
        'status',
    ];

    protected $appends = ['status_label'];

    public static function getStatusOptions(): array
    {
        return [
            'scheduled' => 'Đã lên lịch',
            'canceled' => 'Đã hủy',
        ];
    }

    public function getStatusLabelAttribute(): ?string
    {
        return self::getStatusOptions()[$this->status] ?? 'Không xác định';
    }

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function rankings()
    {
        return $this->belongsToMany(Ranking::class, 'exam_schedule_ranking');
    }

    public function examFields()
    {
        return $this->belongsToMany(ExamField::class, 'exam_schedule_exam_field');
    }

    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }
}
