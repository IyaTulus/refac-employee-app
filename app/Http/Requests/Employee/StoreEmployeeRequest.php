<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'photo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            // format EMP-001 (prefix EMP- followed by 3 digits)
            'employee_code' => ['required', 'string', 'regex:/^EMP-\d{3}$/', 'unique:employees,employee_code'],
            'full_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\'\s]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email'],
            // accept local Indonesian style starting with 0, e.g. 081234567890
            'phone' => ['required', 'string', 'max:20', 'unique:employees,phone', 'regex:/^0\d{9,13}$/'],
            'birth_place' => ['required', 'string', 'max:100'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female'],
            'marital_status' => ['required', 'in:kawin,tidak kawin'],
            'children_count' => ['required', 'integer', 'min:0', 'max:99'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'kabupaten' => ['required', 'string', 'max:100'],
            'provinsi' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string'],
            'position' => ['required', 'in:manager,staf,magang'],
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
    }

    public function messages()
    {
        return [
            'employee_code.regex' => 'Format NIP harus EMP-XXX (contoh: EMP-001).',
            'phone.regex' => 'Format nomor HP harus dimulai dengan 0 (contoh: 081234567890).',
            'full_name.regex' => 'Nama hanya boleh berisi huruf, angka, tanda petik satu (\'), dan spasi.',
        ];
    }
}
