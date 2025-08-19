<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name tên lĩnh vực học
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField query()
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LearningField extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'teaching_mode',
        'is_practical',
        'description',
        'applies_to_all_rankings'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'teaching_mode' => 'integer',
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

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_learning_field')->withPivot('hours', 'km')->withTimestamps();
    }

    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }
}
