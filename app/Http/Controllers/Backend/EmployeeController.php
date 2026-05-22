<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use jeemce\controllers\AuthTrait;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;
use App\Models\EmployeeEducation;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File as FileFacade;

class EmployeeController extends Controller
{

    use AuthTrait;

    public function __construct()
    {
        $this->middlewareResourceAccess('employees.%');
    }

    public function index(Request $request)
    {
        $query = Employee::query();

        // Search
        if ($request->filled('search')) {
            $s = trim($request->string('search'));
            $query->where(function ($q) use ($s) {
                $q->where('employee_code', 'like', "%{$s}%")
                    ->orWhere('full_name', 'like', "%{$s}%")
                    ->orWhere('position', 'like', "%{$s}%");
            });
        }

        // Positions (multi)
        if ($request->filled('positions')) {
            $positions = (array) $request->input('positions', []);
            $positions = array_filter($positions);
            if (!empty($positions)) $query->whereIn('position', $positions);
        }

        // Tenure filter (compare join_date against now()->subYears)
        if ($request->filled('tenure_value')) {
            $val = (int) $request->input('tenure_value');
            $op = $request->input('tenure_operator', '>');
            $ref = Carbon::now();
            $sub = (clone $ref)->subYears($val);
            if ($op === '>') {
                $query->whereDate('join_date', '<=', $sub);
            } elseif ($op === '<') {
                $query->whereDate('join_date', '>', $sub);
            } else {
                // equality: match year difference
                $year = Carbon::now()->year - $val;
                $query->whereYear('join_date', $year);
            }
        }

        // Sorting
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        $allowed = ['employee_code', 'full_name', 'position', 'join_date', 'tenure', 'created_at'];
        if (!in_array($sort, $allowed)) $sort = 'created_at';
        if ($sort === 'tenure') {
            // tenure sorting is inverse of join_date
            $query->orderBy('join_date', $order === 'asc' ? 'desc' : 'asc');
        } else {
            $query->orderBy($sort, $order);
        }

        $employees = $query->with('educations')->paginate(10)->withQueryString();

        return view('backend.pages.employees.index', compact('employees'));
    }

    /**
     * Export current filtered employees as CSV
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $query = $this->buildFilteredQuery($request);
        $employees = $query->get();

        $filename = 'employees_' . now()->format('Ymd_His') . '.xls';

        $response = new StreamedResponse(function () use ($employees) {
            echo '<table border="1">';
            echo '<thead><tr>';
            echo '<th>NIP</th>';
            echo '<th>Nama</th>';
            echo '<th>Jabatan</th>';
            echo '<th>Departemen</th>';
            echo '<th>Tanggal Masuk</th>';
            echo '<th>Masa Kerja (Tahun)</th>';
            echo '<th>Email</th>';
            echo '<th>Telepon</th>';
            echo '</tr></thead>';
            echo '<tbody>';

            foreach ($employees as $emp) {
                echo '<tr>';
                echo '<td>' . e($emp->employee_code) . '</td>';
                echo '<td>' . e($emp->full_name) . '</td>';
                echo '<td>' . e($emp->position) . '</td>';
                echo '<td>' . e($emp->department) . '</td>';
                echo '<td>' . e($emp->join_date?->format('Y-m-d')) . '</td>';
                echo '<td>' . e($emp->tenure) . '</td>';
                echo '<td>' . e($emp->email) . '</td>';
                echo '<td>' . e($emp->phone) . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        });

        $response->headers->set('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }

    public function exportPdf(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $employees = $query->get();

        return view('backend.pages.employees.pdf_export', compact('employees'));
    }

    public function bulkAction(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'string', 'exists:employees,id'],
            'action' => ['required', 'in:active,inactive,delete'],
        ]);

        $ids = $data['ids'];
        $action = $data['action'];

        $employees = Employee::whereIn('id', $ids)->get();

        if ($action === 'active' || $action === 'inactive') {
            $val = $action === 'active';
            Employee::whereIn('id', $ids)->update(['is_active' => $val]);
            return redirect()->back()->with('success', 'Status pegawai berhasil diperbarui.');
        }

        // delete
        foreach ($employees as $emp) {
            $this->validateAccess('delete', $emp);
            $emp->educations()->delete();
            $file = $emp->file('photo');
            if (!empty($file->id)) {
                if ($file->hasFile()) {
                    FileFacade::delete($file->path());
                }
                $file->delete();
            }
            if ($emp->photo) Storage::disk('public')->delete($emp->photo);
            $emp->delete();
        }

        return redirect()->back()->with('success', 'Pegawai terpilih berhasil dihapus.');
    }

    /**
     * Build query with same filters as index (used by exports)
     */
    private function buildFilteredQuery(Request $request)
    {
        $query = Employee::query();

        if ($request->filled('search')) {
            $s = trim($request->string('search'));
            $query->where(function ($q) use ($s) {
                $q->where('employee_code', 'like', "%{$s}%")
                    ->orWhere('full_name', 'like', "%{$s}%")
                    ->orWhere('position', 'like', "%{$s}%");
            });
        }

        if ($request->filled('positions')) {
            $positions = (array) $request->input('positions', []);
            $positions = array_filter($positions);
            if (!empty($positions)) $query->whereIn('position', $positions);
        }

        if ($request->filled('tenure_value')) {
            $val = (int) $request->input('tenure_value');
            $op = $request->input('tenure_operator', '>');
            $sub = Carbon::now()->subYears($val);
            if ($op === '>') {
                $query->whereDate('join_date', '<=', $sub);
            } elseif ($op === '<') {
                $query->whereDate('join_date', '>', $sub);
            } else {
                $year = Carbon::now()->year - $val;
                $query->whereYear('join_date', $year);
            }
        }

        return $query;
    }

