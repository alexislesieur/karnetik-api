<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id', 'intervention_id', 'type', 'label',
        'file_path', 'file_name', 'mime_type', 'file_size',
        'document_date', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'document_date' => 'date',
            'expires_at' => 'date',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<=', now()->addDays($days))
            ->where('expires_at', '>=', now());
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}