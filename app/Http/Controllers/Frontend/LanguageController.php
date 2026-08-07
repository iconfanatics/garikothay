<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale): RedirectResponse
    {
        $supported = ['bn', 'en'];

        if (!in_array($locale, $supported)) {
            abort(400);
        }

        if ($request->query('context') === 'admin') {
            Session::put('admin_locale', $locale);
            
            // Optional: update admin user locale if they have one (admins might be a different model)
            // if (auth('admin')->check()) {
            //     auth('admin')->user()->update(['locale' => $locale]);
            // }
        } else {
            Session::put('locale', $locale);

            if (auth()->check()) {
                auth()->user()->update(['locale' => $locale]);
            }
        }

        return back()->withHeaders(['Vary' => 'Accept-Language']);
    }
}
