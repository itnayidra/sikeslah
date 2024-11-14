<?php

namespace App\Http\Controllers;

use App\Models\DataArea;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Cek peran pengguna
        $role = auth()->user()->role; // pastikan sudah login
        $navbarTitle = 'Beranda';

        // Tampilkan view yang sama tetapi dengan data yang berbeda jika diperlukan
        return view('home', compact('role', 'navbarTitle'));
        // return view('home', compact('navbarTitle')); // Ganti dengan nama view untuk beranda
    }
    public function beranda()
    {
        $navbarTitle = 'Beranda';
        return view('home', compact('navbarTitle')); // Ganti dengan nama view untuk beranda
    }
    // Menampilkan dashboard pengguna
    public function dashboard()
    {
        $dataArea = DataArea::get();
        $navbarTitle = 'Dashboard'; // Judul untuk navbar
        return view('user.dashboard', compact('navbarTitle', 'dataArea'));
    }
    public function layanan()
    {
        $navbarTitle = 'Layanan';
        return view('layanan', compact('navbarTitle')); // Ganti dengan nama view untuk beranda
    }
    public function services()
    {
        $role = auth()->user()->role; // pastikan sudah login
        $navbarTitle = 'Layanan';
        return view('layanan-login', compact('role', 'navbarTitle')); // Ganti dengan nama view untuk beranda
    }
    public function lbsmap()
    {
        $role = auth()->user()->role; // pastikan sudah login
        $navbarTitle = 'Peta Lahan Baku Sawah';
        return view('lbsmap', compact('role', 'navbarTitle')); // Ganti dengan nama view untuk beranda
    }
    public function layananlbsmap()
    {
        $navbarTitle = 'Layanan';
        return view('lbsmap', compact('navbarTitle')); // Ganti dengan nama view untuk beranda
    }
    public function layanankeslahmap()
    {
        $navbarTitle = 'Layanan';
        return view('keslahmap', compact('navbarTitle')); // Ganti dengan nama view untuk beranda
    }
    public function keslahmap()
    {
        $role = auth()->user()->role; // pastikan sudah login
        $navbarTitle = 'Peta Kesesuaian Lahan';
        return view('keslahmap', compact('role', 'navbarTitle')); // Ganti dengan nama view untuk beranda
    }
    public function panduan()
    {
        $navbarTitle = 'Panduan';
        return view('panduan', compact('navbarTitle')); // Ganti dengan nama view untuk beranda
    }
    public function info()
    {
        $navbarTitle = 'Panduan';
        return view('info', compact('navbarTitle')); // Ganti dengan nama view untuk beranda
    }
}
