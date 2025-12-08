<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {

      
        $permissions = session('permissions');

        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true);
        }

        if (!is_array($permissions) || !in_array($permission, $permissions)) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
