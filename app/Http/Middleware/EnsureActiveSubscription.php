<?php

namespace App\Http\Middleware;

use App\Http\Resources\GeneralError;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response|GeneralError
    {
        $user = $request->user();

        if (!$user) {
            return new GeneralError(['message'=>'Unauthenticated', 'code' => 401]);
        }

        $active = $user->activeSubscription()->with('plan')->first();

        if (!$active) {
            return new GeneralError([
                'message' => 'API access denied. Please subscribe to a plan to access this endpoint.',
                'code' => 403
            ]);
        }

        return $next($request);
    }
}
