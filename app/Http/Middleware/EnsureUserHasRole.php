<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\Role;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $roles = [
            "admin" => Role::Admin,
            "store" => Role::Store,
            "customer" => Role::Customer,
        ];
        if (Role::from($request->user()->role_id) != $roles[$role]) {
            return response()->json([
                'message' => 'User does not have the required role.',
            ], 403);
        }
        return $next($request);
    }
}
