<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id', 'type', 'label', 'due_date', 'due_mileage',
        'is_recurring', 'recurrence_months', 'recurrence_km',
        'is_completed', 'notified_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'due_mileage' => 'integer',
            'is_recurring' => 'boolean',
            'is_completed' => 'boolean',
            'notified_at' => 'datetime',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeUpcoming($query, int $days = 30)
    {
        return $query->pending()
            ->whereNotNull('due_date')
            ->where('due_date', '<=', now()->addDays($days));
    }

    public function isOverdue(): bool
    {
        if ($this->due_date && $this->due_date->isPast()) {
            return true;
        }
        if ($this->due_mileage && $this->vehicle) {
            return $this->vehicle->mileage >= $this->due_mileage;
        }
        return false;
    }
}