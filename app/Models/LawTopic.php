<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 
 *
 * @property int $id
 * @property string $topic_name
 * @property string|null $subtitle
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LawViolation> $violations
 * @property-read int|null $violations_count
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic query()
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic whereTopicName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LawTopic extends Model
{
    use HasFactory;

    protected $table = 'law_topics';
    protected $fillable = ['topic_name', 'subtitle'];

    public static function truncateTable()
    {
        // Tạm tắt kiểm tra khóa ngoại để tránh lỗi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('law_topics')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function violations()
    {
        return $this->hasMany(LawViolation::class, 'topic_id');
    }
}
