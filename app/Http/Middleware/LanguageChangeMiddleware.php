<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

/**
 * Class LanguageChangeMiddleware
 * Handles language functionality.
 *
 * @author Hau Sian Cing
 * @created 05/07/2023
 */
class LanguageChangeMiddleware
{
    /**
     * Handle language request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @author Hau Sian Cing
     * @created 05/07/2023
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $language = session('language');
        if ($language) {
            App::setLocale($language);
        }

        return $next($request);
    }
}
