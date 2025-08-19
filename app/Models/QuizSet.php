<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $lesson_id id cho biết thuộc về bài nào
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \App\Models\Lesson $lesson
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quiz> $quizzes
 * @property-read int|null $quizzes_count
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'lesson_id',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
