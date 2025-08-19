<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $quiz_id ID câu hỏi
 * @property string $option_text Các lựa chọn của quiz
 * @property int $is_correct Đáp án của quiz
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Quiz $quiz
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption whereOptionText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'option_text',
        'is_correct',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
