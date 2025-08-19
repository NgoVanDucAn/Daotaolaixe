<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivationCode extends Model
{
    protected $table = 'activation_codes';

    protected $fillable = [
        'device_mobile_id',
        'device_web_id',
        'activation_code',
        'buyer_name',
        'pakage_time',
        'activated_at',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            0 => 'Chưa kích hoạt',
            1 => 'Đã kích hoạt',
            default => 'Không xác định',
        };
    }

    public function calculateExpiresAt(): ?\DateTime
    {
        if (!$this->activated_at || $this->pakage_time == 1) {
            return null;
        }

        return $this->activated_at->copy()->addDays($this->pakage_time);
    }

    public function isExpired(): bool
    {
        if ($this->pakage_time == 1) {
            return false;
        }

        return now()->greaterThan($this->expires_at);
    }
}
