<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoginUserController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    
    public function store()
    {
        $attributes = request()->validate([
            'email' => 'required|email',
            'password' => ['required'],
        ]);
        $key = 'login-attempts:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'error' => 'Too many login attempts. Please try again in ' . RateLimiter::availableIn($key) . ' seconds.'
            ]);
        }
        RateLimiter::hit($key, 60);

        if (!Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                'error' => 'Invalid credentials.'
            ]);
        }
        RateLimiter::clear($key);

        request()->session()->regenerate();
        return redirect('/');
    }

    public function destroy()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    }
}
