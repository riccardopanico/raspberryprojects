<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use App\Models\Impostazioni;
use Closure;
use Illuminate\Support\Facades\Auth;

class TokenMiddleware {
    public function handle($request, Closure $next) {
        try {
            $token = session('access_token');
            if($request->bearerToken() == $token || $request->api_token == $token) {
                return $next($request);
            }
        } catch (\Throwable $th) {
            return response()->json(['messaggio' => 'Non autorizzato!']);
        }
        return response()->json(['messaggio' => 'Non autorizzato!']);
    }
}
