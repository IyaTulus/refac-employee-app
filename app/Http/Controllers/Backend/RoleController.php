<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use jeemce\controllers\AuthTrait;
use jeemce\controllers\CrudTrait;
use jeemce\models\Menu;

class RoleController extends Controller
{
    use CrudTrait;
    use AuthTrait;

    public function __construct()
    {
        $this->middlewareResourceAccess('role-permission.%');
    }

    public function index(Request $request)
    {
        $query = Role::query();

        Role::querySearch($query, [
            'search' => $request->get('search'),
        ]);

        $roles = $query->paginate(10)->withQueryString();

        return view('backend.pages.roles.index', compact('roles'));
    }

    public function form(Request $request, ?string $id = null)
    {
        $role = $id ? $this->findModel(['id' => $id]) : new Role();

        if (! $request->isMethod('get')) {
            if ($id) {
                $this->validateAccess('update', $role);
            } else {
                $this->validateAccess('create', $role);
            }

            $validated = $request->validate(Role::rules($role));

            if ($role->exists) {
                $role->update(['name' => $validated['name']]);
            } else {
                $role = Role::create(['name' => $validated['name']]);
            }

            $this->syncAccesses($role->id, $validated['accesses'] ?? []);

            return redirect()
                ->route('role-permission.index')
                ->with('success', $id ? 'Role berhasil diperbarui.' : 'Role berhasil dibuat.');
        }

        if ($id) {
            $this->validateAccess('update', $role);
        } else {
            $this->validateAccess('create', $role);
        }

        $menus = $this->menuMatrix($role->id ?? null);
        $selectedPermissions = $role->exists ? $this->selectedPermissions($role->id) : [];

        return view($id ? 'backend.pages.roles.edit' : 'backend.pages.roles.create', compact('role', 'menus', 'selectedPermissions'));
    }

    public function view(string $id)
    {
        return redirect()->route('role-permission.edit', $id);
    }

    public function findModel(array $where)
    {
        return Role::query()->where($where)->firstOrFail();
    }

    public function delete($id, Request $request)
    {
        $role = $this->findModel(['id' => $id]);
        $this->validateAccess('delete', $role);

        if ($role->users()->exists()) {
            return back()->with('error', 'Role tidak bisa dihapus karena masih dipakai user.');
        }

        DB::table('accesses')->where('id_role', $role->id)->delete();
        $role->deleteOrFail();

        if ($request->ajax()) {
            return null;
        }

        return redirect()
            ->route('role-permission.index')
            ->with('success', 'Role berhasil dihapus.');
    }

    private function menuMatrix(?int $roleId = null): array
    {
        $menus = Menu::query()
            ->whereNull('id_menu')
            ->where('type', 'owner_sidebar')
            ->where('status', 'publish')
            ->orderBy('sort')
            ->get();

        $menus = $menus->map(function ($item) {
            $item->tree = $this->loadMenuChildren((int) $item->id);

            return $item;
        })->values()->all();

        $selected = $roleId ? $this->selectedPermissions($roleId) : [];

        $attachAccess = function ($items) use (&$attachAccess, $selected): array {
            if ($items instanceof \Illuminate\Support\Collection) {
                $items = $items->all();
            }

            if (! is_array($items)) {
                $items = (array) $items;
            }

            foreach ($items as $item) {
                $item->access = $selected[$item->id] ?? [
                    'read' => 'none',
                    'view' => 'none',
                    'create' => 'none',
                    'update' => 'none',
                    'delete' => 'none',
                    'publish' => 'none',
                ];

                if (! empty($item->tree)) {
                    $item->tree = $attachAccess($item->tree);
                }
            }

            return $items;
        };

        return $attachAccess($menus);
    }

    private function loadMenuChildren(int $parentId)
    {
        return Menu::query()
            ->where('id_menu', $parentId)
            ->where('type', 'owner_sidebar')
            ->where('status', 'publish')
            ->orderBy('sort')
            ->get()
            ->map(function ($item) {
                $item->tree = $this->loadMenuChildren((int) $item->id);

                return $item;
            })
            ->values()
            ->all();
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
