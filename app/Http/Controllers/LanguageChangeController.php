<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Class LanguageChangeController
 * Handles language functionality.
 *
 * @author Hau Sian Cing
 * @created 05/07/2023
 */
class LanguageChangeController extends Controller
{
    /**
     * to switch language 
     *@author Hau Sian Cing
     * @created 05/07/2023
     * @return object
     */
    public function switchLanguage(Request $request)
    {
        $language = $request->input('language');
        if (in_array($language, ['en', 'mm'])) {
            App::setLocale($language);
            session(['language' => $language]);
        }

        return redirect()->back();
    }
}
