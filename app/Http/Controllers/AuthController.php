<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if ($request->boolean('remember')) {
                cookie()->queue('remember_email', $request->email, 60 * 24 * 30); // 30 days
            } else {
                cookie()->queue(cookie()->forget('remember_email'));
            }

            $request->session()->regenerate();
            if (auth()->user()->isSuperAdmin()) {
                return redirect()->intended('inventory');
            }

            if (auth()->user()->role === 'admin') {
                return redirect()->intended('/');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau Password yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
