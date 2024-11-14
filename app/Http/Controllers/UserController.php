<?php

namespace App\Http\Controllers;

use App\Models\DataArea;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Menampilkan dashboard pengguna
    public function dashboard()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Get the authenticated user
        $user = Auth::user();

        // Get the user's role
        $role = $user->role;

        // Retrieve the data area owned by the user based on user_id
        $dataArea = DataArea::where('user_id', $user->id)->get();

        // Set the navbar title
        $navbarTitle = 'Dashboard';

        // Return the dashboard view with necessary data
        return view('user.dashboard', compact('user', 'navbarTitle', 'dataArea', 'role'));
    }

    // Menampilkan profil pengguna
    public function profile()
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login
        return view('user.profile', compact('user'));
    }

    // Mengupdate profil pengguna
    public function updateProfile(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        // Mendapatkan pengguna yang sedang login
        $user = Auth::user();

        // // Update data pengguna
        // $user->update([
        //     'name' => $request->name,
        //     'email' => $request->email,
        // ]);

        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function showLbsmap()
    {
        return view('user.lbsmap');
    }
    public function showKeslahmap()
    {
        return view('user.keslahmap');
    }
    public function showAddAreaForm()
    {
        return view('user.add-area');
    }

    public function panduan()
    {
        $navbarTitle = 'Panduan';
        return view('user.panduan', compact('navbarTitle')); // Ganti dengan nama view untuk beranda
    }
    public function info()
    {
        $navbarTitle = 'Panduan';
        return view('user.info', compact('navbarTitle')); // Ganti dengan nama view untuk beranda
    }
    // Fungsi logout pengguna
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('success', 'Berhasil keluar.');
    }
}
