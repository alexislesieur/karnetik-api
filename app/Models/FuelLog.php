<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id', 'filled_at', 'mileage_at', 'liters', 'cost',
        'price_per_liter', 'station', 'is_full_tank', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'filled_at' => 'date',
            'mileage_at' => 'integer',
            'liters' => 'decimal:2',
            'cost' => 'decimal:2',
            'price_per_liter' => 'decimal:3',
            'is_full_tank' => 'boolean',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}