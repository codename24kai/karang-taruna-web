<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tampilkan Form Login
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Proses Login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // Coba Login (Laravel otomatis cek password yang di-hash)
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate(); // Security session fixation

            return redirect()->intended('dashboard'); // Lempar ke dashboard
        }

        // Kalau gagal
        return back()->withErrors([
            'username' => 'Username atau password salah, coba lagi ya!',
        ])->onlyInput('username');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
