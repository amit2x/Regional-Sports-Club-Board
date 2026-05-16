<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard === 'employee') {
                    $employee = Auth::guard('employee')->user();

                    if ($employee->hasRole('super_admin')) {
                        return redirect()->route('admin.dashboard');
                    } elseif ($employee->hasRole('regional_sports_secretary')) {
                        return redirect()->route('admin.regional.dashboard');
                    } elseif ($employee->hasRole('airport_sports_secretary')) {
                        return redirect()->route('admin.airport.dashboard');
                    } else {
                        return redirect()->route('employee.dashboard');
                    }
                }

                return redirect('/home');
            }
        }

        return $next($request);
    }
}
