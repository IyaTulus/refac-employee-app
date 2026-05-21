<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\TransportAllowance;
use Illuminate\Http\Request;
use jeemce\controllers\AuthTrait;

class TransportAllowanceController extends Controller
{
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

    public function create()
    {
        $employees = Employee::query()->select(['id', 'employee_code', 'full_name', 'distance_km'])->orderBy('full_name')->get();
        $baseFare = config('transport.base_fare', 5000); // default

        return view('backend.pages.transport-allowances.create', compact('employees', 'baseFare'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer'],
            'work_days' => ['required', 'integer', 'min:0', 'max:31'],
        ]);

        $employee = Employee::findOrFail($data['employee_id']);
        $baseFare = config('transport.base_fare', 5000);
        $distance = $employee->distance_km ?? 0;

        // simple rule: if distance <= 0 then total 0, else baseFare * work_days
        $subtotal = $baseFare; // per day
        $total = $distance > 0 ? ($subtotal * $data['work_days']) : 0;

        $allowance = TransportAllowance::create([
            'employee_id' => $employee->id,
            'month' => $data['month'],
            'year' => $data['year'],
            'base_fare' => $subtotal,
            'distance_km' => $distance,
            'work_days' => $data['work_days'],
            'total_amount' => $total,
            'notes' => $distance > 0 ? 'Layak' : 'Jarak tidak mencukupi',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('transport-allowances.index')->with('success', 'Perhitungan tunjangan berhasil disimpan.');
    }

    public function destroy(string $id)
    {
        $allowance = TransportAllowance::findOrFail($id);
        $this->validateAccess('delete', $allowance);
        $allowance->delete();

        return redirect()->back()->with('success', 'Data tunjangan berhasil dihapus.');
    }
}
