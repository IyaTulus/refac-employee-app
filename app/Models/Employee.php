<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use jeemce\models\Fileable;

class Employee extends Model
{
    use HasUuids, HasFactory, Fileable;

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
        'distance_km' => 'decimal:2',
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

    public function getHasPhotoAttribute(): bool
    {
        $vendorPhoto = $this->file('photo');
        if (!empty($vendorPhoto->id) && $vendorPhoto->exist()) {
            return true;
        }

        if (empty($this->photo)) {
            return false;
        }

        $photoPath = ltrim((string) $this->photo, '/');

        if (Storage::disk('public')->exists($photoPath)) {
            return true;
        }

        if (str_starts_with($photoPath, 'storage/')) {
            $normalizedPath = substr($photoPath, strlen('storage/'));

            return $normalizedPath !== '' && Storage::disk('public')->exists($normalizedPath);
        }

        return false;
    }

    public function getPhotoUrlAttribute(): string
    {
        $vendorPhoto = $this->file('photo');
        if (!empty($vendorPhoto->id) && $vendorPhoto->exist()) {
            return $vendorPhoto->url();
        }

        if (empty($this->photo)) {
            return 'https://via.placeholder.com/150x150?text=No+Photo';
        }

        $photoPath = ltrim((string) $this->photo, '/');

        if (Storage::disk('public')->exists($photoPath)) {
            return asset('storage/' . $photoPath);
        }

        if (str_starts_with($photoPath, 'storage/')) {
            $normalizedPath = substr($photoPath, strlen('storage/'));

            if ($normalizedPath !== '' && Storage::disk('public')->exists($normalizedPath)) {
                return asset('storage/' . $normalizedPath);
            }
        }

        return 'https://via.placeholder.com/150x150?text=No+Photo';
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
