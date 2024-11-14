<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesiController extends Controller
{
    function index()
    {
        return view('Masuk');
    }

    function login(Request $request)
    {
        // Validasi
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        // Pengecekan apakah email dan password benar
        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($infologin)) {
            if (Auth::user()->role == 'admin') {
                return redirect('/admin/admin');
            } elseif (Auth::user()->role == 'user') {
                return redirect('/admin/user');
            } else {
                return redirect('')->withErrors('Nama pengguna dan password yang dimasukkan tidak sesuai')->withInput();
            }
        }
    }
    function logout()
    {
        Auth::logout();
        return redirect('home');
    }
}
