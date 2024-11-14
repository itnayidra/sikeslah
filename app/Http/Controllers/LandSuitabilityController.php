<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class LandSuitabilityController extends Controller
{
    public function query(Request $request)
    {
        // Validasi input
        $request->validate([
            'layer' => 'required|string',
            'parameter' => 'required|string',
            'value' => 'required|string',
        ]);

        $layer = $request->input('layer'); //Ambil jenis tanaman
        $parameter = $request->input('parameter');
        $value = $request->input('value');

        // dd($layer, $value);

        // Tentukan nama tabel berdasarkan layer
        $table = $this->getTableName($layer);
        if (!$table) {
            return response()->json(['error' => 'Invalid layer'], 400);
        }

        // Query berdasarkan layer dan parameter
        $query = DB::table($table)
            ->select(DB::raw('DISTINCT id, keterangan, pl, kapanewon, luas_pl, ST_AsGeoJSON(geom) as geometry'));

        // Kondisional berdasarkan parameter
        if ($parameter === 'Keterangan') {
            $query->where('keterangan', $value);
        } elseif ($parameter === 'Temperatur') {
            $temperature = (float)$value;
            $temperatureRange = $this->getTemperatureRange($layer, $temperature);
            $query->where('keterangan', $temperatureRange);
        } elseif ($parameter === 'Bulankering') {
            $bulankering = (float)$value;
            $bulankeringRange = $this->getBulanKeringRange($layer, $bulankering);
            $query->where('keterangan', $bulankeringRange);
        } elseif ($parameter === 'Drainase') {
            $drainaseRange = $this->getDrainageRange($layer, $value);
            $query->where('keterangan', $drainaseRange);
        } elseif ($parameter === 'Tekstur') {
            $textureRange = $this->getTextureRange($layer, $value);
            $query->where('keterangan', $textureRange);
        } elseif ($parameter === 'Lereng') {
            $lslopeRange = $this->getSlopeRange($layer, $value);
            $query->where('keterangan', $lslopeRange);
        } elseif ($parameter === 'Erosi') {
            $erosionRange = $this->getErosionRange($layer, $value);
            $query->where('keterangan', $erosionRange);
        } elseif ($parameter === 'Banjir') {
            $floodRange = $this->getFloodRange($layer, $value);
            $query->where('keterangan', $floodRange);
        } elseif ($parameter === 'Kapanewon') {
            $query->where('kapanewon', $value);
        } elseif ($parameter === 'Jenislahan') {
            $query->where('pl', $value);
        } else {
            return response()->json(['error' => 'Invalid parameter'], 400);
        }

        // // Ambil hasil query
        $results = $query->get();

        // dd($results->toArray());

        // Format hasil menjadi GeoJSON
        $geoJson = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        foreach ($results as $result) {
            $geoJson['features'][] = [
                'type' => 'Feature',
                'geometry' => json_decode($result->geometry),
                'properties' => [
                    'id' => $result->id,
                    'keterangan' => $result->keterangan,
                    'kapanewon' => $result->kapanewon,
                    'plantType' => $layer,
                    'luas_pl' => $result->luas_pl,
                    'pl' => $result->pl,
                    // 'deskripsi' => $result->deskripsi,
                    'layerLabel' => $this->getLayerLabel($layer)
                ]
            ];
        }

        return response()->json($geoJson);
    }

    private function getTableName($layer)
    {
        $tables = [
            'Padi' => 'padi_gcs',
            'Jagung' => 'jagung_gcs',
            'Kedelai' => 'kedelai_gcs',
            'Padijagung' => 'padijagung_gcs',
            'Padikedelai' => 'padikedelai_gcs',
            'Jagungkedelai' => 'jagungkedelai_gcs',
            'Pajale' => 'padijagungkedelai_gcs',
        ];
        return $tables[$layer] ?? null; // Default jika layer tidak ditemukan
    }
    private function getLayerLabel($layer)
    {
        $labels = [
            'Padi' => 'Padi',
            'Jagung' => 'Jagung',
            'Kedelai' => 'Kedelai',
            'Padijagung' => 'Padi & Jagung',
            'Padikedelai' => 'Padi & Kedelai',
            'Jagungkedelai' => 'Jagung & Kedelai',
            'Pajale' => 'Padi, Jagung & Kedelai',
        ];
        return $labels[$layer] ?? $layer; // Return the default if not found
    }

    private function getTemperatureRange($layer, $temperature)
    {
        // Definisikan rentang temperatur untuk setiap jenis tanaman
        switch ($layer) {
            case 'Padi':
                if ($temperature >= 24 && $temperature <= 29) {
                    return 'Sangat Sesuai';
                } elseif (($temperature >= 22 && $temperature < 24) || ($temperature > 29 && $temperature <= 32)) {
                    return 'Cukup Sesuai';
                } elseif (($temperature >= 18 && $temperature < 22) || ($temperature > 32 && $temperature <= 35)) {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            case 'Jagung':
                if ($temperature >= 20 && $temperature <= 26) {
                    return 'Sangat Sesuai';
                } elseif (($temperature > 26 && $temperature <= 30)) {
                    return 'Cukup Sesuai';
                } elseif (($temperature >= 15 && $temperature < 20) || ($temperature > 30 && $temperature <= 32)) {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            case 'Kedelai':
                if ($temperature >= 23 && $temperature <= 25) {
                    return 'Sangat Sesuai';
                } elseif (($temperature >= 20 && $temperature < 23) || ($temperature > 25 && $temperature <= 28)) {
                    return 'Cukup Sesuai';
                } elseif (($temperature >= 18 && $temperature < 20) || ($temperature > 28 && $temperature <= 32)) {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            default:
                return 'Invalid layer'; // Return an error message if the layer is not recognized
        }
        // Return 'Tidak Sesuai' by default if no conditions match
        return 'Tidak Sesuai';
    }

    private function getBulanKeringRange($layer, $bulankering)
    {
        // Definisikan rentang temperatur untuk setiap jenis tanaman
        switch ($layer) {
            case 'Padi':
                if ($bulankering < 3) {
                    return 'Sangat Sesuai';
                } elseif (($bulankering >= 3 && $bulankering < 9)) {
                    return 'Cukup Sesuai';
                } elseif (($bulankering >= 9 && $bulankering < 9.5)) {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            case 'Jagung':
                if ($bulankering >= 1 && $bulankering <= 7) {
                    return 'Sangat Sesuai';
                } elseif (($bulankering > 7 && $bulankering < 8)) {
                    return 'Cukup Sesuai';
                } elseif (($bulankering >= 8 && $bulankering < 9)) {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            case 'Kedelai':
                if ($bulankering >= 3 && $bulankering <= 7.5) {
                    return 'Sangat Sesuai';
                } elseif (($bulankering > 7.5 && $bulankering < 8.5)) {
                    return 'Cukup Sesuai';
                } elseif (($bulankering >= 8.5 && $bulankering < 9.5)) {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            default:
                return 'Invalid layer'; // Return an error message if the layer is not recognized
        }
        // Return 'Tidak Sesuai' by default if no conditions match
        return 'Tidak Sesuai';
    }

    private function getDrainageRange($layer, $drainage)
    {
        // Definisikan rentang temperatur untuk setiap jenis tanaman
        switch ($layer) {
            case 'Padi':
                if ($drainage === 'terhambat') {
                    // return 'Sangat Sesuai';
                    return rand(0, 1) === 0 ? 'Sangat Sesuai' : 'Cukup Sesuai';
                } elseif ($drainage === 'terhambat') {
                    return 'Cukup Sesuai';
                } elseif ($drainage === 'agak baik' || $drainage === 'baik') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            case 'Jagung':
                if ($drainage === 'baik' || $drainage === 'agak baik') {
                    return 'Sangat Sesuai';
                } elseif ($drainage === 'agak terhambat') {
                    return 'Cukup Sesuai';
                } elseif ($drainage === 'terhambat' || $drainage === 'agak cepat') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            case 'Kedelai':
                if ($drainage === 'baik' || $drainage === 'agak baik') {
                    return 'Sangat Sesuai';
                } elseif ($drainage === 'Agak cepat') {
                    return 'Cukup Sesuai';
                } elseif ($drainage === 'terhambat' || $drainage === 'agak terhambat') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            default:
                return 'Invalid layer'; // Return an error message if the layer is not recognized
        }
        // Return 'Tidak Sesuai' by default if no conditions match
        return 'Tidak Sesuai';
    }
    private function getTextureRange($layer, $texture)
    {
        // Definisikan rentang temperatur untuk setiap jenis tanaman
        switch ($layer) {
            case 'Padi':
                if ($texture === 'agak halus' || $texture === 'sedang') {
                    return 'Sangat Sesuai';
                } elseif ($texture === 'agak kasar' || $texture === 'agak halus' || $texture === 'halus' || $texture === 'sedang') {
                    return 'Cukup Sesuai';
                } elseif ($texture === 'kasar') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }

            case 'Jagung':
                if ($texture === 'sedang' || $texture === 'agak halus') {
                    return 'Sangat Sesuai';
                } elseif ($texture === 'agak kasar' || $texture === 'halus') {
                    return 'Cukup Sesuai';
                } elseif ($texture === 'kasar') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            case 'Kedelai':
                if ($texture === 'sedang' || $texture === 'agak halus') {
                    return 'Sangat Sesuai';
                } elseif ($texture === 'agak kasar' || $texture === 'halus') {
                    return 'Cukup Sesuai';
                } elseif ($texture === 'kasar') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            default:
                return 'Invalid layer'; // Return an error message if the layer is not recognized
        }
        // Return 'Tidak Sesuai' by default if no conditions match
        return 'Tidak Sesuai';
    }
    private function getErosionRange($layer, $erosion)
    {
        // Definisikan rentang temperatur untuk setiap jenis tanaman
        switch ($layer) {
            case 'Padi':
                if ($erosion === 'sangat ringan') {
                    return 'Sangat Sesuai';
                } elseif ($erosion === 'ringan') {
                    return 'Cukup Sesuai';
                } elseif ($erosion === 'sedang') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            case 'Jagung':
                if ($erosion === 'sangat ringan') {
                    return 'Sangat Sesuai';
                } elseif ($erosion === 'ringan') {
                    return 'Cukup Sesuai';
                } elseif ($erosion === 'sedang') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            case 'Kedelai':
                if ($erosion === 'sangat ringan') {
                    return 'Sangat Sesuai';
                } elseif ($erosion === 'ringan') {
                    return 'Cukup Sesuai';
                } elseif ($erosion === 'sedang') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            default:
                return 'Invalid layer'; // Return an error message if the layer is not recognized
        }
        // Return 'Tidak Sesuai' by default if no conditions match
        return 'Tidak Sesuai';
    }
    private function getSlopeRange($layer, $slope)
    {
        // Definisikan rentang temperatur untuk setiap jenis tanaman
        switch ($layer) {
            case 'Padi':
                if ($slope === 'datar') {
                    return 'Sangat Sesuai';
                } elseif ($slope === 'agak landai') {
                    return 'Cukup Sesuai';
                } elseif ($slope === 'landai') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            case 'Jagung':
                if ($slope === 'datar') {
                    return 'Sangat Sesuai';
                } elseif ($slope === 'agak landai') {
                    return 'Cukup Sesuai';
                } elseif ($slope === 'landai') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            case 'Kedelai':
                if ($slope === 'datar') {
                    return 'Sangat Sesuai';
                } elseif ($slope === 'agak landai') {
                    return 'Cukup Sesuai';
                } elseif ($slope === 'landai') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            default:
                return 'Invalid layer'; // Return an error message if the layer is not recognized
        }
        // Return 'Tidak Sesuai' by default if no conditions match
        return 'Tidak Sesuai';
    }
    private function getFloodRange($layer, $flood)
    {
        // Definisikan rentang temperatur untuk setiap jenis tanaman
        switch ($layer) {
            case 'Padi':
                if ($flood === 'sangat ringan' || $flood === 'tidak ada') {
                    return 'Sangat Sesuai';
                } elseif ($flood === 'ringan') {
                    return 'Cukup Sesuai';
                } elseif ($flood === 'sedang') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            case 'Jagung':
                if ($flood === 'sangat ringan' || $flood === 'tidak ada') {
                    return 'Sangat Sesuai';
                } elseif ($flood === 'ringan') {
                    return 'Cukup Sesuai';
                } elseif ($flood === 'sedang') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            case 'Kedelai':
                if ($flood === 'sangat ringan' || $flood === 'tidak ada') {
                    return 'Sangat Sesuai';
                } elseif ($flood === 'ringan') {
                    return 'Cukup Sesuai';
                } elseif ($flood === 'sedang') {
                    return 'Sesuai Marginal';
                } else {
                    return 'Tidak Sesuai';
                }
                break;

            default:
                return 'Invalid layer'; // Return an error message if the layer is not recognized
        }
        // Return 'Tidak Sesuai' by default if no conditions match
        return 'Tidak Sesuai';
    }


    public function querypilihArea(Request $request)
    {
        $kapanewon = $request->input('kapanewon');

        // Query untuk mendapatkan data GeoJSON dari PostgreSQL
        $result = DB::select("
            SELECT ST_AsGeoJSON(geom) as geojson, namobj
            FROM admin_kap_wgs
            WHERE namobj = ?
        ", [$kapanewon]);

        if (count($result) > 0) {
            return response()->json([
                'status' => 'success',
                'data' => $result
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    // public function queryDrawArea(Request $request)
    // {
    //     // Ambil geometri area yang dikirim sebagai parameter GET
    //     $geometry = $request->query('geometry');

    //     // Query PostgreSQL untuk mencari data berdasarkan area yang digambar
    //     $result = DB::select("
    //         SELECT namobj, penutup_la, ket_padi, ket_jag, ket_ked
    //         FROM keslah_all_wgs
    //         WHERE ST_Intersects(geom,ST_GeomFromGeoJSON(?))
    //     ", [$geometry]);

    //     if (count($result) > 0) {
    //         // Menyusun informasi dari hasil query
    //         $info = '';
    //         foreach ($result as $row) {
    //             $info .= 'Kapanewon: ' . $row->namobj . '<br>';
    //             $info .= 'Jenis Lahan: ' . $row->penutup_la . '<br>';
    //             $info .= 'Kesesuaian Lahan: ' . $row->ket_padi . '<br><br>';
    //             $info .= 'Kesesuaian Lahan: ' . $row->ket_jag . '<br><br>';
    //             $info .= 'Kesesuaian Lahan: ' . $row->ket_ked . '<br><br>';
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'info' => $info
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Data tidak ditemukan'
    //         ]);
    //     }
    // }

    // QUERY BY PARAMETERS
    // Fungsi untuk mengevaluasi dan mencocokkan lokasi dari database
    public function queryParameter(Request $request)
    {
        // dd($request->all());

        // Ambil input dari form (temperatur dan drainase)
        $temperature = $request->input('temperature');
        $drainage = $request->input('drainage');
        $texture = $request->input('texture');
        $slope = $request->input('slope');
        $erosion = $request->input('erosion');
        $flood = $request->input('flood');

        // Evaluasi kesesuaian
        $evaluationResults = $this->evaluateConditions($temperature, $drainage, $texture, $slope, $erosion, $flood);
        // dd($evaluationResults);
        // Ambil nilai overall untuk setiap tanaman
        $padiOverall = $evaluationResults['Padi']['Overall'];
        $jagungOverall = $evaluationResults['Jagung']['Overall'];
        $kedelaiOverall = $evaluationResults['Kedelai']['Overall'];

        // Fetch overall data for each plant
        $padiOverall = DB::table('padi_gcs')
            ->select('id', DB::raw('ST_AsGeoJSON(geom) as geom'), 'keterangan', 'luas_pl', 'kapanewon')
            ->where('keterangan', $evaluationResults['Padi']['Overall'])
            ->get();

        $jagungOverall = DB::table('jagung_gcs')
            ->select('id', DB::raw('ST_AsGeoJSON(geom) as geom'), 'keterangan', 'luas_pl', 'kapanewon')
            ->where('keterangan', $evaluationResults['Jagung']['Overall'])
            ->get();

        $kedelaiOverall = DB::table('kedelai_gcs')
            ->select('id', DB::raw('ST_AsGeoJSON(geom) as geom'), 'keterangan', 'luas_pl', 'kapanewon')
            ->where('keterangan', $evaluationResults['Kedelai']['Overall'])
            ->get();

        // Combine all results into a single GeoJSON response
        $features = [];

        // Function to convert results to GeoJSON feature
        function convertToGeoJSONFeature($data, $type)
        {
            $geoJsonFeatures = [];
            foreach ($data as $item) {
                $geoJsonFeatures[] = [
                    'type' => 'Feature',
                    'geometry' => json_decode($item->geom),
                    'properties' => [
                        'id' => $item->id,
                        'keterangan' => $item->keterangan,
                        'plant_type' => $type, // Add plant type for reference
                        'luas_pl' => $item->luas_pl,
                        'kapanewon' => $item->kapanewon,
                    ],
                ];
            }
            return $geoJsonFeatures;
        }

        // Add features for each plant type
        $features = array_merge(
            $features,
            convertToGeoJSONFeature($padiOverall, 'Padi'),
            convertToGeoJSONFeature($jagungOverall, 'Jagung'),
            convertToGeoJSONFeature($kedelaiOverall, 'Kedelai')
        );

        // Prepare the final GeoJSON structure
        $geoJsonData = [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];

        // Return the GeoJSON response
        return response()->json($geoJsonData);


        // Kirim data GeoJSON ke view
        // return view('results', ['geojson' => json_encode($geojson), 'locations' => $locations]);
    }

    // Fungsi untuk mengevaluasi kondisi berdasarkan input pengguna
    public function evaluateConditions($temp, $drain, $texture, $slope, $erosion, $flood)
    {
        // Tanaman yang ingin dievaluasi
        $crops = ['Padi', 'Jagung', 'Kedelai'];

        // Array untuk menyimpan hasil evaluasi
        $results = [];

        foreach ($crops as $crop) {
            $tempClass = $this->evaluateTemperature($temp, $crop);
            $drainClass = $this->evaluateDrainage($drain, $crop);
            $textureClass = $this->evaluateTexture($texture, $crop);
            $slopeClass = $this->evaluateSlope($slope, $crop);
            $erosionClass = $this->evaluateErosion($erosion, $crop);
            $floodClass = $this->evaluateFlood($flood, $crop);

            // // Menentukan kelas kesesuaian keseluruhan
            // $overallClass = $this->getOverallClass([$tempClass, $drainClass]);

            // Menentukan kelas kesesuaian keseluruhan, abaikan nilai null
            $overallClass = $this->getOverallClass(array_filter([$tempClass, $drainClass, $textureClass, $slopeClass, $erosionClass, $floodClass]));


            $results[$crop] = [
                'Temperature' => $tempClass,
                'Drainage' => $drainClass,
                'Texture' => $textureClass,
                'Slope' => $slopeClass,
                'Erosion' => $erosionClass,
                'Flood' => $floodClass,
                'Overall' => $overallClass,
            ];
        }

        return $results;
    }

    private function evaluateTemperature($temp, $crop)
    {
        if (is_null($temp) || $temp === '') {
            return null; // Skip drainage evaluation if not provided
        }
        if ($crop === 'Padi') {
            if ($temp >= 24 && $temp <= 29) {
                return 'Sangat Sesuai';
            } elseif (($temp >= 22 && $temp < 24) || ($temp > 29 && $temp <= 32)) {
                return 'Cukup Sesuai';
            } elseif (($temp >= 18 && $temp < 22) || ($temp > 32 && $temp <= 35)) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        } elseif ($crop === 'Jagung') {
            if ($temp >= 20 && $temp <= 26) {
                return 'Sangat Sesuai';
            } elseif (($temp > 26 && $temp <= 30)) {
                return 'Cukup Sesuai';
            } elseif (($temp >= 15 && $temp < 20) || ($temp > 30 && $temp <= 32)) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        } elseif ($crop === 'Kedelai') {
            if ($temp >= 23 && $temp <= 25) {
                return 'Sangat Sesuai';
            } elseif (($temp >= 20 && $temp < 23) || ($temp > 25 && $temp <= 28)) {
                return 'Cukup Sesuai';
            } elseif (($temp >= 18 && $temp < 20) || ($temp > 28 && $temp <= 32)) {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }
    }
    private function evaluateDrainage($drain, $crop)
    {
        // Jika input drainage kosong, kembalikan null agar tidak dievaluasi
        if (is_null($drain) || $drain === '') {
            return null; // Abaikan evaluasi jika drainage kosong
        }
        // Evaluasi drainase berdasarkan tanaman
        if ($crop === 'Padi') {
            if ($drain === 'terhambat') {
                return 'Sangat Sesuai';
            } elseif ($drain === 'terhambat') {
                return 'Cukup Sesuai';
            } elseif ($drain === 'agak baik' || $drain === 'baik') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        } elseif ($crop === 'Jagung') {
            if ($drain === 'baik' || $drain === 'agak baik') {
                return 'Sangat Sesuai';
            } elseif ($drain === 'Agak terhambat') {
                return 'Cukup Sesuai';
            } elseif ($drain === 'terhambat' || $drain === 'agak cepat') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        } elseif ($crop === 'Kedelai') {
            if ($drain === 'baik' || $drain === 'agak baik') {
                return 'Sangat Sesuai';
            } elseif ($drain === 'Agak cepat') {
                return 'Cukup Sesuai';
            } elseif ($drain === 'terhambat' || $drain === 'agak terhambat') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }
    }

    private function evaluateTexture($texture, $crop)
    {
        // Jika input drainage kosong, kembalikan null agar tidak dievaluasi
        if (is_null($texture) || $texture === '') {
            return null; // Abaikan evaluasi jika textureage kosong
        }
        // Evaluasi texturease berdasarkan tanaman
        if ($crop === 'Padi') {
            if ($texture === 'agak halus' || $texture === 'sedang') {
                return 'Sangat Sesuai';
            } elseif ($texture === 'agak kasar' || $texture === 'sedang' || $texture === 'agak halus' || $texture === 'halus') {
                return 'Cukup Sesuai';
            } elseif ($texture === 'kasar') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        } elseif ($crop === 'Jagung') {
            if ($texture === 'sedang' || $texture === 'agak halus') {
                return 'Sangat Sesuai';
            } elseif ($texture === 'agak kasar' || $texture === 'halus') {
                return 'Cukup Sesuai';
            } elseif ($texture === 'kasar') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        } elseif ($crop === 'Kedelai') {
            if ($texture === 'sedang' || $texture === 'agak halus') {
                return 'Sangat Sesuai';
            } elseif ($texture === 'agak kasar' || $texture === 'halus') {
                return 'Cukup Sesuai';
            } elseif ($texture === 'kasar') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }
    }
    private function evaluateSlope($slope, $crop)
    {
        // Jika input drainage kosong, kembalikan null agar tidak dievaluasi
        if (is_null($slope) || $slope === '') {
            return null; // Abaikan evaluasi jika slopeage kosong
        }
        // Evaluasi slopease berdasarkan tanaman
        if ($crop === 'Padi') {
            if ($slope === 'datar') {
                return 'Sangat Sesuai';
            } elseif ($slope === 'agak landai') {
                return 'Cukup Sesuai';
            } elseif ($slope === 'landai') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        } elseif ($crop === 'Jagung') {
            if ($slope === 'datar') {
                return 'Sangat Sesuai';
            } elseif ($slope === 'agak landai') {
                return 'Cukup Sesuai';
            } elseif ($slope === 'landai') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        } elseif ($crop === 'Kedelai') {
            if ($slope === 'datar') {
                return 'Sangat Sesuai';
            } elseif ($slope === 'agak landai') {
                return 'Cukup Sesuai';
            } elseif ($slope === 'landai') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }
    }
    private function evaluateErosion($erosion, $crop)
    {
        // Jika input drainage kosong, kembalikan null agar tidak dievaluasi
        if (is_null($erosion) || $erosion === '') {
            return null; // Abaikan evaluasi jika erosionage kosong
        }
        // Evaluasi erosionase berdasarkan tanaman
        if ($crop === 'Padi') {
            if ($erosion === 'sangat ringan') {
                return 'Sangat Sesuai';
            } elseif ($erosion === 'ringan') {
                return 'Cukup Sesuai';
            } elseif ($erosion === 'sedang') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        } elseif ($crop === 'Jagung') {
            if ($erosion === 'sangat ringan') {
                return 'Sangat Sesuai';
            } elseif ($erosion === 'ringan') {
                return 'Cukup Sesuai';
            } elseif ($erosion === 'sedang') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        } elseif ($crop === 'Kedelai') {
            if ($erosion === 'sangat ringan') {
                return 'Sangat Sesuai';
            } elseif ($erosion === 'ringan') {
                return 'Cukup Sesuai';
            } elseif ($erosion === 'sedang') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }
    }
    private function evaluateFlood($flood, $crop)
    {
        // Jika input drainage kosong, kembalikan null agar tidak dievaluasi
        if (is_null($flood) || $flood === '') {
            return null; // Abaikan evaluasi jika floodage kosong
        }
        // Evaluasi floodase berdasarkan tanaman
        if ($crop === 'Padi') {
            if ($flood === 'sangat ringan' || $flood === 'tidak ada') {
                return 'Sangat Sesuai';
            } elseif ($flood === 'ringan') {
                return 'Cukup Sesuai';
            } elseif ($flood === 'sedang') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        } elseif ($crop === 'Jagung') {
            if ($flood === 'sangat ringan' || $flood === 'tidak ada') {
                return 'Sangat Sesuai';
            } elseif ($flood === 'ringan') {
                return 'Cukup Sesuai';
            } elseif ($flood === 'sedang') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        } elseif ($crop === 'Kedelai') {
            if ($flood === 'sangat ringan' || $flood === 'tidak ada') {
                return 'Sangat Sesuai';
            } elseif ($flood === 'ringan') {
                return 'Cukup Sesuai';
            } elseif ($flood === 'sedang') {
                return 'Sesuai Marginal';
            } else {
                return 'Tidak Sesuai';
            }
        }
    }

    private function getOverallClass($classes)
    { // Remove null values
        $filteredClasses = array_filter($classes, function ($class) {
            return !is_null($class);
        });

        // If all parameters were skipped, return a default value or 'Data Tidak Cukup'
        if (empty($filteredClasses)) {
            return 'Data Tidak Cukup';
        }
        // Menentukan kelas kesesuaian keseluruhan
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

    public function searchLocation(Request $request)
    {
        // dd($request->all());

        // Validasi input
        $request->validate([
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
        ]);

        // Ambil koordinat dari input
        $longitude = $request->input('longitude');
        $latitude = $request->input('latitude');

        // Query ke database untuk mencari lokasi berdasarkan titik
        $results = DB::table('sawah_keslah_gcs')
            ->select('kapanewon', 'ketpadi', 'ketjag', 'ketked', DB::raw('ST_AsGeoJSON(ST_MakePoint(?, ?)) as geometry')) // Menggunakan ST_AsGeoJSON
            ->whereRaw("ST_Contains(geom, ST_SetSRID(ST_MakePoint(?, ?), 4326))", [$longitude, $latitude, $longitude, $latitude]) // Pastikan untuk mengirimkan 4 parameter
            ->get();
        // dd($longitude, $latitude);

        // Format hasil menjadi GeoJSON
        $geoJson = [
            'type' => 'FeatureCollection',
            'features' => []
        ];
        // dd($geoJson);

        foreach ($results as $result) {
            $geoJson['features'][] = [
                'type' => 'Feature',
                'geometry' => json_decode($result->geometry),
                'properties' => [
                    'kapanewon' => $result->kapanewon,
                    'padi' => $result->ketpadi,
                    'jagung' => $result->ketjag,
                    'kedelai' => $result->ketked,
                ]
            ];
        }

        return response()->json($geoJson);
    }
}
