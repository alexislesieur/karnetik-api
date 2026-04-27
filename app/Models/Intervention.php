<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intervention extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_id', 'type', 'label', 'notes', 'performed_at', 'mileage_at',
        'garage', 'garage_city', 'cost', 'cost_parts', 'cost_labor',
        'reference', 'is_professional',
    ];

    protected function casts(): array
    {
        return [
            'performed_at' => 'date',
            'mileage_at' => 'integer',
            'cost' => 'decimal:2',
            'cost_parts' => 'decimal:2',
            'cost_labor' => 'decimal:2',
            'is_professional' => 'boolean',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, int $months = 12)
    {
        return $query->where('performed_at', '>=', now()->subMonths($months));
    }
}