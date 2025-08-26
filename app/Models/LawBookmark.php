<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 
 *
 * @property int $id
 * @property string $bookmark_code
 * @property int $bookmark_type_id
 * @property string $bookmark_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LawBookmarkType $bookmarkType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LawViolation> $violations
 * @property-read int|null $violations_count
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark query()
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark whereBookmarkCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark whereBookmarkDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark whereBookmarkTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LawBookmark extends Model
{
    use HasFactory;

    protected $table = 'law_bookmarks';
    protected $fillable = ['bookmark_code', 'bookmark_type_id', 'bookmark_description'];

    public static function truncateTable()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('law_violation_bookmarks')->truncate();
        DB::table('law_bookmarks')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function violations()
    {
        return $this->belongsToMany(LawViolation::class, 'law_violation_bookmarks', 'bookmark_id', 'violation_id')
                    ->withTimestamps();
    }

    public function bookmarkType()
    {
        return $this->belongsTo(LawBookmarkType::class, 'bookmark_type_id');
    }
}