    public function show(string $id)
    {
        $employee = Employee::query()->findOrFail($id);

        return view('backend.pages.employees.show', compact('employee'));
    }

    public function create()
    {
        $employee = new Employee();

        return view('backend.pages.employees.create', compact('employee'));
    }

    public function store(\App\Http\Requests\Employee\StoreEmployeeRequest $request)
    {
        $validated = $request->validated();

        $employee = new Employee();
        $employee->id = (string) Str::uuid();
        $employee->employee_code = $validated['employee_code'];
        $employee->full_name = $validated['full_name'];
        $employee->email = $validated['email'];
        $employee->phone = $validated['phone'];
        $employee->birth_place = $validated['birth_place'];
        $employee->birth_date = $validated['birth_date'];
        $employee->gender = $validated['gender'];
        $employee->marital_status = $validated['marital_status'];
        $employee->children_count = $validated['children_count'];
        $employee->kecamatan = $validated['kecamatan'];
        $employee->kabupaten = $validated['kabupaten'];
        $employee->provinsi = $validated['provinsi'];
        $employee->distance_km = $validated['distance_km'];
        $employee->address = $validated['address'];
        $employee->position = $validated['position'];
        $employee->employment_status = $validated['employment_status'];
        $employee->department = $validated['department'];
        $employee->join_date = $validated['join_date'];
        $employee->resign_date = $validated['resign_date'] ?? null;
        $employee->is_active = $request->boolean('is_active', true);
        $employee->save();

        // Educations
        if (!empty($validated['educations']) && is_array($validated['educations'])) {
            foreach ($validated['educations'] as $edu) {
                $edu['employee_id'] = $employee->id;
                EmployeeEducation::create($edu);
            }
        }

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $employee = Employee::query()->with('educations')->findOrFail($id);
        $this->validateAccess('update', $employee);

        return view('backend.pages.employees.edit', compact('employee'));
    }

    public function update(Request $request, string $id)
    {
        $employee = Employee::query()->with('educations')->findOrFail($id);
        $this->validateAccess('update', $employee);

        $rules = (new \App\Http\Requests\Employee\StoreEmployeeRequest())->rules();
        // adjust unique rules to ignore current employee
        // enforce EMP-XXX format on update as well
        $rules['employee_code'] = ['required', 'string', 'regex:/^EMP-\d{3}$/', Rule::unique('employees', 'employee_code')->ignore($employee->id)];
        $rules['email'] = ['required', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($employee->id)];
        // local phone format starting with 0
        $rules['phone'] = ['required', 'string', 'max:20', Rule::unique('employees', 'phone')->ignore($employee->id), 'regex:/^0\d{9,13}$/'];

        $validated = $request->validate($rules, (new \App\Http\Requests\Employee\StoreEmployeeRequest())->messages());

        $employee->employee_code = $validated['employee_code'];
        $employee->full_name = $validated['full_name'];
        $employee->email = $validated['email'];
        $employee->phone = $validated['phone'];
        $employee->birth_place = $validated['birth_place'];
        $employee->birth_date = $validated['birth_date'];
        $employee->gender = $validated['gender'];
        $employee->marital_status = $validated['marital_status'];
        $employee->children_count = $validated['children_count'];
        $employee->kecamatan = $validated['kecamatan'];
        $employee->kabupaten = $validated['kabupaten'];
        $employee->provinsi = $validated['provinsi'];
        $employee->distance_km = $validated['distance_km'];
        $employee->address = $validated['address'];
        $employee->position = $validated['position'];
        $employee->employment_status = $validated['employment_status'];
        $employee->department = $validated['department'];
        $employee->join_date = $validated['join_date'];
        $employee->resign_date = $validated['resign_date'] ?? null;
        $employee->is_active = $request->boolean('is_active', $employee->is_active);
        $employee->save();

        // sync educations: remove existing and recreate for simplicity
        $employee->educations()->delete();
        if (!empty($validated['educations']) && is_array($validated['educations'])) {
            foreach ($validated['educations'] as $edu) {
                $edu['employee_id'] = $employee->id;
                EmployeeEducation::create($edu);
            }
        }

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $employee = Employee::query()->findOrFail($id);
        $this->validateAccess('delete', $employee);

        // Remove related educations and photo
        $employee->educations()->delete();
        $file = $employee->file('photo');
        if (!empty($file->id)) {
            if ($file->hasFile()) {
                FileFacade::delete($file->path());
            }
            $file->delete();
        }
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil dihapus.');
    }

    public function downloadPdf(string $id)
    {
        $employee = Employee::query()->with('educations')->findOrFail($id);

        // If PDF generator is not available, return HTML view (print-to-PDF from browser)
        return view('backend.pages.employees.show_pdf', compact('employee'));
    }
}
