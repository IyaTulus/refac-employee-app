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
            'employee_code' => ['required', 'string', 'regex:/^[0-9]{8,}$/', 'unique:employees,employee_code'],
            'full_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\'\s]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:employees,phone', 'regex:/^\+?[1-9]\d{1,14}$/'],
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
            'employee_code.regex' => 'NIP minimal 8 karakter dan hanya angka.',
            'phone.regex' => 'Format nomor HP internasional tidak valid (contoh: +62xxx).',
            'full_name.regex' => 'Nama hanya boleh berisi huruf, angka, tanda petik satu (\'), dan spasi.',
        ];
    }
}
