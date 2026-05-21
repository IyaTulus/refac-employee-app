<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransportSetting extends Model
{
    use HasUuids;

    protected $table = 'transport_settings';

    protected $fillable = [
        'base_fare',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'base_fare' => 'decimal:2',
    ];

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
