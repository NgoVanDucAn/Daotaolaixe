<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerForm()
    {
        return view("auth.register");
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return redirect()->route('login')->with('success', 'You have successfully registered');
    }

    public function loginForm()
    {
        return view('auth.login')->with('success', 'You have successfully logged in');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' =>'required|email',
            'password' => 'required|min:8'
        ]);

        $credentials = $request->only('email', 'password');

        $remember = $request->has('remember');

        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'You have successfully logged in');
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have successfully logged out');
    }

    public function dashboard()
    {
        return view('layouts.admin');
    }
}
