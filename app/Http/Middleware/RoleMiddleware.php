<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $args): Response
    {

        if (!Auth::check()) abort(401);
        /** @var User */
        $user = Auth::user();
        $roles = explode("|", $args); // convert $roles to array

        foreach ($roles as $role) {

            if ($user->hasRole($role))
                return $next($request);
        }

        abort(403, 'No Sufficient Role');
    }
}
