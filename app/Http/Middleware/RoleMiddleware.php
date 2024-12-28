<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
   /*  public function handle(Request $request, Closure $next,$roles)
    {
        if (!auth()->user() || !auth()->user()->hasRole($roles)) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    } */
   /*  public function handle($request, Closure $next, ...$roles)
    {
        // Check if user is authenticated and has at least one role
        if (!auth()->check() || !$this->userHasAnyRole(auth()->user(), $roles)) {
            abort(403, 'Unauthorized action');
        }
    
        return $next($request);
    }

    // Helper function for role check
    protected function userHasAnyRole($user, $roles)
    {
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }
        return false;
    } */

    public function handle($request, Closure $next, ...$roles)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            abort(403, 'Unauthorized user.');
        }
        // dd($roles);
        // Check if the user has at least one of the specified roles
        if (!$this->userHasAnyRole(auth()->user(), $roles)) {
            abort(403, 'User have no role.');
        }

        $permission = $request->route()->getName();
        // dd($permission);
        if (!Auth::user()->hasPermission($permission)) {
            abort(403, "You don't have permissions.");
        }

        return $next($request);
    }

    // Helper function for role check
    protected function userHasAnyRole($user, $roles)
    {
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    // Helper function to check if user has specific permission
    protected function userHasPermission($user, $permission)
    {
        return $user->permissions->contains('name', $permission);
    }

}
