<?php

namespace App\Api\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class AuthProxy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->merge([
            'client_id' => env('CLIENT_WEB_ADMIN_ID'),
            'client_secret' => env('CLIENT_WEB_ADMIN_SECRET'),
        ]);

        Log::info('auth_proxy', $request->request->all());

        return $next($request);
    }
}
