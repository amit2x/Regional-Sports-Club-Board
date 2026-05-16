<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmployeeStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guard('employee')->check()) {
            $employee = auth()->guard('employee')->user();

            if ($employee->employment_status !== 'active') {
                auth()->guard('employee')->logout();

                return redirect()->route('employee.login')
                    ->withErrors(['employee_id' => 'Your account is ' . $employee->employment_status . '. Please contact administrator.']);
            }
        }

        return $next($request);
    }
}
