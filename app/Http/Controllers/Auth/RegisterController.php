<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(edu|ac\.[a-z]{2,}|university\.[a-z]{2,})$/i'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'university_id' => ['nullable', 'string', 'max:50'],
        ], [
            'email.regex' => 'Please use a valid university email address (.edu or academic domain).',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'university_id' => $request->university_id,
            'role' => 'student',
            'karma_points' => 0,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome to Campus Connect Pro!');
    }
}
