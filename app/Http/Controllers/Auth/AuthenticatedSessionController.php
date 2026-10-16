<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        if (session('emp_logged_in')) {
            return redirect()->route('employee.home');
        }

        $request->session()->regenerate();
        //////////////////////


        if (Auth::user()->email == "katryana22@gmail.com" || Auth::user()->email == "user@pixel.com") {

            return redirect()->intended(route('dashboard', absolute: false));
        } else {

            $user = Auth::user();
            // التأكد من أن user_agent مصفوفة دائماً
            $currentUserAgent = is_array($user->user_agent) ? $user->user_agent : [];

            // جلب الـ User-Agent الحالي للجهاز
            $newUserAgent = $request->header('User-Agent');

            if (count($currentUserAgent) < 1) {
                // السماح فقط بإضافة أول جهاز
                if (!in_array($newUserAgent, $currentUserAgent)) {
                    $currentUserAgent[] = $newUserAgent;
                    $user->user_agent = $currentUserAgent;
                    $user->save();
                }
                return redirect()->intended('/home');
            } elseif (!in_array($newUserAgent, $currentUserAgent)) {
                // منع تسجيل الدخول من أي جهاز آخر إذا كان هناك جهاز مسجل بالفعل
                Auth::logout();
                return redirect()->back()->withErrors([
                    'email' => '❌ لا يمكنك تسجيل الدخول من جهاز مختلف'
                ]);
            }

            // إذا كان الجهاز مسجل مسبقًا، السماح بالدخول
            return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
