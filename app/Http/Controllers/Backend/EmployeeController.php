<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeEducation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use jeemce\controllers\AuthTrait;
use jeemce\controllers\CrudTrait;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeController extends Controller
{
    use CrudTrait;
    use AuthTrait;

    public function __construct()
    {
        $this->middlewareResourceAccess('employees.%');
    }

    public function index(Request $request)
    {
        $query = Employee::query()->with('educations');

        Employee::querySearch($query, [
            'search' => $request->get('search'),
            'sorter' => $this->employeeSorter($request),
        ]);

        $this->applyEmployeeFilters($query, $request);

        $employees = $query->paginate(10)->withQueryString();

        return view('backend.pages.employees.index', compact('employees'));
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $employees = $this->buildFilteredQuery($request)->get();
        $filename = 'employees_' . now()->format('Ymd_His') . '.xls';

        $response = new StreamedResponse(function () use ($employees) {
            echo '<table border="1">';
            echo '<thead><tr>';
            echo '<th>NIP</th><th>Nama</th><th>Jabatan</th><th>Departemen</th><th>Tanggal Masuk</th><th>Masa Kerja (Tahun)</th><th>Email</th><th>Telepon</th>';
            echo '</tr></thead><tbody>';

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

            echo '</tbody></table>';
        });

        $response->headers->set('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }

    public function exportPdf(Request $request)
    {
        $employees = $this->buildFilteredQuery($request)->get();

        return view('backend.pages.employees.pdf_export', compact('employees'));
    }

    public function bulkAction(Request $request)
    {
        $data = $request->validate(Employee::bulkActionRules());

        $employees = Employee::whereIn('id', $data['ids'])->get();

        if (in_array($data['action'], ['active', 'inactive'], true)) {
            Employee::whereIn('id', $data['ids'])->update(['is_active' => $data['action'] === 'active']);

            return redirect()->back()->with('success', 'Status pegawai berhasil diperbarui.');
        }

        foreach ($employees as $emp) {
            $this->validateAccess('delete', $emp);
            $this->deleteEmployeeAssets($emp);
            $emp->delete();
        }

        return redirect()->back()->with('success', 'Pegawai terpilih berhasil dihapus.');
    }

    public function form(Request $request, ?string $id = null)
    {
        $employee = $id ? $this->findModel(['id' => $id]) : new Employee();

        if (! $request->isMethod('get')) {
            if ($id) {
                $this->validateAccess('update', $employee);
            } else {
                $this->validateAccess('create', $employee);
            }

            $validated = $request->validate(Employee::rules($employee), Employee::messages());

            if (! $id) {
                $employee->id = (string) Str::uuid();
            }

            $this->fillEmployee($employee, $validated, $request, (bool) $id);
            $employee->save();

            $employee->educations()->delete();

            if (! empty($validated['educations']) && is_array($validated['educations'])) {
                foreach ($validated['educations'] as $edu) {
                    $edu['employee_id'] = $employee->id;
                    EmployeeEducation::create($edu);
                }
            }

            return redirect()->route('employees.index')->with('success', $id ? 'Pegawai berhasil diperbarui.' : 'Pegawai berhasil ditambahkan.');
        }

        if ($id) {
            $this->validateAccess('update', $employee);
        } else {
            $this->validateAccess('create', $employee);
        }

        return view($id ? 'backend.pages.employees.edit' : 'backend.pages.employees.create', compact('employee'));
    }

    public function view(string $id)
    {
        $employee = $this->findModel(['id' => $id]);

        return view('backend.pages.employees.show', compact('employee'));
    }

    public function findModel(array $where)
    {
        return Employee::query()->with('educations')->where($where)->firstOrFail();
    }

    public function delete($id, Request $request)
    {
        $employee = $this->findModel(['id' => $id]);
        $this->validateAccess('delete', $employee);

        $this->deleteEmployeeAssets($employee);
        $employee->deleteOrFail();

        if ($request->ajax()) {
            return null;
        }

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil dihapus.');
    }

    public function downloadPdf(string $id)
    {
        $employee = Employee::query()->with('educations')->findOrFail($id);

        return view('backend.pages.employees.show_pdf', compact('employee'));
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = Employee::query();

        Employee::querySearch($query, [
            'search' => $request->get('search'),
            'sorter' => $this->employeeSorter($request),
        ]);

        $this->applyEmployeeFilters($query, $request);

        return $query;
    }

    private function applyEmployeeFilters(Builder $query, Request $request): void
    {
        if ($request->filled('positions')) {
            $positions = array_filter((array) $request->input('positions', []));
            if (! empty($positions)) {
                $query->whereIn('position', $positions);
            }
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
                $query->whereYear('join_date', Carbon::now()->year - $val);
            }
        }
    }

    private function employeeSorter(Request $request): array
    {
        $sort = (string) $request->input('sort', 'created_at');
        $order = strtolower((string) $request->input('order', 'desc')) === 'asc' ? 'asc' : 'desc';

        if ($sort === 'tenure') {
            return ['join_date' => $order === 'asc' ? 'desc' : 'asc'];
        }

        $allowed = ['employee_code', 'full_name', 'position', 'join_date', 'created_at'];

        if (! in_array($sort, $allowed, true)) {
            $sort = 'created_at';
        }

        return [$sort => $order];
    }

    private function fillEmployee(Employee $employee, array $validated, Request $request, bool $isUpdate): void
    {
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
        $employee->is_active = $request->boolean('is_active', $isUpdate ? $employee->is_active : true);
    }

    private function deleteEmployeeAssets(Employee $employee): void
    {
        $employee->educations()->delete();

        $file = $employee->file('photo');
        if (! empty($file->id)) {
            if ($file->hasFile()) {
                FileFacade::delete($file->path());
            }
            $file->delete();
        }

        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }
    }
}
