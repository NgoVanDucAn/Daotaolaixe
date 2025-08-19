<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * 
 *
 * @property int $id
 * @property int $curriculum_id Giáo trình id
 * @property string $title Tên bài học
 * @property string|null $description Mô tả chi tiết về bài học
 * @property int $sequence Thứ tự của bài học trong giáo trình
 * @property string $visibility
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Curriculum $curriculum
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamSet> $examSets
 * @property-read int|null $exam_sets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuizSet> $quizSets
 * @property-read int|null $quiz_sets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ranking> $rankings
 * @property-read int|null $rankings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereCurriculumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereVisibility($value)
 * @mixin \Eloquent
 */
class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'curriculum_id','title', 'description', 'sequence', 'visibility',
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function quizSets()
    {
        return $this->hasMany(QuizSet::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'lesson_student')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function examSets()
    {
        return $this->hasMany(ExamSet::class);
    }

    public function rankings()
    {
        return $this->belongsToMany(Ranking::class, 'lesson_ranking');
    }
}
