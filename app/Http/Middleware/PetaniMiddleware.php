<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetaniMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('petani')->check()) {
            return redirect(url('/'));
            // response()->json(['message' => 'Unauthorized. Only Petani allowed'], 403);
        }
        return $next($request);
    }
}
