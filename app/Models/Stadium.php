<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $location
 * @property string|null $google_maps_url
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium query()
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium whereGoogleMapsUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Stadium extends Model
{
    use HasFactory;

    protected $table = 'stadiums';

    protected $fillable = [
        'location',
        'google_maps_url',
        'note',
    ];
}
