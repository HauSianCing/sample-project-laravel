<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Employee;

/**
 * Class CheckForEmployeeAuthenticated
 * Authenticate for employee
 *
 * @author Hau Sian Cing
 * @created 21/6/2023
 */
class CheckForEmployeeAuthenticated
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
        $employeeKey = session('employee.id');

        // Make sure the logged-in employee still exists and has not been inactivated.
        if ($employeeKey && Employee::whereKey($employeeKey)->exists()) {
            return $next($request);
        }

        $request->session()->forget('employee');
        return redirect()->route('login');
    }
}
