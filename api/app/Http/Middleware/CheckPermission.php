<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission, $guard = null): Response
    {
        $user = auth()->guard('sanctum')->user();
        if (!$user) return response()->json(['message' => 'Unauthenticated'], 401);

        if (!Cache::has('role:' . $user->role_id)) {
            $role = Role::find($user->role_id);
            if ($role) Cache::put('role:' . $user->role_id, $role->permissions);
        }

        $permissionRole = collect(Cache::get('role:' . $user->role_id) ?? [])->pluck('name')->toArray();
        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if (in_array($permission, $permissionRole)) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'You don\'t have permission',
        ], 403);
    }
}
