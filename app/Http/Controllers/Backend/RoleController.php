<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use jeemce\controllers\AuthTrait;
use jeemce\models\Menu;

class RoleController extends Controller
{
    use AuthTrait;

    public function __construct()
    {
        $this->middlewareResourceAccess('role-permission.%');
    }

    public function index()
    {
        $roles = Role::query()
            ->orderBy('id')
            ->paginate(10);

        return view('backend.pages.roles.index', compact('roles'));
    }

    public function create()
    {
        $role = new Role();
        $menus = $this->menuMatrix();
        $selectedPermissions = [];

        return view('backend.pages.roles.create', compact('role', 'menus', 'selectedPermissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:128', 'unique:roles,name'],
            'accesses' => ['nullable', 'array'],
            'accesses.*.read' => ['nullable', 'in:all,none,only'],
            'accesses.*.view' => ['nullable', 'in:all,none,only'],
            'accesses.*.create' => ['nullable', 'in:all,none,only'],
            'accesses.*.update' => ['nullable', 'in:all,none,only'],
            'accesses.*.delete' => ['nullable', 'in:all,none,only'],
            'accesses.*.publish' => ['nullable', 'in:all,none,only'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
        ]);

        $this->syncAccesses($role->id, $validated['accesses'] ?? []);

        return redirect()
            ->route('role-permission.index')
            ->with('success', 'Role berhasil dibuat.');
    }

    public function show(int $id)
    {
        return redirect()->route('role-permission.edit', $id);
    }

    public function edit(int $id)
    {
        $role = Role::query()->findOrFail($id);
        $this->validateAccess('update', $role);

        $menus = $this->menuMatrix($role->id);
        $selectedPermissions = $this->selectedPermissions($role->id);

        return view('backend.pages.roles.edit', compact('role', 'menus', 'selectedPermissions'));
    }

    public function update(Request $request, int $id)
    {
        $role = Role::query()->findOrFail($id);
        $this->validateAccess('update', $role);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:128', Rule::unique('roles', 'name')->ignore($role->id)],
            'accesses' => ['nullable', 'array'],
            'accesses.*.read' => ['nullable', 'in:all,none,only'],
            'accesses.*.view' => ['nullable', 'in:all,none,only'],
            'accesses.*.create' => ['nullable', 'in:all,none,only'],
            'accesses.*.update' => ['nullable', 'in:all,none,only'],
            'accesses.*.delete' => ['nullable', 'in:all,none,only'],
            'accesses.*.publish' => ['nullable', 'in:all,none,only'],
        ]);

        $role->update([
            'name' => $validated['name'],
        ]);

        $this->syncAccesses($role->id, $validated['accesses'] ?? []);

        return redirect()
            ->route('role-permission.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $role = Role::query()->findOrFail($id);
        $this->validateAccess('delete', $role);

        if ($role->users()->exists()) {
            return back()->with('error', 'Role tidak bisa dihapus karena masih dipakai user.');
        }

        DB::table('accesses')->where('id_role', $role->id)->delete();
        $role->delete();

        return redirect()
            ->route('role-permission.index')
            ->with('success', 'Role berhasil dihapus.');
    }

    private function menuMatrix(?int $roleId = null): array
    {
        $menus = Menu::tree([
            'id_menu' => null,
            'status' => 'active',
        ]);

        $selected = $roleId ? $this->selectedPermissions($roleId) : [];

        $attachAccess = function (array $items) use (&$attachAccess, $selected): array {
            foreach ($items as $item) {
                $item->access = $selected[$item->id] ?? [
                    'read' => 'none',
                    'view' => 'none',
                    'create' => 'none',
                    'update' => 'none',
                    'delete' => 'none',
                    'publish' => 'none',
                ];

                if (!empty($item->tree)) {
                    $item->tree = $attachAccess($item->tree);
                }
            }

            return $items;
        };

        return $attachAccess($menus);
    }

    private function selectedPermissions(int $roleId): array
    {
        $rows = DB::table('accesses')
            ->where('id_role', $roleId)
            ->get();

        $selected = [];

        foreach ($rows as $row) {
            $selected[$row->id_menu] = [
                'read' => $row->read ?? 'none',
                'view' => $row->view ?? 'none',
                'create' => $row->create ?? 'none',
                'update' => $row->update ?? 'none',
                'delete' => $row->delete ?? 'none',
                'publish' => $row->publish ?? 'none',
            ];
        }

        return $selected;
    }

    private function syncAccesses(int $roleId, array $accesses): void
    {
        DB::table('accesses')->where('id_role', $roleId)->delete();

        foreach ($accesses as $menuId => $access) {
            $row = [
                'read' => $access['read'] ?? 'none',
                'view' => $access['view'] ?? 'none',
                'create' => $access['create'] ?? 'none',
                'update' => $access['update'] ?? 'none',
                'delete' => $access['delete'] ?? 'none',
                'publish' => $access['publish'] ?? 'none',
            ];

            $allNone = collect($row)->every(fn($value) => $value === 'none');

            if ($allNone) {
                continue;
            }

            DB::table('accesses')->insert([
                'id_role' => $roleId,
                'id_menu' => (int) $menuId,
                'read' => $row['read'],
                'view' => $row['view'],
                'create' => $row['create'],
                'update' => $row['update'],
                'delete' => $row['delete'],
                'publish' => $row['publish'],
            ]);
        }
    }
}
