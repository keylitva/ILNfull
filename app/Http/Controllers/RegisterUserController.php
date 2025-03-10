<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class RegisterUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        //dd($request->all());
      // Validate the request data
      $request->validate([
        'name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'email_confirmation' => 'required|same:email',
        'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
    ]);
    $alternative_id = substr(bin2hex(random_bytes(5)), 0, 10);
    //dd($alternative_id);
    // Create user
    $user = User::create([
        'name' => $request->name . ' ' . $request->surname,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'alternative_id' => $alternative_id,
        'created_at' => now(),
        'updated_at' => now(),
        'permissions' => 'user',
    ]);

    // Log the user in (optional)
    auth()->login($user);

    return redirect()->route('home')->with('success', 'Registration successful.');

    }
}
