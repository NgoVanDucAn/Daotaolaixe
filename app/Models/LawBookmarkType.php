<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 
 *
 * @property int $id
 * @property string $bookmark_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LawBookmark> $bookmarks
 * @property-read int|null $bookmarks_count
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmarkType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmarkType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmarkType query()
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmarkType whereBookmarkName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmarkType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmarkType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmarkType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LawBookmarkType extends Model
{
    use HasFactory;

    protected $table = 'law_bookmark_types';
    protected $fillable = ['bookmark_name'];

    public static function truncateTable()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('law_bookmark_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function bookmarks()
    {
        return $this->hasMany(LawBookmark::class, 'bookmark_type_id');
    }
}
