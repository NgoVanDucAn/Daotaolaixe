<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 
 *
 * @property int $id
 * @property string $description
 * @property string $entities
 * @property string $fines
 * @property string|null $additional_penalties
 * @property string|null $remedial
 * @property string|null $other_penalties
 * @property int $type_id
 * @property int $topic_id
 * @property string|null $image
 * @property string|null $keyword
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $violation_no
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LawBookmark> $bookmarks
 * @property-read int|null $bookmarks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, LawViolation> $relatedViolations
 * @property-read int|null $related_violations_count
 * @property-read \App\Models\LawTopic $topic
 * @property-read \App\Models\LawVehicleType $vehicleType
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation query()
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereAdditionalPenalties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereEntities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereFines($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereOtherPenalties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereRemedial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereViolationNo($value)
 * @mixin \Eloquent
 */
class LawViolation extends Model
{
    use HasFactory;

    protected $table = 'law_violations';
    protected $fillable = [
        'violation_no', 'description', 'entities', 'fines', 'additional_penalties',
        'remedial', 'other_penalties', 'type_id', 'topic_id', 'image', 'keyword'
    ];

    public static function truncateTable()
    {
        // Tạm tắt kiểm tra khóa ngoại để tránh lỗi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('law_violations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function bookmarks()
    {
        return $this->belongsToMany(LawBookmark::class, 'law_violation_bookmarks', 'violation_id', 'bookmark_id')
                    ->withTimestamps();
    }

    public function vehicleType()
    {
        return $this->belongsTo(LawVehicleType::class, 'type_id');
    }

    public function topic()
    {
        return $this->belongsTo(LawTopic::class, 'topic_id');
    }

    public function relatedViolations()
    {
        return $this->belongsToMany(LawViolation::class, 'law_violation_relations', 'violation_id', 'related_violation_id')->withTimestamps();
    }
}
