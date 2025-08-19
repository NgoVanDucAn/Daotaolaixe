<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $tip_type
 * @property int|null $quiz_set_id
 * @property int|null $page_id
 * @property string $content
 * @property array $question
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $label_type
 * @property-read \App\Models\Page|null $page
 * @property-read \App\Models\QuizSet|null $quizSet
 * @method static \Illuminate\Database\Eloquent\Builder|Tip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tip query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereQuizSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereTipType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tip extends Model
{
    protected $fillable = ['tip_type', 'quiz_set_id', 'page_id', 'question', 'content'];

    protected $casts = [
        'question' => 'array',
    ];

    protected $appends = ['label_type'];

    public function quizSet()
    {
        return $this->belongsTo(QuizSet::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function getLabelTypeAttribute()
    {
        return [1 => 'Ô tô', 2 => 'Xe máy', 3 => 'Mô phỏng'][$this->tip_type] ?? 'Không xác định';
    }
}