<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use jeemce\helpers\QuerySearch;

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

    public static function querySearch(Builder $query, array $options = []): QuerySearch
    {
        return new QuerySearch($query, array_merge([
            'searchFields' => ['base_fare'],
            'sorterFields' => ['base_fare', 'created_at', 'updated_at'],
            'sorterDefaultFields' => ['created_at' => 'desc'],
        ], $options));
    }

    public static function rules(): array
    {
        return [
            'base_fare' => ['required', 'numeric', 'min:0'],
        ];
    }
}
