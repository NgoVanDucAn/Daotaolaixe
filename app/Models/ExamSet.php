<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name tên bộ đề
 * @property int|null $license_level
 * @property string $type kiểu bộ đề: đề thi thử, đề ôn tập, câu hỏi ôn tập,etc...
 * @property string|null $description mô tả về bộ đề
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $lesson_id
 * @property-read \App\Models\Lesson|null $lesson
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quiz> $quizzes
 * @property-read int|null $quizzes_count
 * @property-read \App\Models\Ranking|null $ranking
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereLicenseLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExamSet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'license_level', 'type', 'description', 'lesson_id',];

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'exam_set_question');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function ranking()
    {
        return $this->belongsTo(Ranking::class, 'license_level');
    }
}
