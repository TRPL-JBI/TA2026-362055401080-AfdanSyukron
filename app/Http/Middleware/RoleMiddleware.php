<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('/');
        }

        $user = auth()->user();
        $userRole = strtolower(trim($user->role->role ?? ''));

        // Normalize expected roles (e.g. support comma-separated or multiple arguments)
        $allowedRoles = [];
        foreach ($roles as $role) {
            $splitRoles = explode(',', $role);
            foreach ($splitRoles as $r) {
                $allowedRoles[] = strtolower(trim($r));
            }
        }

        // If 'staff admin' is allowed, also allow 'admin' and 'staff humas' for compatibility
        if (in_array('staff admin', $allowedRoles)) {
            $allowedRoles[] = 'admin';
            $allowedRoles[] = 'staff humas';
        }

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
