<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Menampilkan form registrasi
    public function showRegisterForm()
    {
        return view('register');
    }

    // // Memproses registrasi
    // public function register(Request $request)
    // {
    //     // Validasi input pengguna
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);

    //     // Membuat user baru
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password), // Enkripsi password
    //         'role' => 'user', // Default role sebagai user biasa
    //     ]);

    //     // Login otomatis setelah registrasi
    //     Auth::login($user);

    //     // Redirect ke dashboard pengguna
    //     return redirect()->route('user.dashboard')->with('success', 'Registrasi berhasil!');
    // }

    public function register_proses(Request $request)
    {
        $request->validate([
            'nama'  => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        $data['name']       = $request->nama;
        $data['email']      = $request->email;
        $data['password']   = Hash::make($request->password);
        $data['role'] = 'user';

        User::create($data);

        $login = [
            'email'     => $request->email,
            'password'  => $request->password
        ];

        if (Auth::attempt($login)) {
            return redirect()->route('user.dashboard');
        } else {
            return redirect()->route('login')->with('failed', 'Email atau Password Salah');
        }
    }
}
