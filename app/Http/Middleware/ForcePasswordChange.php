<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guard('employee')->check() &&
            auth()->guard('employee')->user()->force_password_change) {

            if (!$request->routeIs('employee.password.change') &&
                !$request->routeIs('employee.logout')) {
                return redirect()->route('employee.password.change')
                    ->with('warning', 'You must change your password before continuing.');
            }
        }

        return $next($request);
    }
}
