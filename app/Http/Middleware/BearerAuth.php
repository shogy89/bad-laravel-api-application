<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BearerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        $validToken = DB::table('bearer_tokens')
        ->where('token', $token)
        ->where('expiry_time', '>', date('Y-m-d H:i:s'))
        ->first();

        if (!$validToken){
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        
        return $next($request);
    }
}
