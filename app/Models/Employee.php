<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
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

    public static function rules(?self $employee = null): array
    {
        $rules = [
            'upload_photo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'employee_code' => ['required', 'string', 'regex:/^EMP-\d{3}$/'],
            'full_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\'\s]+$/'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^0\d{9,13}$/'],
            'birth_place' => ['required', 'string', 'max:100'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female'],
            'marital_status' => ['required', 'in:kawin,tidak kawin'],
            'children_count' => ['required', 'integer', 'min:0', 'max:99'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'kabupaten' => ['required', 'string', 'max:100'],
            'provinsi' => ['required', 'string', 'max:100'],
            'distance_km' => ['required', 'numeric', 'min:0'],
            'address' => ['required', 'string'],
            'position' => ['required', 'in:manager,staf,magang'],
            'employment_status' => ['required', 'in:contract,permanent,intern'],
            'department' => ['required', 'in:marketing,hrd,production,executive,commissioner'],
            'join_date' => ['required', 'date'],
            'resign_date' => ['nullable', 'date', 'after_or_equal:join_date'],
            'is_active' => ['sometimes', 'boolean'],
            'educations' => ['nullable', 'array'],
            'educations.*.level' => ['required', 'string'],
            'educations.*.institution' => ['required', 'string'],
            'educations.*.major' => ['nullable', 'string'],
            'educations.*.graduation_year' => ['required', 'integer', 'min:1950', 'max:' . (date('Y') + 5)],
        ];

        if ($employee?->exists) {
            $rules['employee_code'] = ['required', 'string', 'regex:/^EMP-\d{3}$/', Rule::unique('employees', 'employee_code')->ignore($employee->id)];
            $rules['email'] = ['required', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($employee->id)];
            $rules['phone'] = ['required', 'string', 'max:20', Rule::unique('employees', 'phone')->ignore($employee->id), 'regex:/^0\d{9,13}$/'];
        } else {
            $rules['employee_code'][] = 'unique:employees,employee_code';
            $rules['email'][] = 'unique:employees,email';
            $rules['phone'][] = 'unique:employees,phone';
        }

        return $rules;
    }

    public static function messages(): array
    {
        return [
            'upload_photo.image' => 'Foto harus berupa gambar.',
            'upload_photo.mimes' => 'Format foto harus PNG, JPG, atau JPEG.',
            'upload_photo.max' => 'Ukuran foto maksimal 2MB.',
            'employee_code.regex' => 'Format NIP harus EMP-XXX (contoh: EMP-001).',
            'phone.regex' => 'Format nomor HP harus dimulai dengan 0 (contoh: 081234567890).',
            'full_name.regex' => 'Nama hanya boleh berisi huruf, angka, tanda petik satu (\'), dan spasi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'marital_status.required' => 'Status perkawinan wajib dipilih.',
            'distance_km.required' => 'Jarak rumah ke kantor wajib diisi.',
            'distance_km.numeric' => 'Jarak harus berupa angka.',
            'distance_km.min' => 'Jarak tidak boleh kurang dari 0 km.',
            'position.required' => 'Jabatan wajib dipilih.',
            'employment_status.required' => 'Status kepegawaian wajib dipilih.',
            'department.required' => 'Departemen wajib dipilih.',
        ];
    }

    public static function bulkActionRules(): array
    {
        return [
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'string', 'exists:employees,id'],
            'action' => ['required', 'in:active,inactive,delete'],
        ];
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
