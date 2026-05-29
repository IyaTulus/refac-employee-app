<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use jeemce\controllers\CrudTrait;
use jeemce\controllers\AuthTrait;

class UserController extends Controller
{
    use CrudTrait;
    use AuthTrait;

    public function __construct()
    {
        $this->middlewareResourceAccess('users.%');
    }

    public function index(Request $request)
    {
        $roles = Role::query()->orderBy('name')->get();

        $query = User::query()->with(['employee', 'role']);

        User::querySearch($query, [
            'search' => $request->get('search'),
            'filter' => [
                'role_id' => $request->get('role'),
            ],
        ]);

        $users = $query->paginate(10)->withQueryString();

        return view('backend.pages.users.index', compact('users', 'roles'));
    }

    public function form(Request $request, $id = null)
    {
        $isEdit = (bool) $id;

        $user = $isEdit
            ? $this->findModel(['id' => $id])
            : new User();

        if ($isEdit) {
            $this->validateAccess('update', $user);
        } else {
            $this->validateAccess('create', $user);
        }

        $employees = Employee::query()
            ->select(['id', 'employee_code', 'full_name'])
            ->orderBy('full_name')
            ->get();

        $roles = Role::query()
            ->orderBy('name')
            ->get();

        if (! $request->isMethod('get')) {
            $params = $request->all();
            Log::info('users.form.submit.params', ['params' => $params]);
            $validated = Validator::make($params, User::rules($user->exists ? $user : null))->validate();

            $validated['is_active'] = $request->boolean('is_active');

            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }

            unset($validated['password_confirmation']);
            if (! $user->exists && empty($user->id)) {
                $user->id = (string) Str::uuid();
            }
            $user->fill($validated);
            $user->saveOrFail();

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'User berhasil disimpan.',
                    'redirect_url' => route('users.index'),
                ]);
            }

            return redirect()
                ->route('users.index')
                ->with('success', 'User berhasil disimpan.');
        }

        return view($id ? 'backend.pages.users.edit' : 'backend.pages.users.create', compact('user', 'roles', 'employees'));
    }

    public function view(?string $id = null)
    {
        $id ??= (string) Auth::id();

        return redirect()->route('users.edit', $id);
    }

    public function findModel(array $where)
    {
        return User::query()->with(['employee', 'role'])->where($where)->firstOrFail();
    }

    public function delete($id, Request $request)
    {
        $user = $this->findModel(['id' => $id]);
        $this->validateAccess('delete', $user);

        if ((string) Auth::id() === (string) $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        $user->deleteOrFail();

        if ($request->ajax()) {
            return;
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatus(Request $request, string $id): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $user = User::query()->findOrFail($id);
        $this->validateAccess('update', $user);

        if ((string) Auth::id() === (string) $user->id) {
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
        return view('backend.pages.users.change_password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate(User::passwordRules());

        /** @var User $user */
        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('backend.home.index')->with('success', 'Password berhasil diperbarui.');
    }
}
