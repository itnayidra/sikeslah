@extends('layout.mainapplogin')

@section('head')
<style>
/* General table styling */
/* .card{
    width:1300px;
} */

        div.dt-container-pengguna {
            width: 800px;
            margin: 0 auto;
        }
        div.dt-container-eval {
            width: 1225px;
            margin: 0 auto;
        }
        th, td { white-space: nowrap; }
        div.dataTables_wrapper {
            width: 1225px;
            margin: 0 auto;
        }


</style>
@endsection

@section('content')
    <section class="content-wrapper mt-2">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 style="color:#ffc451;margin-top: 70px;">Halo, <span>{{ Auth::user()->name }}</span>!</h1>
                    </div>
                    <p style="color:white; font-size:16px; padding-top:20px">Di sini Anda dapat melihat data kesesuaian lahan berdasarkan parameter yang telah dimasukkan. 
                    Tabel di bawah ini menampilkan hasil analisis untuk membantu Anda dalam pengambilan keputusan.</p><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
    </section>
    <!-- /.content-header -->
  
    <!-- Main content -->
    <!-- Main content Tabel Hasil Evaluasi -->
    <section class="content p-0 pb-5">
        <div class="container-fluid container-xl position-relative align-items-center justify-content-between">
            <div class="row">
                <div class="col-12">
                    <div class="card"  style="font-family: Nunito;">
                        <div class="card-header">
                            <h1 class="card-title text-center">Tabel Hasil Evaluasi Kesesuaian Lahan</h1>
                        </div>
                        <div class="card-body table-responsive">
                            {{-- <div class="row mb-2">
                                <div class="col-sm-12">
                                    <a href="{{ route('user.add-area') }}" class="btn btn-primary float-end">Tambah Data Evaluasi</a>
                                </div>
                            </div> --}}
                            <div id="dt-container-eval">
                                <table id="tableHasilEval" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%">
                                    <thead class="text-center align-middle">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Area</th>
                                            <th>Deskripsi</th>
                                            <th>Luas (m<sup>2</sup>)</th>
                                            <th>Temperatur</th>
                                            <th>Bulan Basah</th>
                                            <th>Drainase</th>
                                            <th>Tekstur</th>
                                            <th>Kedalaman Tanah</th>
                                            <th>Kejenuhan Basa</th>
                                            <th>Ktk</th>
                                            <th>pH</th>
                                            <th>c-Organik</th>
                                            <th>Erosi</th>
                                            <th>Lereng</th>
                                            <th>Banjir</th>
                                            <th>Hasil</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @foreach ($dataArea as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->nama_area }}</td>
                                                <td>{{ $d->deskripsi }}</td>
                                                <td>{{ number_format($d->luas, 2, ',', '.') }}</td>
                                                <td>{{ $d->temperatur }}</td>
                                                <td>{{ $d->bulan_basah }}</td>
                                                <td>{{ $d->drainase }}</td>
                                                <td>{{ $d->tekstur }}</td>
                                                <td>{{ $d->kedalaman_tanah }}</td>
                                                <td>{{ $d->kejenuhan_basa }}</td>
                                                <td>{{ $d->ktk }}</td>
                                                <td>{{ $d->ph }}</td>
                                                <td>{{ $d->c_organik }}</td>
                                                <td>{{ $d->erosi }}</td>
                                                <td>{{ $d->lereng }}</td>
                                                <td>{{ $d->banjir }}</td>
                                                <td>
                                                    @php
                                                        $recommendationList = json_decode($d->recommendation, true);
                                                    @endphp

                                                    @if($recommendationList && is_array($recommendationList))
                                                        <ul style="text-align: left;">
                                                            @foreach($recommendationList as $recommendation)
                                                                <li>{{ $recommendation }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        No recommendations available
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('user.edit_addarea', $d->id) }}" class="btn btn-success" id="edit" data-bs-toggle="tooltip" data-bs-placement="top" title="Sunting data">
                                                        <i class="fas fa-pen"></i> 
                                                    </a>
                                                    <form action="{{ route('data.delete', $d->id) }}" class="form-hapus-eval" method="POST" style="display:inline-block;" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus data">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="delete-eval btn btn-warning">
                                                            <i class="fas fa-trash" style="color: white"></i>
                                                        </button>
                                                    </form>   
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    new DataTable('#tableHasilEval', {
        layout: {
            topStart: {
                buttons: [
                {
                    text: 'Tambah Data',
                    action: function (e, dt, node, config) {
                        window.location.href = '{{ route('user.add-area') }}'; // Redirect to the add user route
                    },
                },

                {
                    text: 'GeoJSON',
                    action: function (e, dt, node, config) {
                        // Gunakan fetch untuk mengambil data GeoJSON dari endpoint
                        fetch('/unduh-geojson')
                            .then(response => response.blob())  // Ambil response sebagai Blob
                            .then(blob => {
                                // Buat URL Blob untuk file unduhan
                                const url = window.URL.createObjectURL(blob);
                                const a = document.createElement('a');
                                a.href = url;
                                a.download = 'data.geojson';  // Nama file unduhan
                                document.body.appendChild(a);
                                a.click();
                                document.body.removeChild(a);
                                window.URL.revokeObjectURL(url);  // Bersihkan URL Blob
                            })
                        .catch(error => console.error('Error fetching GeoJSON:', error));
                    }
                },
                
                
                'excel',
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    text: 'Atur Kolom' // Mengubah nama tombol menjadi "Atur Kolom"
                }
                ]            
            },
            bottomEnd: {
                paging: {
                    firstLast: false
                }
            },
            
        },
        // columnDefs: [
        //     {
        //         targets: -1,
        //         visible: false
        //     }
        // ],
        scrollX: true,
        fixedColumns: {
            start: 0,
            end: 2
        },
        search: {
            boundary: true
        },
        pageLength: 10, 
        language: {
            search: "Cari:", // Mengubah teks label pencarian
            searchPlaceholder: "Masukkan kata kunci..." // Opsional: Menambahkan placeholder
        }
    });
    $(document).ready(function() {
        $('.delete-eval').on('click', function(e) { // Use class selector for the button
            e.preventDefault();

            const form = $(this).closest('form'); // Get the closest form for the clicked button

            Swal.fire({
                title: "Apakah Anda yakin ingin menghapus data ini?",
                text: "Anda tidak akan dapat mengembalikannya!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#495E57",
                cancelButtonColor: "#ffc451",
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit the specific form
                    Swal.fire({
                        title: "Data berhasil dihapus!",
                        text: "Data Anda sudah berhasil dihapus.",
                        icon: "success"
                    });
                }
            });
        });
    });

</script>
@endsection
