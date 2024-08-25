<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        $userAdmin = Role::where('name', 'admin')->first();

        if ($user) {
            $userRole = $user->role; 
            if ($userRole && $userRole->name === 'admin') {
                return $next($request);
            }
        }

        return response()->json([
            "message" => "Anda tidak dapat mengakses halaman Admin"
        ], 403);
    }
}
