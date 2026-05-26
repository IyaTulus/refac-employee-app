<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransportAllowance extends Model
{
    use HasUuids;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'base_fare',
        'distance_km',
        'work_days',
        'total_amount',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'base_fare' => 'decimal:2',
        'distance_km' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'month' => 'integer',
        'year' => 'integer',
        'work_days' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer'],
            'work_days' => ['required', 'integer', 'min:0', 'max:31'],
        ];
    }
}
