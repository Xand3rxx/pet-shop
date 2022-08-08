<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\JWTTokenGenerator;

class EnsureTokenIsValid
{
    use JWTTokenGenerator;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('api/*')) {
            if ($this->decodeToken($request->bearerToken())) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }
        return $next($request);
    }
}
