<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use App\Rules\BdPhone;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request, CartRepositoryInterface $cartRepo): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Merge guest cart into user cart
            $sessionCartId = session('cart_id');
            if ($sessionCartId) {
                $cartRepo->mergeCarts($sessionCartId, Auth::user());
                session()->forget('cart_id');
            }

            return redirect()->intended(route('customer.dashboard'));
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->onlyInput('email');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255', 
                Rule::unique('users')->whereNull('deleted_at')
            ],
            'phone' => [
                'nullable', 'string', new BdPhone(), 
                Rule::unique('users', 'phone')->whereNull('deleted_at')
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'locale' => app()->getLocale(),
        ]);

        event(new Registered($user));
        event(new UserRegistered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function showEmailVerification(Request $request): View|RedirectResponse
    {
        if ($request->user() && $request->user()->hasVerifiedEmail()) {
            return redirect()->route('customer.dashboard');
        }
        
        return view('auth.verify-email');
    }

    public function verifyEmail(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('customer.dashboard');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('customer.dashboard')->with('verified', true);
    }

    public function resendVerification(Request $request): RedirectResponse
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
