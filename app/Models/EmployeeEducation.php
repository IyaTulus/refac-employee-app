<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeEducation extends Model
{
    use HasUuids;

    protected $table = 'employee_educations';

    protected $fillable = [
        'employee_id',
        'level',
        'institution',
        'major',
        'graduation_year',
    ];

    protected $casts = [
        'graduation_year' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
