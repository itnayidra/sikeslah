@extends('layout.mainapplogin')

@section('head')
    <style>
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
        },
        tr.child {
            text-align: justify;
        }

        tr.child td {
            text-align: justify; /* Menyesuaikan teks dalam <td> juga */
        }
    </style>
@endsection

@section('content')
    <!-- Main content Rekap All -->
    <section class="content pb-3">
        <div class="container-fluid container-xl position-relative align-items-center justify-content-between" style="max-width: 1200px">
            <div class="row justify-content-center mt-5" style="padding: 15px;">
                <!-- Card: Total Pengguna dan Data Evaluasi -->
                <div class="col-lg-4 col-12 mb-3">
                    <!-- Total Pengguna -->
                    <div class="small-box bg-white border rounded px-3 py-3 mb-3" style="height: 235px;">
                        <div class="inner">
                            <p style="font-size: 20px"><strong>Total Pengguna</strong></p>
                            <p style="font-size: 40px">{{ $userCount }}</p> <!-- Menampilkan jumlah pengguna -->
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>

                    <!-- Data Evaluasi -->
                    <div class="small-box bg-white border rounded px-3 py-3" style="height: 235px;">
                        <div class="inner">
                            <p style="font-size: 20px"><strong>Data Evaluasi Kesesuaian Lahan</strong></p>
                            <p style="font-size: 40px">{{ $dataCount }}</p> <!-- Menampilkan jumlah pengguna -->
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>

                <!-- Tabel Daftar Pengguna -->
                <div class="col-lg-8 col-12">
                    <div class="card"  style="font-family: Nunito;">
                        <div class="card-header">
                            <h1 class="card-title text-center">Daftar Pengguna</h1>
                        </div>
                        <div class="card-body table-responsive">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                {{-- <a href="{{ route('admin.create') }}" class="btn btn-warning me-2">Tambah Data Pengguna</a> --}}
                                <div id="example_filter" class="dataTables_filter">
                                    <!-- Kotak pencarian sudah dihasilkan oleh DataTables di sini -->
                                </div>
                            </div>
                            <div id="dt-container-pengguna">
                                <table id="tabelPengguna" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%">
                                    <thead class="text-center align-middle">
                                        <tr>
                                            <th>No</th>
                                            <th>Id Pengguna</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Peran</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @foreach ($data as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->id }}</td>
                                                <td>{{ $d->name }}</td>
                                                <td>{{ $d->email }}</td>
                                                <td>{{ $d->role }}</td>
                                                <td>
                                                    {{-- Edit --}}
                                                    <a href="{{ route('admin.user.edit', $d->id) }}" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Sunting data">
                                                        <i class="fas fa-pen"></i> 
                                                    </a>
                                                    {{-- Delete --}}
                                                    <form action="{{ route('admin.user.delete', $d->id) }}" class="formHapusUser" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="deleteuser-btn btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus data">
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
    <!-- End Main content Rekap All -->

    <!-- Main content Tabel Hasil Evaluasi -->
    <section class="content p-0 pb-4">
        <div class="container-fluid container-xl position-relative align-items-center justify-content-between" style="max-width: 1200px">
            <div class="row">
                <div class="col-12">
                    <div class="card"  style="font-family: Nunito;">
                        <div class="card-header">
                            <h1 class="card-title text-center">Tabel Hasil Evaluasi Kesesuaian Lahan</h1>
                        </div>
                        <div class="card-body table-responsive">
                            {{-- <div class="row mb-2">
                                <div class="col-sm-12">
                                    <a href="{{ route('admin.add-area') }}" class="btn btn-primary float-end">Tambah Data Evaluasi</a>
                                </div>
                            </div> --}}
                            <div id="dt-container-eval">
                                <table id="tableHasilEval" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%">
                                    <thead class="text-center align-middle">
                                        <tr>
                                            <th>No</th>
                                            <th>Id Pengguna</th>
                                            <th>Nama Area</th>
                                            <th>Deskripsi</th>
                                            <th>Luas m<sup>2</sup></th>
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
                                                <td>{{ $d->user_id }}</td>
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
                                                    <a href="{{ route('admin.edit_addarea', $d->id) }}" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Sunting data">
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
    <!-- End Main content Tabel Hasil Evaluasi -->
@endsection


@section('scripts')
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    new DataTable('#tabelPengguna', {
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 10001, targets: 4 },
            { responsivePriority: 2, targets: -2 }
        ],
        layout: {
            bottomEnd: {
                paging: {
                    firstLast: false
                }
            },
            topStart: {
                buttons: [
                {
                    text: 'Tambah Data Pengguna',
                    action: function (e, dt, node, config) {
                        window.location.href = '{{ route('admin.create') }}'; // Redirect to the add user route
                    },
                    className: 'btn btn-warning' // You can add custom classes for styling
                }
                ],
            },
        },
        search: {
            boundary: true
        },
        pageLength: 5, 
        lengthChange: false,
        language: {
            search: "Cari:", // Mengubah teks label pencarian
            searchPlaceholder: "Masukkan kata kunci..." // Opsional: Menambahkan placeholder
        },
        
    });
    // Konfirmasi penghapusan untuk deleteuser
    $(document).ready(function() {
        $('.deleteuser-btn').on('click', function(e) { // Use class selector for the delete button
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
         
    $(document).ready(function() {
        $('#tableHasilEval').DataTable({
            scrollX: true ,// Mengaktifkan scroll horizontal
            fixedColumns: {
                start: 0,
                end: 2
            },
            layout: {
                topStart: {
                    buttons: [
                        {
                            text: 'Tambah Data',
                            action: function (e, dt, node, config) {
                                window.location.href = '{{ route('admin.add-area') }}'; // Redirect to the add user route
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
                        'excel', 'print',
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
            search: {
                boundary: true
            },
            pageLength: 10, 
            language: {
                search: "Cari:", // Mengubah teks label pencarian
                searchPlaceholder: "Masukkan kata kunci..." // Opsional: Menambahkan placeholder
            }
            
        });
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