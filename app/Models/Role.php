<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

class Role extends Model
{
    protected $fillable = [
        'name',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public static function rules(?self $role = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:128',
                $role?->exists
                    ? Rule::unique('roles', 'name')->ignore($role->id)
                    : 'unique:roles,name',
            ],
            'accesses' => ['nullable', 'array'],
            'accesses.*.read' => ['nullable', 'in:all,none,only'],
            'accesses.*.view' => ['nullable', 'in:all,none,only'],
            'accesses.*.create' => ['nullable', 'in:all,none,only'],
            'accesses.*.update' => ['nullable', 'in:all,none,only'],
            'accesses.*.delete' => ['nullable', 'in:all,none,only'],
            'accesses.*.publish' => ['nullable', 'in:all,none,only'],
        ];
    }
}
