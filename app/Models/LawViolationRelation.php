<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 
 *
 * @property int $violation_id
 * @property int $related_violation_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolationRelation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolationRelation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolationRelation query()
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolationRelation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolationRelation whereRelatedViolationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolationRelation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolationRelation whereViolationId($value)
 * @mixin \Eloquent
 */
class LawViolationRelation extends Model
{
    use HasFactory;

    protected $table = 'law_violation_relations';
    protected $fillable = ['violation_id', 'related_violation_id'];

    public static function truncateTable()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('law_violation_relations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
