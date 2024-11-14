<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login_proses(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($data)) {
            // Cek role pengguna
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard'); // Admin diarahkan ke dashboard admin
            } else {
                return redirect()->route('user.dashboard'); // Pengguna biasa diarahkan ke dashboard pengguna
            }
        } else {
            return redirect()->route('login')->with('Gagal', 'Email atau password salah');
        }
    }

    public function forgot_password()
    {
        return view('auth.forgot-password');
    }

    // public function forgot_password_act(Request $request)
    // {
    //     $customMessage = [
    //         'email.required' => 'Email tidak boleh kosong',
    //         'email.email' => 'Email tidak valid',
    //         'email.exists' => 'Email tidak terdaftar',
    //     ];

    //     $request->validate([
    //         'email' => 'required|email|exists:users,email'
    //     ], $customMessage);

    //     $token = \Str::random(60);

    //     PasswordResetToken::updateOrCreate([
    //         'email' => $request->email,
    //         'token' => $token,
    //         'created_at' => now(),
    //     ]);

    //     Mail::to($request->email)->send(new ResetPasswordMail($token));

    //     $data = [
    //         'email' => $request->email
    //     ];

    //     return redirect()->route('forgot-password')->with('success', 'Kami telah mengirimkan link reset kata sandi ke email Anda');
    // }

    public function logout()
    {
        // Log out the user
        Auth::logout();

        // Redirect to the login page or another route
        return redirect('')->with('success', 'Kamu berhasil keluar!');
    }
}
