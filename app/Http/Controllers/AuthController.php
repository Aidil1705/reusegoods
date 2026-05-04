<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // 🔐 Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // 🔑 Proses login
        if (Auth::attempt($credentials, $remember)) {

            // 🔒 keamanan session
            $request->session()->regenerate();

            // 🚫 cek akun aktif
            if (!Auth::user()->is_active) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Akun Anda dinonaktifkan oleh admin'
                ])->onlyInput('email');
            }

            // ✅ cek role dan redirect sesuai role
            $user = Auth::user();
            $userRole = \DB::table('set_roles')
                ->join('roles', 'set_roles.role_id', '=', 'roles.id')
                ->where('set_roles.user_id', $user->id)
                ->first();

            if ($userRole && $userRole->role_name === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('pembeli.dashboard');
        }

        // ❌ login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // 🔒 hapus session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}