<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $title Tiêu đề tài liệu
 * @property string|null $type Loại tài liệu
 * @property string|null $file_path Đường dẫn tới tài liệu
 * @property string|null $url Link tới tài liệu nếu là tài liệu trực tuyến
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $chapter_id
 * @property string|null $total_time
 * @property string|null $start_time
 * @property string|null $end_time
 * @property-read \App\Models\Chapter $chapter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Simulation> $simulations
 * @property-read int|null $simulations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Material newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Material newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Material query()
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereChapterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereTotalTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereUrl($value)
 * @mixin \Eloquent
 */
class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'type',
        'file_path',
        'url',
        'chapter_id',
        'total_time',
        'start_time',
        'end_time',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function simulations()
    {
        return $this->belongsToMany(Simulation::class, 'simulation_material')->withTimestamps();
    }
}
