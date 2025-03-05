<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleTaskCreation
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();

        if (RateLimiter::tooManyAttempts("task-create:$ip", 2)) {
            return response()->json([
                'message' => 'Too many task creation attempts. Please wait.',
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        RateLimiter::hit("task-create:$ip", 60);

        return $next($request);
    }
}
