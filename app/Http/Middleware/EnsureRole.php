<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Role tidak ditemukan.');
        }

        $roleName = $user->role?->name;

        if (! $roleName) {
            abort(403, 'Role tidak ditemukan.');
        }

        $allowed = collect($roles)
            ->map(fn ($role) => strtolower(trim($role)))
            ->contains(strtolower(trim($roleName)));

        if (! $allowed) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
