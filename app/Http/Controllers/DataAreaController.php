<?php

namespace App\Http\Controllers;

use App\Models\DataArea;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class DataAreaController extends Controller
{
    // Menampilkan form tmbah area
    public function showAddAreaForm()
    {
        if (auth()->user()->role == 'admin') {
            return view('admin.add-area');
        } else {
            return view('user.add-area');
        }
        // return view('add-area');
    }

    public function store_area(Request $request)
    {
        // dd('Function called');

        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'nama_area' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'area' => 'required|string',
            'temperatur' => 'required|numeric',
            'bulan_basah' => 'required|numeric|max:100',
            'drainase' => 'required|in:Sangat terhambat,Terhambat,Agak terhambat,Agak baik,Baik,Agak cepat,Cepat',
            'tekstur' => 'required|in:Sangat halus,Halus,Agak halus,Sedang,Agak kasar,Kasar',
            'kedalaman_tanah' => 'required|numeric|min:0',
            'kejenuhan_basa' => 'required|numeric|min:0',
            'ktk' => 'required|numeric|min:0',
            'ph' => 'required|numeric|min:0|max:14',
            'c_organik' => 'required|numeric|min:0',
            'erosi' => 'required|in:Tidak ada,Sangat ringan,Ringan,Sedang,Berat,Sangat berat',
            'lereng' => 'required|in:Datar,Agak landai,Landai,Agak curam,Curam,Sangat curam',
            'banjir' => 'required|in:Tidak ada,Rendah,Sedang,Tinggi,Sangat tinggi',
            'recommendation' => 'nullable|string',
        ]);
        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        // Hitung rekomendasi kesesuaian
        $recommendation = $this->calculateRecommendation($request);
        // dd($recommendation);

        // Simpan data ke dalam model
        $dataArea = [
            'nama_area' => $request->nama_area,
            'deskripsi' => $request->deskripsi,
            'area' => DataArea::raw("ST_GeomFromText('{$request->area}', 4326)"),
            'geojson' => $this->convertToGeoJSON($request->area), // Mengonversi ke GeoJSON
            'luas' => $request->luas,
            'user_id' => auth()->id(), // Simpan ID user yang login            
            'temperatur' => $request->temperatur,
            'bulan_basah' => $request->bulan_basah,
            'drainase' => $request->drainase,
            'tekstur' => $request->tekstur,
            'kedalaman_tanah' => $request->kedalaman_tanah,
            'kejenuhan_basa' => $request->kejenuhan_basa,
            'ktk' => $request->ktk,
            'ph' => $request->ph,
            'c_organik' => $request->c_organik,
            'erosi' => $request->erosi,
            'lereng' => $request->lereng,
            'banjir' => $request->banjir,
            'recommendation' => json_encode($recommendation),
        ];

        DataArea::create($dataArea);
        // dd('Data disimpan dengan sukses!');


        // // Directly save the data to the database
        // DataArea::create($dataArea);
        // dd($dataArea);

        // Mengarahkan pengguna berdasarkan peran mereka
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Data berhasil disimpan dan evaluasi selesai.');
        } elseif (Auth::user()->role === 'user') {
            return redirect()->route('user.dashboard')->with('success', 'Data berhasil disimpan dan evaluasi selesai.');
        }

        // // Jika tidak ada peran yang sesuai, bisa diarahkan ke halaman default
        // return redirect()->route('home')->with('success', 'Data berhasil disimpan dan evaluasi selesai.');
    }

    private function convertToGeoJSON($wkt)
    {
        // Menghapus keyword "POLYGON" jika ada dan mengekstrak koordinat
        $wkt = trim(str_replace('POLYGON', '', $wkt));

        // Debug: Cek hasil WKT setelah diubah
        // dd($wkt);

        // Menggunakan regex untuk mendapatkan pasangan koordinat longitude dan latitude
        preg_match_all('/\(([^)]+)\)/', $wkt, $matches);

        // Debug: Cek hasil matches dari regex
        // dd($matches);

        // Array untuk menyimpan koordinat
        $coordinates = [];

        // Mengolah setiap bagian polygon (bisa lebih dari 1 ring)
        foreach ($matches[1] as $match) {
            // Memisahkan koordinat dengan koma
            $coords = explode(',', $match);

            // Debug: Cek hasil pemisahan koordinat
            // dd($coords);

            $polygon = []; // Array polygon kosong

            foreach ($coords as $coord) {
                // Memisahkan longitude dan latitude
                preg_match('/([-\d.]+)\s+([-\d.]+)/', $coord, $point);

                // Debug: Cek hasil pemisahan point
                // dd($point);

                if (count($point) == 3) {
                    $lng = (float) $point[1];
                    $lat = (float) $point[2];

                    // Debug sebelum dan setelah konversi
                    // dd("Longitude: $lng, Latitude: $lat");

                    $polygon[] = [$lng, $lat]; // Menambahkan koordinat ke dalam polygon
                }
            }

            // Menambahkan polygon yang telah diproses ke dalam koordinat
            if (!empty($polygon)) {
                $coordinates[] = $polygon;
            }
        }

        // Membuat GeoJSON format
        $geojson = [
            'type' => 'Polygon',  // Atau 'MultiPolygon' jika terdapat lebih dari satu polygon
            'coordinates' => $coordinates,
        ];

        // Debug: Cek hasil GeoJSON yang dihasilkan
        // dd($geojson);

        // Mengembalikan hasil GeoJSON dalam format JSON
        return json_encode($geojson);
    }

    // Fungsi untuk menghitung kesesuaian lahan
    private function calculateRecommendation(Request $request)
    {
        $crops = ['Padi', 'Jagung', 'Kedelai'];
        $results = [];

        foreach ($crops as $crop) {
            $tempClass = $this->evaluateTemperatureForCrop($request->temperatur, $crop);
            $drainClass = $this->evaluateDrainageForCrop($request->drainase, $crop);
            $bulanClass = $this->evaluateBulanForCrop($request->bulan_basah, $crop);
            $textureClass = $this->evaluateTextureForCrop($request->tekstur, $crop);
            $kedalamanClass = $this->evaluateKedalamanForCrop($request->kedalaman_tanah, $crop);
            $jenuhClass = $this->evaluateKejenuhanForCrop($request->kejenuhan_basa, $crop);
            $ktkClass = $this->evaluateKtkForCrop($request->ktk, $crop);
            $phClass = $this->evaluatePhForCrop($request->ph, $crop);
            $corganikClass = $this->evaluateCorganikForCrop($request->c_organik, $crop);
            $erosiClass = $this->evaluateErosiForCrop($request->erosi, $crop);
            $lerengClass = $this->evaluateLerengForCrop($request->lereng, $crop);
            $banjirClass = $this->evaluateBanjirForCrop($request->banjir, $crop);

            // Tentukan kelas evaluasi untuk tanaman Padi
            if ($crop === 'Padi') {
                $cropperakaran = $this->getOverallClass([$drainClass, $textureClass, $kedalamanClass, $erosiClass, $lerengClass]);
            } else {
                // Untuk Jagung dan Kedelai, drainase dihitung terpisah dari cropperakaran
                $cropperakaran = $this->getOverallClass([$textureClass, $kedalamanClass, $erosiClass, $lerengClass]);
            }

            // Evaluasi umum untuk semua tanaman
            $crophara = $this->getOverallClass([$jenuhClass, $ktkClass, $phClass, $corganikClass]);
            $croperosi = $this->getOverallClass([$erosiClass, $lerengClass]);

            // Tentukan kelas keseluruhan untuk setiap tanaman
            if ($crop === 'Padi') {
                $results[$crop] = $this->getOverallClass([$tempClass, $bulanClass, $cropperakaran, $crophara, $croperosi, $banjirClass]);
            } else {
                $results[$crop] = $this->getOverallClass([$tempClass, $bulanClass, $drainClass, $cropperakaran, $crophara, $croperosi, $banjirClass]);
            }
        }

        $recommendationList = [];
        foreach ($results as $crop => $class) {
            $recommendationList[] = "$crop: $class"; // Menambahkan hasil ke dalam list
        }

        return $recommendationList; // Mengembalikan hasil dalam bentuk array
    }

    // // Membuat rekomendasi berdasarkan hasil evaluasi
    // $recommendation = '';
    // foreach ($results as $crop => $class) {
    //     $recommendation .= "$crop: $class; ";
    // }

    // return rtrim($recommendation, "; "); // Menghapus trailing semicolon


    // PERSAMAANUTK PERHITUNGAN
    private function evaluateTemperatureForCrop($temp, $crop)
    {
        // Evaluasi untuk padi
        if ($crop === 'Padi') {
            if ($temp >= 25 && $temp <= 28) {
                return 'Sangat Sesuai';
            } elseif (($temp > 28 && $temp <= 30) || ($temp >= 23 && $temp < 25)) {
                return 'Cukup Sesuai';
            } elseif (($temp > 30 && $temp <= 33) || ($temp >= 21 && $temp < 23)) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk jagung
        if ($crop === 'Jagung') {
            if ($temp >= 20 && $temp <= 25) {
                return 'Sangat Sesuai';
            } elseif (($temp > 25 && $temp <= 27) || ($temp >= 18 && $temp < 20)) {
                return 'Cukup Sesuai';
            } elseif (($temp > 27 && $temp <= 30) || ($temp >= 16 && $temp < 18)) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk kedelai
        if ($crop === 'Kedelai') {
            if ($temp >= 24 && $temp <= 28) {
                return 'Sangat Sesuai';
            } elseif (($temp > 28 && $temp <= 30) || ($temp >= 22 && $temp < 24)) {
                return 'Cukup Sesuai';
            } elseif (($temp > 30 && $temp <= 33) || ($temp >= 20 && $temp < 22)) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        return 'Tidak Sesuai'; // Kembalikan 'Tidak Sesuai' jika tanaman tidak dikenali
    }

    private function evaluateDrainageForCrop($drain, $crop)
    {
        // Evaluasi untuk padi
        if ($crop === 'Padi') {
            if ($drain === 'Agak terhambat' || $drain === 'Agak baik') {
                return 'Sangat Sesuai';
            } elseif ($drain === 'Terhambat' || $drain === 'Baik') {
                return 'Cukup Sesuai';
            } elseif ($drain === 'Sangat terhambat' || $drain === 'Agak cepat') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk jagung
        if ($crop === 'Jagung') {
            if ($drain === 'Baik' || $drain === 'Agak baik') {
                return 'Sangat Sesuai';
            } elseif ($drain === 'Agak cepat' || $drain === 'Agak terhambat') {
                return 'Cukup Sesuai';
            } elseif ($drain === 'Terhambat') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk kedelai
        if ($crop === 'Kedelai') {
            if ($drain === 'Baik' || $drain === 'Agak baik') {
                return 'Sangat Sesuai';
            } elseif ($drain === 'Agak cepat' || $drain === 'Agak terhambat') {
                return 'Cukup Sesuai';
            } elseif ($drain === 'Terhambat') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        return 'Tidak Sesuai'; // Kembalikan 'Tidak Sesuai' jika tanaman tidak dikenali
    }

    private function evaluateBulanForCrop($bulan, $crop)
    {
        // Evaluasi untuk padi
        if ($crop === 'Padi') {
            if ($bulan >= 6 && $bulan <= 8) {
                return 'Sangat Sesuai';
            } elseif ($bulan > 4 && $bulan <= 6) {
                return 'Cukup Sesuai';
            } elseif (($bulan > 8 && $bulan <= 10) || ($bulan >= 2 && $bulan < 4)) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk jagung
        if ($crop === 'Jagung') {
            if ($bulan >= 3 && $bulan <= 5) {
                return 'Sangat Sesuai';
            } elseif (($bulan < 3) || ($bulan >= 5 && $bulan <= 7)) {
                return 'Cukup Sesuai';
            } elseif ($bulan > 7 && $bulan <= 8) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk kedelai
        if ($crop === 'Kedelai') {
            if ($bulan >= 2 && $bulan <= 4) {
                return 'Sangat Sesuai';
            } elseif ($bulan > 4 && $bulan <= 6) {
                return 'Cukup Sesuai';
            } elseif (($bulan > 6) || ($bulan < 2)) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        return 'Tidak Sesuai'; // Kembalikan 'Tidak Sesuai' jika tanaman tidak dikenali
    }
    private function evaluateTextureForCrop($texture, $crop)
    {
        // Evaluasi untuk padi
        if ($crop === 'Padi') {
            if ($texture === 'Halus' || $texture === 'Agak halus') {
                return 'Sangat Sesuai';
            } elseif ($texture === 'Sedang') {
                return 'Cukup Sesuai';
            } elseif ($texture === 'Agak kasar') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk jagung
        if ($crop === 'Jagung') {
            if ($texture === 'Halus' || $texture === 'Agak halus' || $texture === 'Sedang') {
                return 'Sangat Sesuai';
            } elseif ($texture === 'Halus' || $texture === 'Agak halus' || $texture === 'Sedang') {
                return 'Cukup Sesuai';
            } elseif ($texture === 'Agak kasar') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk kedelai
        if ($crop === 'Kedelai') {
            if ($texture === 'Halus' || $texture === 'Agak halus' || $texture === 'Sedang') {
                return 'Sangat Sesuai';
            } elseif ($texture === 'Halus' || $texture === 'Agak halus' || $texture === 'Sedang') {
                return 'Cukup Sesuai';
            } elseif ($texture === 'Agak kasar') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        return 'Tidak Sesuai'; // Kembalikan 'Tidak Sesuai' jika tanaman tidak dikenali
    }
    private function evaluateKedalamanForCrop($kedalaman, $crop)
    {
        // Evaluasi untuk padi
        if ($crop === 'Padi') {
            if ($kedalaman > 50) {
                return 'Sangat Sesuai';
            } elseif ($kedalaman >= 41 && $kedalaman <= 50) {
                return 'Cukup Sesuai';
            } elseif ($kedalaman > 25 && $kedalaman <= 40) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }
        // Evaluasi untuk jagung
        if ($crop === 'Jagung') {
            if ($kedalaman > 60) {
                return 'Sangat Sesuai';
            } elseif ($kedalaman >= 41 && $kedalaman <= 60) {
                return 'Cukup Sesuai';
            } elseif ($kedalaman > 25 && $kedalaman <= 40) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }
        // Evaluasi untuk kedelai
        if ($crop === 'Kedelai') {
            if ($kedalaman > 50) {
                return 'Sangat Sesuai';
            } elseif ($kedalaman >= 31 && $kedalaman <= 50) {
                return 'Cukup Sesuai';
            } elseif ($kedalaman > 20 && $kedalaman <= 30) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }
    }
    private function evaluateKejenuhanForCrop($jenuh, $crop)
    {
        // Evaluasi untuk padi
        if ($crop === 'Padi') {
            if ($jenuh > 50) {
                return 'Sangat Sesuai';
            } elseif ($jenuh >= 35 && $jenuh <= 50) {
                return 'Cukup Sesuai';
            } elseif ($jenuh < 35) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk jagung
        if ($crop === 'Jagung') {
            if ($jenuh > 50) {
                return 'Sangat Sesuai';
            } elseif ($jenuh >= 35 && $jenuh <= 50) {
                return 'Cukup Sesuai';
            } elseif ($jenuh < 35) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk kedelai
        if ($crop === 'Kedelai') {
            if ($jenuh > 35) {
                return 'Sangat Sesuai';
            } elseif ($jenuh >= 20 && $jenuh <= 35) {
                return 'Cukup Sesuai';
            } elseif ($jenuh < 20) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        return 'Tidak Sesuai'; // Kembalikan 'Tidak Sesuai' jika tanaman tidak dikenali

    }
    private function evaluateKtkForCrop($ktk, $crop)
    {
        // Evaluasi untuk padi
        if ($crop === 'Padi') {
            if ($ktk > 16) {
                return 'Sangat Sesuai';
            } elseif ($ktk >= 5 && $ktk <= 16) {
                return 'Cukup Sesuai';
            } elseif ($ktk < 5) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk padi
        if ($crop === 'Jagung') {
            if ($ktk > 16) {
                return 'Sangat Sesuai';
            } elseif ($ktk >= 5 && $ktk <= 16) {
                return 'Cukup Sesuai';
            } elseif ($ktk < 5) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk padi
        if ($crop === 'Kedelai') {
            if ($ktk > 16) {
                return 'Sangat Sesuai';
            } elseif ($ktk >= 5 && $ktk <= 16) {
                return 'Cukup Sesuai';
            } elseif ($ktk < 5) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        return 'Tidak Sesuai'; // Kembalikan 'Tidak Sesuai' jika tanaman tidak dikenali

    }
    private function evaluatePhForCrop($ph, $crop)
    {
        // Evaluasi untuk padi
        if ($crop === 'Padi') {
            if ($ph >= 5.5 && $ph <= 7.0) {
                return 'Sangat Sesuai';
            } elseif (($ph >= 4.5 && $ph <= 5.4) || ($ph >= 7.0 && $ph <= 8.0)) {
                return 'Cukup Sesuai';
            } elseif ($ph > 8.0) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk padi
        if ($crop === 'Jagung') {
            if ($ph >= 5.6 && $ph <= 7.0) {
                return 'Sangat Sesuai';
            } elseif (($ph >= 5.0 && $ph <= 5.5) || ($ph >= 7.1 && $ph <= 8.0)) {
                return 'Cukup Sesuai';
            } elseif ($ph > 8.0) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk padi
        if ($crop === 'Kedelai') {
            if ($ph >= 5.6 && $ph <= 7.5) {
                return 'Sangat Sesuai';
            } elseif (($ph >= 5.0 && $ph <= 5.5) || ($ph >= 7.6 && $ph <= 8.0)) {
                return 'Cukup Sesuai';
            } elseif ($ph > 8.0) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        return 'Tidak Sesuai'; // Kembalikan 'Tidak Sesuai' jika tanaman tidak dikenali

    }
    private function evaluateCorganikForCrop($corganik, $crop)
    {
        // Evaluasi untuk padi
        if ($crop === 'Padi') {
            if ($corganik > 1.2) {
                return 'Sangat Sesuai';
            } elseif ($corganik >= 0.8 && $corganik <= 1.2) {
                return 'Cukup Sesuai';
            } elseif ($corganik < 0.8) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }
        if ($crop === 'Jagung') {
            if ($corganik > 1.2) {
                return 'Sangat Sesuai';
            } elseif ($corganik >= 0.8 && $corganik <= 1.2) {
                return 'Cukup Sesuai';
            } elseif ($corganik < 0.8) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }
        if ($crop === 'Kedelai') {
            if ($corganik > 1.2) {
                return 'Sangat Sesuai';
            } elseif ($corganik >= 0.8 && $corganik <= 1.2) {
                return 'Cukup Sesuai';
            } elseif ($corganik < 0.8) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }
    }
    private function evaluateErosiForCrop($erosi, $crop)
    {
        // Evaluasi untuk padi
        if ($crop === 'Padi') {
            if ($erosi === 'Tidak ada') {
                return 'Sangat Sesuai';
            } elseif ($erosi === 'Sangat ringan') {
                return 'Cukup Sesuai';
            } elseif ($erosi === 'Ringan') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk jagung
        if ($crop === 'Jagung') {
            if ($erosi === 'Tidak ada') {
                return 'Sangat Sesuai';
            } elseif ($erosi === 'Sangat ringan') {
                return 'Cukup Sesuai';
            } elseif ($erosi === 'Ringan' || $erosi === 'Sedang') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk kedelai
        if ($crop === 'Kedelai') {
            if ($erosi === 'Tidak ada') {
                return 'Sangat Sesuai';
            } elseif ($erosi === 'Sangat ringan') {
                return 'Cukup Sesuai';
            } elseif ($erosi === 'Ringan' || $erosi === 'Sedang') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        return 'Tidak Sesuai'; // Kembalikan 'Tidak Sesuai' jika tanaman tidak dikenali

    }
    private function evaluateLerengForCrop($lereng, $crop)
    {
        // Evaluasi untuk padi
        if ($crop === 'Padi') {
            if ($lereng === 'Datar') {
                return 'Sangat Sesuai';
            } elseif ($lereng === 'Agak landai') {
                return 'Cukup Sesuai';
            } elseif ($lereng === 'Landai') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk jagung
        if ($crop === 'Jagung') {
            if ($lereng === 'Datar') {
                return 'Sangat Sesuai';
            } elseif ($lereng === 'Landai') {
                return 'Cukup Sesuai';
            } elseif ($lereng === 'Agak curam') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk kedelai
        if ($crop === 'Kedelai') {
            if ($lereng === 'Datar') {
                return 'Sangat Sesuai';
            } elseif ($lereng === 'Agak landai') {
                return 'Cukup Sesuai';
            } elseif ($lereng === 'Landai') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        return 'Tidak Sesuai'; // Kembalikan 'Tidak Sesuai' jika tanaman tidak dikenali

    }
    private function evaluateBanjirForCrop($banjir, $crop)
    {
        // Evaluasi untuk padi
        if ($crop === 'Padi') {
            if ($banjir === 'Rendah') {
                return 'Sangat Sesuai';
            } elseif ($banjir === 'Sedang') {
                return 'Cukup Sesuai';
            } elseif ($banjir === 'Tinggi') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk jagung
        if ($crop === 'Jagung') {
            if ($banjir === 'Tidak ada') {
                return 'Sangat Sesuai';
            } elseif ($banjir === 'Tidak ada') {
                return 'Cukup Sesuai';
            } elseif ($banjir === 'Rendah' || $banjir === 'Sedang') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        // Evaluasi untuk kedelai
        if ($crop === 'Kedelai') {
            if ($banjir === 'Tidak ada') {
                return 'Sangat Sesuai';
            } elseif ($banjir === 'Tidak ada') {
                return 'Cukup Sesuai';
            } elseif ($banjir === 'Rendah' || $banjir === 'Sedang') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }

        return 'Tidak Sesuai'; // Kembalikan 'Tidak Sesuai' jika tanaman tidak dikenali

    }

    private function getOverallClass($classes)
    {
        if (in_array('Tidak Sesuai', $classes)) {
            return 'Tidak Sesuai';
        } elseif (in_array('Sesuai Marginal', $classes)) {
            return 'Sesuai Marginal';
        } elseif (in_array('Cukup Sesuai', $classes)) {
            return 'Cukup Sesuai';
        } else {
            return 'Sangat Sesuai';
        }
    }

    public function showEvaluasiDataUser()
    {
        $userId = auth()->id(); // Ambil ID pengguna yang sedang login
        $areas = DataArea::where('user_id', $userId)->get(); // Ambil data area berdasarkan user_id

        return view('user.dashboard', compact('areas'));
    }

    public function showEditAddAreaForm($id)
    {
        // $data = DataArea::find($id);
        $data = DataArea::select('*', DB::raw('ST_AsText(area) as area_wkt'))->find($id);

        // $dataArea = DataArea::all(); // Ambil semua data dari model DataArea

        if (auth()->user()->role == 'admin') {
            return view('admin.edit_addarea', compact('data'));
        } else {
            return view('user.edit_addarea', compact('data'));
        }
        // return view('add-area');
    }

    public function getPolygon($id)
    {
        // Ambil data Polygon berdasarkan ID
        $polygon = DataArea::find($id);

        if ($polygon) {
            // Mengembalikan GeoJSON yang disimpan dalam kolom 'geojson'
            return response()->json(json_decode($polygon->geojson));
        } else {
            // Jika data tidak ditemukan
            return response()->json(['error' => 'Polygon not found'], 404);
        }
    }

    // public function showPolygon()
    // {
    //     $data = DataArea::select('*')->addSelect('geojson');

    //     if (auth()->user()->role == 'admin') {
    //         return view('admin.edit_addarea', compact('data'));
    //     } else {
    //         return view('user.edit_addarea', compact('data'));
    //     }
    // }
    // public function getPolygonData($id)
    // {
    //     $dataArea = DataArea::table('evaluasi')
    //         ->select('id', DataArea::raw('ST_AsText(area) as area'), /* other columns you need */)
    //         ->where('id', $id) // Use the correct condition to fetch the specific record
    //         ->first();

    //     // Ubah data menjadi GeoJSON jika perlu
    //     $dataAreageojosn = [
    //         'type' => 'FeatureCollection',
    //         'features' => $dataArea->map(function ($dataAreageojosn) {
    //             return [
    //                 'type' => 'Feature',
    //                 'geometry' => json_decode($dataAreageojosn->geojson), // Asumsikan kolom `geojson` menyimpan data GeoJSON
    //                 'properties' => [
    //                     'name' => $dataAreageojosn->name, // Misalnya kolom nama atau properti lain
    //                 ]
    //             ];
    //         })
    //     ];

    //     return response()->json($dataAreageojosn);
    //     dd($dataAreageojosn->all());
    // }

    public function updateAddArea(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_area' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'area' => 'required|string',
            'luas' => 'required|numeric',
            'temperatur' => 'required|numeric',
            'bulan_basah' => 'required|numeric|max:100',
            'drainase' => 'required|in:Sangat terhambat,Terhambat,Agak terhambat,Agak baik,Baik,Agak cepat,Cepat',
            'tekstur' => 'required|in:Sangat halus,Halus,Agak halus,Sedang,Agak kasar,Kasar',
            'kedalaman_tanah' => 'required|numeric|min:0',
            'kejenuhan_basa' => 'required|numeric|min:0',
            'ktk' => 'required|numeric|min:0',
            'ph' => 'required|numeric|min:0|max:14',
            'c_organik' => 'required|numeric|min:0',
            'erosi' => 'required|in:Tidak ada,Sangat ringan,Ringan,Sedang,Berat,Sangat berat',
            'lereng' => 'required|in:Datar,Agak landai,Landai,Agak curam,Curam,Sangat curam',
            'banjir' => 'required|in:Tidak ada,Rendah,Sedang,Tinggi,Sangat tinggi',
            'recommendation' => 'nullable|string',
        ]);
        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = DataArea::find($id);
        $data->update($request->all());

        // dd($request->all());

        // Mengarahkan pengguna berdasarkan peran mereka
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Data berhasil disimpan dan evaluasi selesai.');
        } elseif (Auth::user()->role === 'user') {
            return redirect()->route('user.dashboard')->with('success', 'Data berhasil disimpan dan evaluasi selesai.');
        }
    }

    public function delete(Request $request, $id)
    {
        $data = DataArea::find($id);

        if ($data) {
            $data->forceDelete(); // Menghapus data
        }

        // Cek role pengguna untuk mengarahkan ke dashboard yang sesuai
        $user = auth()->user();
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard'); // Arahkan ke dashboard admin
        } else {
            return redirect()->route('user.dashboard'); // Arahkan ke dashboard user
        }
    }

    public function unduhGeojson()
    {
        // Ambil data GeoJSON dari tabel 'evaluasi'
        $data = DB::table('evaluasi')->get();

        // Format GeoJSON
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        foreach ($data as $row) {
            $geojson['features'][] = [
                'type' => 'Feature',
                'geometry' => json_decode($row->geojson), // Ambil kolom geojson dari database
                'properties' => [
                    'nama_area' => $row->nama_area,
                    'deskripsi' => $row->deskripsi,
                    'luas_m2' => $row->luas,
                    'temperatur' => $row->temperatur,
                    'bulan_basah' => $row->bulan_basah,
                    'drainase' => $row->drainase,
                    'tekstur' => $row->tekstur,
                    'kedalaman_tanah' => $row->kedalaman_tanah,
                    'kejenuhan_basa' => $row->kejenuhan_basa,
                    'ktk' => $row->ktk,
                    'ph' => $row->ph,
                    'c_organik' => $row->c_organik,
                    'erosi' => $row->erosi,
                    'lereng' => $row->lereng,
                    'banjir' => $row->banjir,
                    'hasil_penilaian' => $row->recommendation
                ]
            ];
        }

        // Return sebagai response JSON
        return response()->json($geojson, 200, [
            'Content-Disposition' => 'attachment; filename="data.geojson"'
        ]);
    }
}
