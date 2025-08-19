<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 
 *
 * @property int $id
 * @property string $vehicle_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LawViolation> $violations
 * @property-read int|null $violations_count
 * @method static \Illuminate\Database\Eloquent\Builder|LawVehicleType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawVehicleType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawVehicleType query()
 * @method static \Illuminate\Database\Eloquent\Builder|LawVehicleType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawVehicleType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawVehicleType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawVehicleType whereVehicleName($value)
 * @mixin \Eloquent
 */
class LawVehicleType extends Model
{
    use HasFactory;

    protected $table = 'law_vehicle_types';
    protected $fillable = ['vehicle_name'];

    public static function truncateTable()
    {
        // Tạm tắt kiểm tra khóa ngoại để tránh lỗi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('law_vehicle_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function violations()
    {
        return $this->hasMany(LawViolation::class, 'type_id');
    }
}
