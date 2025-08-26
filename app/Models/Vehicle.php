<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $license_plate biển số
 * @property string $model
 * @property int $ranking_id
 * @property string $type loại xe: xe con, xe 7 chỗ, xe ? chỗ,...
 * @property string $color màu sắc của xe
 * @property string|null $training_license_number số giấy phép tập lái
 * @property string $manufacture_year năm sản xuất
 * @property string|null $description mô tả
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \App\Models\Ranking $ranking
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereLicensePlate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereManufactureYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereRankingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereTrainingLicenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_plate',
        'model',
        'ranking_id',
        'type',
        'color',
        'training_license_number',
        'manufacture_year',
        'description',
    ];

    protected $appends = ['practical_hours_by_field'];

    public function getPracticalHoursByFieldAttribute()
    {
        $fieldHours = [];

        foreach ($this->calendars as $calendar) {
            $field = $calendar->learningField;

            if ($calendar->level == 3 && $field && $field->is_practical) {
                $fieldName = $field->name;

                if (!isset($fieldHours[$fieldName])) {
                    $fieldHours[$fieldName] = 0;
                }

                $fieldHours[$fieldName] += $calendar->calendarStudents->sum('hours');
            }
        }

        return $fieldHours;
    }

    public function ranking()
    {
        return $this->belongsTo(Ranking::class);
    }

    public function calendars()
    {
        return $this->hasMany(Calendar::class, 'vehicle_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
