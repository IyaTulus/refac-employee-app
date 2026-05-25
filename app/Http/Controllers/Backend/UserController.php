<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use jeemce\controllers\AuthTrait;

class UserController extends Controller
{
    use AuthTrait;

    public function __construct()
    {
        $this->middlewareResourceAccess('users.%');
    }

    public function index(Request $request)
    {
        $roles = Role::query()->orderBy('name')->get();

        $users = User::query()
            ->with(['employee', 'role'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->string('search'));

                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role_id', $request->integer('role'));
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('backend.pages.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $user = new User();
        $roles = Role::query()->orderBy('name')->get();
        $employees = Employee::query()
            ->select(['id', 'employee_code', 'full_name'])
            ->orderBy('full_name')
            ->get();

        return view('backend.pages.users.create', compact('user', 'roles', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        $user = new User();
        $user->id = (string) Str::uuid();
        $user->employee_id = $validated['employee_id'];
        $user->role_id = $validated['role_id'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->password = $validated['password'];
        $user->is_active = $request->boolean('is_active');
        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(?string $id = null)
    {
        $id ??= (string) auth()->id();
        return redirect()->route('users.edit', $id);
    }

    public function edit(string $id)
    {
        $user = User::query()->with(['employee', 'role'])->findOrFail($id);
        $this->validateAccess('update', $user);

        $roles = Role::query()->orderBy('name')->get();
        $employees = Employee::query()
            ->select(['id', 'employee_code', 'full_name'])
            ->orderBy('full_name')
            ->get();

        return view('backend.pages.users.edit', compact('user', 'roles', 'employees'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::query()->findOrFail($id);
        $this->validateAccess('update', $user);

        $validated = $this->validatePayload($request, $user);

        $user->employee_id = $validated['employee_id'];
        $user->role_id = $validated['role_id'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }
        $user->is_active = $request->boolean('is_active');
        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = User::query()->findOrFail($id);
        $this->validateAccess('delete', $user);

        if ((string) auth()->id() === (string) $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatus(Request $request, string $id): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $user = User::query()->findOrFail($id);
        $this->validateAccess('update', $user);

        if ((string) auth()->id() === (string) $user->id) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun yang sedang digunakan.');
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        if (! $request->expectsJson()) {
            return back()->with('success', $user->is_active ? 'User diaktifkan.' : 'User dinonaktifkan.');
        }

        return response()->json([
            'success' => true,
            'is_active' => $user->is_active,
            'message' => $user->is_active ? 'User diaktifkan.' : 'User dinonaktifkan.',
        ]);
    }

    public function checkUsername(Request $request): JsonResponse
    {
        $username = trim((string) $request->query('username', ''));
        $ignore = (string) $request->query('ignore', '');

        $formatValid = (bool) preg_match('/^[a-z0-9]{6,}$/', $username);
        $isUnique = ! User::query()
            ->when($ignore !== '', fn($query) => $query->where('id', '!=', $ignore))
            ->where('username', $username)
            ->exists();

        return response()->json([
            'valid' => $formatValid && $isUnique,
            'format_valid' => $formatValid,
            'unique' => $isUnique,
        ]);
    }

    public function employeeSuggest(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        $employees = Employee::query()
            ->select(['id', 'employee_code', 'full_name'])
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($sub) use ($query) {
                    $sub->where('full_name', 'like', "%{$query}%")
                        ->orWhere('employee_code', 'like', "%{$query}%");
                });
            })
            ->orderBy('full_name')
            ->limit(10)
            ->get();

        return response()->json([
            'data' => $employees,
        ]);
    }

    public function editPassword()
    {
        return view('backend.user.change_password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $user = auth()->user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('backend.home.index')->with('success', 'Password berhasil diperbarui.');
    }

    private function validatePayload(Request $request, ?User $user = null): array
    {
        $userId = $user?->id;

        return $request->validate([
            'employee_id' => ['required', 'exists:employees,id', Rule::unique('users', 'employee_id')->ignore($userId)],
            'role_id' => ['required', 'exists:roles,id'],
            'username' => ['required', 'string', 'min:6', 'regex:/^[a-z0-9]+$/', Rule::unique('users', 'username')->ignore($userId)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['required', 'string', Rule::unique('users', 'phone')->ignore($userId)],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', 'min:6'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
