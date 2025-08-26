<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $type
 * @property string $code
 * @property string $name
 * @property string|null $image
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $type_label
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrafficSign extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'code', 'name', 'image', 'description'];

    protected $appends = ['type_label'];

    public static function getTypeOptions(): array
    {
        return [
            1 => 'Biển báo cấm',
            2 => 'Biển báo nguy hiểm',
            3 => 'Biển báo hiệu lệnh',
            4 => 'Biển báo chỉ dẫn',
            5 => 'Biển báo phụ',
            6 => 'Vạch kẻ đường',
        ];
    }

    public function getTypeLabelAttribute(): ?string
    {
        return self::getTypeOptions()[$this->type] ?? 'Không xác định';
    }
}
