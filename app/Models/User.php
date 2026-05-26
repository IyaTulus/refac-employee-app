<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'employee_id',
        'name',
        'email',
        'phone',
        'password',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function hasField(string $field): bool
    {
        return array_key_exists($field, $this->getAttributes());
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function roles(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public static function rules(?self $user = null): array
    {
        $userId = $user?->id;

        return [
            'employee_id' => ['required', 'exists:employees,id', Rule::unique('users', 'employee_id')->ignore($userId)],
            'role_id' => ['required', 'exists:roles,id'],
            'username' => ['required', 'string', 'min:6', 'regex:/^[a-z0-9]+$/', Rule::unique('users', 'username')->ignore($userId)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['required', 'string', Rule::unique('users', 'phone')->ignore($userId)],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', 'min:6'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public static function messages(): array
    {
        return [];
    }

    public static function passwordRules(): array
    {
        return [
            'password' => ['required', 'confirmed', 'min:6'],
        ];
    }
}
