<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Employee;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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
        // Get the employee ID and password from the request
        $employee = session('employee');

        if ($employee) {
            return $next($request);
        }
        return redirect()->route('login');
    }
}
