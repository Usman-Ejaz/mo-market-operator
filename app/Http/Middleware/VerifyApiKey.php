<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyApiKey
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
        $authorizationKey = str_replace("Bearer ", "", $request->header("authorization"));

        if (isValidKey("app_key", $authorizationKey)) {
            return $next($request);
        }

        return response("Unauthorized", HTTP_UNAUTHORIZED);
    }
}
