<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $vehicle_id
 * @property string $type
 * @property string $time
 * @property int|null $user_id
 * @property int $amount
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $type_vi
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\Vehicle $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense query()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereVehicleId($value)
 * @mixin \Eloquent
 */
class VehicleExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'type',
        'time',
        'user_id',
        'amount',
        'note',
    ];

    protected $appends = ['type_vi'];

    public static function getTypeOptions(): array
    {
        return [
            'simulation' => 'sa hình',
            'refuel' => 'đổ xăng',
            'maintenance' => 'bảo dưỡng',
            'inspection' => 'đăng kiểm',
            'tire_replacement' => 'thay lốp',
            'other' => 'khác',
        ];
    }

    public function getTypeViAttribute(): ?string
    {
        return self::getTypeOptions()[$this->type] ?? null;
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
