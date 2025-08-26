<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $quiz_set_id id xác định việc add bài tập vào bộ câu hỏi nào
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\QuizSet $quizSet
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereQuizSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'quiz_set_id',
    ];

    public function quizSet()
    {
        return $this->belongsTo(QuizSet::class, 'quiz_set_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_assignments')
                    ->withPivot('status', 'score', 'note')
                    ->withTimestamps();
    }
}
