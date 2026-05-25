<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\TransportAllowance;
use App\Models\TransportSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use jeemce\controllers\AuthTrait;
use jeemce\controllers\CrudTrait;

class TransportAllowanceController extends Controller
{
    use CrudTrait;
    use AuthTrait;

    public function __construct()
    {
        $this->middlewareResourceAccess('transport-allowances.%');
    }

    public function index(Request $request)
    {
        $query = TransportAllowance::with('employee')->orderByDesc('created_at');

        if ($request->filled('month')) {
            $query->where('month', $request->integer('month'));
        }

        if ($request->filled('year')) {
            $query->where('year', $request->integer('year'));
        }

        $allowances = $query->paginate(10)->withQueryString();

        return view('backend.pages.transport-allowances.index', compact('allowances'));
    }

    public function form(?string $id = null)
    {
        $allowance = $id ? $this->findModel(['id' => $id]) : new TransportAllowance();

        if ($id) {
            $this->validateAccess('update', $allowance);
        } else {
            $this->validateAccess('create', $allowance);
        }

        $employees = Employee::query()
            ->select(['id', 'employee_code', 'full_name', 'distance_km', 'employment_status'])
            ->orderBy('full_name')
            ->get();
        $baseFare = TransportSetting::query()->latest()->value('base_fare') ?? 0;

        return view('backend.pages.transport-allowances.create', compact('allowance', 'employees', 'baseFare'));
    }

    public function save(Request $request, ?string $id = null)
    {
        $allowance = $id ? $this->findModel(['id' => $id]) : new TransportAllowance();

        if ($id) {
            $this->validateAccess('update', $allowance);
        } else {
            $this->validateAccess('create', $allowance);
        }

        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer'],
            'work_days' => ['required', 'integer', 'min:0', 'max:31'],
        ]);

        $employee = Employee::findOrFail($data['employee_id']);
        $baseFare = TransportSetting::query()->latest()->value('base_fare') ?? 0;
        $result = $this->evaluateTransportAllowance($employee, (int) $data['work_days'], (float) $baseFare);

        $payload = [
            'employee_id' => $employee->id,
            'month' => $data['month'],
            'year' => $data['year'],
            'base_fare' => $result['base_fare'],
            'distance_km' => $result['counted_distance_km'],
            'work_days' => $data['work_days'],
            'total_amount' => $result['total_amount'],
            'notes' => $result['notes'],
            'created_by' => Auth::id(),
        ];

        if ($allowance->exists) {
            $allowance->update($payload);
        } else {
            $allowance = TransportAllowance::create($payload);
        }

        return redirect()->route('transport-allowances.index')->with('success', 'Perhitungan tunjangan berhasil disimpan.');
    }

    public function view(string $id)
    {
        return redirect()->route('transport-allowances.index');
    }

    public function findModel(array $where)
    {
        return TransportAllowance::query()->with('employee')->where($where)->firstOrFail();
    }

    public function delete($id, Request $request)
    {
        $allowance = $this->findModel(['id' => $id]);
        $this->validateAccess('delete', $allowance);
        $allowance->deleteOrFail();

        if ($request->ajax()) {
            return null;
        }

        return redirect()->back()->with('success', 'Data tunjangan berhasil dihapus.');
    }

    private function evaluateTransportAllowance(Employee $employee, int $workDays, float $baseFare): array
    {
        $actualDistance = (float) ($employee->distance_km ?? 0);
        $countedDistance = min(max($actualDistance, 0), 25);
        $isPermanent = $employee->employment_status === 'permanent';
        $meetsWorkDays = $workDays >= 19;
        $meetsDistance = $countedDistance > 5;

        $notes = [];

        if (! $isPermanent) {
            $notes[] = 'Tidak layak: pegawai non-tetap.';
        }

        if (! $meetsWorkDays) {
            $notes[] = 'Tidak layak: minimal 19 hari kerja.';
        }

        if ($actualDistance <= 5) {
            $notes[] = 'Tidak layak: jarak minimal harus lebih dari 5 km.';
        }

        if ($actualDistance > 25) {
            $notes[] = 'Jarak dibatasi maksimal 25 km.';
        }

        $isEligible = $isPermanent && $meetsWorkDays && $meetsDistance;

        return [
            'base_fare' => $baseFare,
            'actual_distance_km' => $actualDistance,
            'counted_distance_km' => $countedDistance,
            'is_eligible' => $isEligible,
            'total_amount' => $isEligible ? ($baseFare * $workDays) : 0,
            'notes' => $isEligible
                ? ($actualDistance > 25 ? 'Layak: jarak dihitung maksimal 25 km.' : 'Layak')
                : implode(' ', $notes),
        ];
    }
}
