<?php

namespace App\Providers;

use App\Http\Resources\GeneralError;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       RateLimiter::for('subscription', function (Request $request) {
            $user = $request->user();

            if (!$user) {
                $limit = Limit::perMinute(1)->by($request->ip());
            } else {
                $active = $user->activeSubscription()->with('plan')->first();
                if (!$active || !$active->plan) {
                    $limit = Limit::perMinute(1)->by($user->id ?: $request->ip());
                } else {
                    $calls = (int) $active->plan->calls_per_minute;
                    $limit = Limit::perMinute($calls)->by($user->id);
                }
            }

            return $limit->response(function(Request $request, array $headers) {
                return (new GeneralError([
                    'message' => 'Maximum request limit reached. Please try again later.',
                    'code'    => 429,
                    'toast'   => true,
                ]))
                ->response()
                ->withHeaders($headers)
                ->setStatusCode(429);
            });
        });
    }
}
