<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $quiz_set_id Bộ câu hỏi
 * @property string $question Câu hỏi
 * @property string|null $name Tên câu hỏi
 * @property string|null $image Ảnh của câu hỏi
 * @property string|null $explanation Nội dung giải thích về câu hỏi đó
 * @property int $mandatory quy định câu hỏi là câu bắt buộc đúng hay không
 * @property int $wrong câu hỏi hay bị sai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $tip cách làm nhanh, nhận diện đáp án cho câu hỏi đó
 * @property string|null $tip_image hình ảnh mô tả việc nhận diện đáp án đúng cho câu hỏi đó
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuizOption> $quizOptions
 * @property-read int|null $quiz_options_count
 * @property-read \App\Models\QuizSet $quizSet
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz query()
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereExplanation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereMandatory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereQuizSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereTip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereTipImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereWrong($value)
 * @mixin \Eloquent
 */
class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_set_id',
        'question',
        'name',
        'image',
        'explanation',
        'mandatory',
        'wrong',
        'tip',
        'tip_image',
    ];

    public function quizSet()
    {
        return $this->belongsTo(QuizSet::class);
    }

    public function quizOptions()
    {
        return $this->hasMany(QuizOption::class);
    }
}
