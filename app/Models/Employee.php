<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'photo',
        'employee_code',
        'full_name',
        'email',
        'phone',
        'birth_place',
        'birth_date',
        'gender',
        'marital_status',
        'children_count',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'address',
        'distance_km',
        'position',
        'employment_status',
        'department',
        'join_date',
        'resign_date',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
        'resign_date' => 'date',
        'is_active' => 'boolean',
        'children_count' => 'integer',
    ];

    /**
     * Get tenure in years.
     */
    public function getTenureAttribute(): int
    {
        return (int) $this->join_date->diffInYears(now());
    }

    /**
     * Get age in years.
     */
    public function getAgeAttribute(): int
    {
        return (int) $this->birth_date->diffInYears(now());
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'employee_id');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(EmployeeEducation::class, 'employee_id');
    }

    // public function parentData(): HasOne
    // {
    //     return $this->hasOne(ParentData::class, 'employee_id');
    // }
    // public function transportAllowances(): HasMany
    // {
    //     return $this->hasMany(TransportAllowance::class, 'employee_id');
    // }
}
