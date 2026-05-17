<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                // Handle employee guard
                if ($guard === 'employee') {
                    $employee = Auth::guard('employee')->user();

                    // If force password change is required
                    if ($employee->force_password_change) {
                        return redirect()->route('employee.password.change')
                            ->with('warning', 'Please change your password before continuing.');
                    }

                    // Redirect based on role
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

                // Default redirect for web guard
                return redirect('/home');
            }
        }

        return $next($request);
    }
}
