<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name tên hạng bằng
 * @property string|null $description mô tả hạng bằng
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamSet> $examSets
 * @property-read int|null $exam_sets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lesson> $lessons
 * @property-read int|null $lessons_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vehicle> $vehicles
 * @property-read int|null $vehicles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ranking extends Model
{
    use HasFactory;

    const VEHICLE_TYPES = [
        0 => 'Xe máy',
        1 => 'Ô tô',
    ];

    protected $fillable = ['name', 'fee_ranking', 'vehicle_type', 'description'];

    protected $casts = [
        'fee' => 'decimal:2',
    ];

    public function getVehicleTypeTextAttribute()
    {
        return self::VEHICLE_TYPES[$this->vehicle_type] ?? 'Không xác định';
    }
    
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_ranking');
    }

    public function examSets()
    {
        return $this->hasMany(ExamSet::class, 'license_level');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'ranking_user')->withTimestamps();
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
