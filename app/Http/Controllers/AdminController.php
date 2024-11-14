<?php

namespace App\Http\Controllers;

use App\Models\DataArea;
use App\Models\User;
use App\Models\GeojsonData;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }
        $data = User::get(); // Ambil semua pengguna
        $dataArea = DataArea::get();
        $userCount = User::count();
        $dataCount = DataArea::count();
        $navbarTitle = 'Dashboard';
        return view('admin.dashboard', compact('data', 'userCount', 'dataArea', 'dataCount', 'navbarTitle')); // Kirim ke view
        // return view('dashboard');

    }
    // public function index()
    // {
    //     $data = User::get();
    //     return view('index', compact('data'));
    // }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data['email'] = $request->email;
        $data['name'] = $request->name;
        $data['password'] = Hash::make($request->password);

        User::create($data);
        return redirect()->route('admin.dashboard');
    }

    // // Menampilkan form edit user
    // public function edit(User $data)
    // {
    //     return view('admin.edit', compact('data'));
    // }

    // // Hapus user
    // public function destroy(User $data)
    // {
    //     $data->delete();
    //     return redirect()->route('admin.dashboard')->with('success', 'User berhasil dihapus');
    // }

    public function edit(Request $request, $id)
    {
        $data = User::find($id);

        return view('admin.edit', compact('data'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'nama'      => 'required',
            'password'  => 'nullable',
            'role' => 'required|in:user,admin'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $find = User::find($id);

        $data['email']      = $request->email;
        $data['name']       = $request->nama;

        if ($request->password) {
            $data['password']   = Hash::make($request->password);
        }

        $data['role'] = $request->role;

        $find->update($data);

        return redirect()->route('admin.dashboard')->with('success', 'Data user berhasil diperbarui');;
    }

    public function delete(Request $request, $id)
    {
        $data = User::find($id);

        if ($data) {
            $data->forceDelete();
        }

        return redirect()->route('admin.dashboard');
    }

    public function showLbsmap()
    {
        return view('admin.lbsmap');
    }
    public function showKeslahmap()
    {
        return view('admin.keslahmap');
    }
    public function showAddAreaForm()
    {
        return view('admin.add-area');
    }

    public function panduan()
    {
        $navbarTitle = 'Panduan';
        return view('admin.panduan', compact('navbarTitle')); // Ganti dengan nama view untuk beranda
    }
    public function info()
    {
        $navbarTitle = 'Panduan';
        return view('admin.info', compact('navbarTitle')); // Ganti dengan nama view untuk beranda
    }

    // Fungsi logout pengguna
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('success', 'Berhasil keluar.');
    }

    public function uploadGeoJSON(Request $request)
    {
        $request->validate([
            'geojson' => 'required|array', // Validasi agar geojson berupa array
        ]);

        // Mendapatkan data GeoJSON dari permintaan
        $geojson = $request->input('geojson');
        $name = $geojson['name'] ?? 'Unnamed Layer';

        // Simpan data GeoJSON ke basis data menggunakan fungsi model
        GeoJSONData::create([
            'name' => $name,
            'geometry' => json_encode($geojson['geometry'])
        ]);

        return response()->json(['message' => 'Data GeoJSON berhasil disimpan'], 201);
    }

    public function getGeoJSON($id)
    {
        // Ambil semua data GeoJSON dari tabel
        $geojsonRecords = GeoJSONData::all();

        // Konversi setiap data menjadi format GeoJSON
        $geojsonData = $geojsonRecords->map(function ($record) {
            return [
                'type' => 'Feature',
                'properties' => [
                    'name' => $record->name,
                ],
                'geometry' => json_decode($record->geometry)
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $geojsonData
        ]);
    }
}
