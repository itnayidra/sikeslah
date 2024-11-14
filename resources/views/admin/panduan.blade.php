@extends('layout.mainapplogin')

@section('head')
<style>
    .sticky-card {
        position: sticky; /* Tetap sticky untuk ukuran layar besar */
        top: 120px;
        height: calc(100vh - 300px);
        overflow-y: auto;
    }
    /* Menetapkan warna default untuk link */
    a.active-link {
        color: white;
        text-decoration: none;
    }

    /* Menambahkan gaya saat link diklik */
    a.active-link:active, a.active-link:focus {
        color: #FFD700; /* Warna saat diklik */
    }

    /* Opsional: Menambahkan efek transisi */
    a.active-link {
        transition: color 0.3s ease;
    }

    @media (max-width: 768px) {
        .sticky-card {
            position: static; /* Ubah menjadi static di ponsel */
            height: auto; /* Biarkan tinggi otomatis */
            margin-top: 20px; /* Tambahkan margin untuk menghindari tumpang tindih */
        }
    }
    
</style>
@endsection

@section('content')
    <section class="content pb-3">
        <div class="container-fluid container-xl position-relative align-items-center justify-content-between">
            <div class="row justify-content-center mt-5" style="padding: 15px;">
                <!-- Card: Total Pengguna dan Data Evaluasi -->
                <div class="col-lg-4 col-12 mb-3 sticky-card" id="stickyCard">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 style="color:#ffc451;"><strong>Petunjuk Penggunaan SiKeslah</strong></span>!</h1>
                        </div>
                        <p style="color:white; margin-top: 15px">
                            <strong>Panduan</strong>
                            <br>
                            <ul style="list-style:none; line-height: 2;">
                                <li>
                                    <i class="fa-brands fa-readme" style="color:white"></i>
                                    <a href="#kenalsikeslah"  class="active-link"><span class="ms-2">Apa itu SiKeslah?</span></a>        
                                </li>
                                <li>
                                    <i class="fa-brands fa-readme" style="color:white"></i>
                                    <a href="#panduanlogin"  class="active-link"><span class="ms-2">Login/Masuk</span></a>        
                                </li>
                                <li>
                                    <i class="fa-brands fa-readme" style="color:white"></i>
                                    <a href="#panduandashboard"  class="active-link"><span class="ms-2">Akses Dashboard</span></a>        
                                </li>
                                <li>
                                    <i class="fa-brands fa-readme" style="color:white"></i>
                                    <a href="#panduanpetalahansawah"  class="active-link"><span class="ms-2">Peta Lahan Sawah</span></a>        
                                </li>
                                <li>
                                    <i class="fa-brands fa-readme" style="color:white"></i>
                                    <a href="#panduanceklahan"  class="active-link"><span class="ms-2">Cek Kesesuaian Lahan</span></a>        
                                </li>
                                <li>
                                    <i class="fa-brands fa-readme" style="color:white"></i>
                                    <a href="#panduannilailahan"  class="active-link"><span class="ms-2">Nilai Kesesuaian Lahan</span></a>        
                                </li>
                                
                            </ul>
                            <br>
                        </p>
                    </div><!-- /.row -->
                </div>
                
                <div class="col-lg-8 col-12">
                    <section id="kenalsikeslah" class="kenalsikeslah" style="padding-bottom:0px; padding-top:20px">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-center">Apa itu SiKeslah?</h3>
                            </div>
                            <div class="card-body" style="font-family: Nunito;">
                                <div class="row">
                                    <div class="justify-content-between align-items-center">
                                        <p style="text-align: justify; text-indent:50px;">
                                            <strong>SiKeslah</strong> adalah aplikasi WebGIS yang dirancang untuk membantu petani, perencana wilayah, dan masyarakat umum dalam mengetahui dan 
                                            mengevaluasi kesesuaian lahan untuk pertanian di Kabupaten Sleman.
                                            Dengan SiKeslah, Anda bisa mengevaluasi kesesuaian lahan pertanian secara mendalam berdasarkan berbagai parameter seperti iklim, topografi, dan 
                                            kualitas tanah. WebGIS ini adalah mitra andal Anda untuk memastikan kesesuaian lahan pertanian di Kabupaten Sleman!
                                        </p>   
                                        <p style="text-align: justify; text-indent:50px;">
                                            SiKeslah dirancang dan dibangun dengan tujuan untuk menyajikan informasi yang akurat 
                                            mengenai kesesuaian lahan di Kabupaten Sleman, khususnya untuk lahan pertanian sawah yang cocok bagi tanaman padi, jagung, dan kedelai. Dengan hadirnya 
                                            SiKeslah, diharapkan dapat memberikan manfaat yang signifikan baik bagi masyarakat umum maupun pemerintah Kabupaten Sleman karena mempermudah 
                                            akses perolehan informasi terkait kesesuaian lahan pertanian. Sistem ini memungkinkan pengguna untuk memperoleh data yang lebih tepat dan efisien mengenai 
                                            kesesuaian lahan untuk berbagai jenis tanaman, yang pada gilirannya dapat mendukung perencanaan dan pengelolaan pertanian yang lebih baik, serta meningkatkan 
                                            produktivitas pertanian di Kabupaten Sleman.                                       
                                        </p>        
                                        <p style="text-align: justify; text-indent:50px;">
                                            WebGIS ini menyajikan informasi kesesuaian lahan untuk tanaman padi sawah, jagung, dan kedelai di Kabupaten Sleman yang bersumber dari Dinas Pertanahan & 
                                            Tata Ruang tahun 2019. Selain itu, webGIS ini memiliki sistem penilaian kesesuaian lahan yang menghasilkan empat kelas kesesuaian lahan sesuai pada petunjuk teknis pedoman 
                                            penilaian kesesuaian lahan untuk komoditas pertanian strategis tingkat semi-detail skala 1:50.000 dari Balai Besar Penelitian dan Pengembangan Sumberdaya Lahan Pertanian serta SNI 8474:2018. 
                                            Variabel yang digunakan dalam penilaian kesesuaian lahan ini mencakup kondisi temperatur, ketersediaan air, kondisi perakaran, retensi hara, bahaya erosi, dan bahaya banjir.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="panduanlogin" class="panduanlogin" style="padding-bottom:0px; padding-top:40px">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-center">Panduan Login/Masuk & Daftar</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p style="text-align: justify">
                                            Yuk, ikuti langkah berikut ini untuk login/masuk serta daftar akun di SiKeslah! Dengan masuk ke akun Anda, Anda dapat
                                            mengakses seluruh fitur dalam SiKeslah.
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div>
                                        <ol>
                                            <li>
                                                <p style="text-align: justify">Masuk ke <a href="{{ route('login') }}" style="color:blue"> Login</a> atau pilih tombol
                                                    <a class="btn btn-outline-warning" role="button"><b style="color:black">Masuk</b></a> di pojok kanan atas untuk membuka halaman Login.
                                                </p>                                            
                                            </li>
                                            <li>
                                                <p style="text-align: justify">
                                                    Jika Anda <b>sudah memiliki akun</b>, silakan isi email dan kata sandi yang sudah Anda daftarkan. 
                                                    Selanjutnya, klik tombol 
                                                    <button class="btn btn-primary" type="submit">Masuk</button>.
                                                </p>
                                            </li>
                                            {{-- <li>
                                                <p style="text-align: justify">
                                                    Lupa kata sandi? Klik link <a href="{{ ('lupa-kata-sandi') }}" style="color:blue"> Lupa kata sandi?</a> di bawah formulir login.
                                                    Masukkan email Anda dan ikuti petunjuk yang dikirimkan ke email untuk mengatur ulang kata sandi Anda.
                                                </p>
                                            </li> --}}
                                            <li>
                                                <p style="text-align: justify">
                                                    Jika Anda <b>belum memiliki akun</b>, silakan daftar terlebih dahulu. Berikut ini panduan untuk daftar akun:
                                                    <p style="text-align: justify">
                                                        <ul>
                                                            <li>
                                                                <p style="text-align: justify">Masuk ke <a href="{{ route('register') }}" style="color:blue"> Daftar</a></p>
                                                            </li>
                                                            <li>
                                                                <p style="text-align: justify">Lengkapi form nama pengguna, email, dan kata sandi.</p> 
                                                            </li>
                                                            <li>
                                                                <p style="text-align: justify">Klik tombol <button class="btn btn-primary" type="submit">Daftar</button>.</p>
                                                            </li>
                                                        </ul>
                                                    </p>
                                                </p>
                                            </li>
                                        </ol>
                                        <p style="text-align: justify">
                                            <b>Tips Keamanan:</b>
                                            <br>
                                            <ul>
                                                <li>
                                                    <p style="text-align: justify">Gunakan kata sandi yang kuat dan unik untuk akun Anda.</p>
                                                </li>
                                                <li>
                                                    <p style="text-align: justify">Jangan berbagi informasi login Anda dengan orang lain.</p>
                                                </li>
                                                <li>
                                                    <p style="text-align: justify">Jika menggunakan komputer umum, pastikan untuk logout setelah selesai.</p>
                                                </li>
                                            </ul>
                                        </p>
    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="panduandashboard" class="panduandashboard" style="padding-bottom:0px; padding-top:40px">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-center">Akses Dashboard</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p style="text-align: justify;text-indent:50px;">
                                            <b>Dashboard</b> SiKeslah memuat <b>informasi hasil penilaian kesesuaian lahan dalam bentuk tabel</b>. 
                                            Anda dapat menambah data penilaian, unduh data dalam format geojson, pdf, serta excel. Sesuaikan kolom yang akan ditampilkan pada tabel.
                                            Jika Anda ingin mengubah data, silakan pilih tombol
                                            <button type="button" class="btn btn-success">
                                                <i class="fas fa-pen"  style="color: white"></i> 
                                            </button>, sedangkan jika Anda ingin hapus data, klik tombol 
                                            <button type="button" class="btn btn-warning">
                                                <i class="fas fa-trash"  style="color: white"></i> 
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="panduanpetalahansawah" class="panduanpetalahansawah" style="padding-top:40px; padding-bottom:0">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-center">Eksplor Peta Lahan Sawah</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p style="text-align: justify">
                                            Yuk, ikuti panduan langkah demi langkah ini untuk mengksplor dan 
                                            memahami peta lahan sawah di SiKeslah! Dengan panduan ini, Anda akan semakin mudah 
                                            menemukan informasi penting seputar lahan sawah untuk kebutuhan Anda.
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div>
                                        <ol>
                                            <li>
                                                <p style="text-align: justify">Masuk ke <a href="{{ route('admin.lbsmap') }}" style="color:blue"> Peta Lahan Sawah</a></p>
                                            </li>
                                            <li>
                                                <p style="text-align: justify">
                                                    Di halaman Peta Lahan Sawah terdapat beberapa fitur, meliputi:
                                                    <ul>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Pusatkan peta</strong></span><i class="fa-solid fa-house"></i>
                                                                <br>
                                                                Fungsi : Memusatkan dan mengembalikan ke tampilan utama peta.
                                                                <br>
                                                                Cara Menggunakan : Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-house"></i></button> untuk kembali ke posisi awal peta.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Perbesar peta</strong></span><i class="fa-solid fa-plus"></i>
                                                                <br>
                                                                Fungsi: Memperbesar tampilan peta untuk melihat detail lebih lanjut.                                                               
                                                                <br>
                                                                Cara Menggunakan: Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-plus"></i></button> untuk zoom in pada area yang diinginkan.                                                            
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Perkecil peta</strong></span><i class="fa-solid fa-minus"></i>
                                                                <br>
                                                                Fungsi: Memperkecil tampilan peta untuk melihat area yang lebih luas.                                                                
                                                                <br>
                                                                Cara Menggunakan: Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-minus"></i></button> untuk zoom out dan mendapatkan gambaran umum.                                                            
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Kursor</strong></span><i class="fa-solid fa-arrow-pointer"></i>
                                                                <br>
                                                                Fungsi : Mengatur ulang tombol yang telah digunakan.
                                                                <br>
                                                                Cara Menggunakan: Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-arrow-pointer"></i></button> untuk mengatur ulang pengaturan tampilan peta.                                                            
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Hitung area</strong></span><i class="fa-solid fa-ruler-combined"></i>
                                                                <br>
                                                                Fungsi : Menghitung luas area yang digambar di peta.
                                                                <br>
                                                                Cara Menggunakan: Gambar batas area yang diinginkan, lalu klik <button type="button" class="btn btn-white"><i class="fa-solid fa-ruler-combined"></i></button> untuk melihat hasil luasnya.                                                            
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Info</strong></span><i class="fa-solid fa-info"></i>
                                                                <br>
                                                                Fungsi : Menampilkan informasi detail lahan sawah dengan cara mengklik peta.
                                                                <br>
                                                                Cara Menggunakan: Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-info"></i></button>, kemudian klik pada area peta untuk mendapatkan informasi tentang lahan tersebut.                                                            
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Diagram</strong></span><i class="fa-solid fa-chart-pie"></i>
                                                                <br>
                                                                Fungsi : Menampilkan informasi dalam bentuk diagram/grafik luas lahan sawah.
                                                                <br>
                                                                Cara Menggunakan: Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-chart-pie"></i></button> untuk melihat grafik yang menunjukkan data luas lahan.                                                            
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Unduh data</strong></span><i class="fa-solid fa-download"></i>
                                                                <br>
                                                                Fungsi : Mengunduh data geospasial yang ada di peta dalam format GeoJSON..
                                                                <br>
                                                                Cara Menggunakan: Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-download"></i></button> untuk mengunduh data yang ditampilkan pada layer peta.                                                            
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Ubah layer</strong></span><i class="fa-solid fa-layer-group"></i>
                                                                <br>
                                                                Fungsi : Mengubah layer peta yang ditampilkan.
                                                                <br>
                                                                Cara Menggunakan : Klik tombol <button type="button" class="btn btn-white" 
                                                                style="background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAACE1BMVEX///8A//8AgICA//8AVVVAQID///8rVVVJtttgv98nTmJ2xNgkW1ttyNsmWWZmzNZYxM4gWGgeU2JmzNNr0N1Rwc0eU2VXxdEhV2JqytQeVmMhVmNoydUfVGUgVGQfVGQfVmVqy9hqy9dWw9AfVWRpydVry9YhVmMgVGNUw9BrytchVWRexdGw294gVWQgVmUhVWPd4N6HoaZsy9cfVmQgVGRrytZsy9cgVWQgVWMgVWRsy9YfVWNsy9YgVWVty9YgVWVry9UgVWRsy9Zsy9UfVWRsy9YgVWVty9YgVWRty9Vsy9aM09sgVWRTws/AzM0gVWRtzNYgVWRuy9Zsy9cgVWRGcHxty9bb5ORbxdEgVWRty9bn6OZTws9mydRfxtLX3Nva5eRix9NFcXxOd4JPeINQeIMiVmVUws9Vws9Vw9BXw9BYxNBaxNBbxNBcxdJexdElWWgmWmhjyNRlx9IqXGtoipNpytVqytVryNNrytZsjZUuX210k5t1y9R2zNR3y9V4lp57zth9zdaAnKOGoaeK0NiNpquV09mesrag1tuitbmj1tuj19uktrqr2d2svcCu2d2xwMO63N+7x8nA3uDC3uDFz9DK4eHL4eLN4eIyYnDX5OM5Z3Tb397e4uDf4uHf5uXi5ePi5+Xj5+Xk5+Xm5+Xm6OY6aHXQ19fT4+NfhI1Ww89gx9Nhx9Nsy9ZWw9Dpj2abAAAAWnRSTlMAAQICAwQEBgcIDQ0ODhQZGiAiIyYpKywvNTs+QklPUlNUWWJjaGt0dnd+hIWFh4mNjZCSm6CpsbW2t7nDzNDT1dje5efr7PHy9PT29/j4+Pn5+vr8/f39/f6DPtKwAAABTklEQVR4Xr3QVWPbMBSAUTVFZmZmhhSXMjNvkhwqMzMzMzPDeD+xASvObKePPa+ffHVl8PlsnE0+qPpBuQjVJjno6pZpSKXYl7/bZyFaQxhf98hHDKEppwdWIW1frFnrxSOWHFfWesSEWC6R/P4zOFrix3TzDFLlXRTR8c0fEEJ1/itpo7SVO9Jdr1DVxZ0USyjZsEY5vZfiiAC0UoTGOrm9PZLuRl8X+Dq1HQtoFbJZbv61i+Poblh/97TC7n0neCcK0ETNUrz1/xPHf+DNAW9Ac6t8O8WH3Vp98f5lCaYKAOFZMLyHL4Y0fe319idMNgMMp+zWVSybUed/+/h7I4wRAG1W6XDy4XmjR9HnzvDRZXUAYDFOhC1S/Hh+fIXxen+eO+AKqbs+wAo30zDTDvDxKoJN88sjUzDFAvBzEUGFsnADoIvAJzoh2BZ8sner+Ke/vwECuQAAAABJRU5ErkJggg==');
                                                                     background-repeat: no-repeat; width:30px; height:30px;">
                                                                </button>
                                                                , pilih layer yang diinginkan dari daftar untuk mengganti tampilan peta sesuai kebutuhan.
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </p>
                                            </li>
                                        </ol>
    
                                    </div>
                                </div>

                                {{-- <div class="d-flex justify-content-between align-items-center mb-3">
                                    <p style="text-align: justify">Tonton cara mengakses Peta Lahan Sawah</p>
                                </div> --}}
                            </div>
                        </div>
                    </section>
                
                    <section id="panduanceklahan" class="panduanceklahan" style="padding-top:40px; padding-bottom:0">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-center">Cara Cek Kesesuaian Lahan</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p style="text-align: justify">
                                            Yuk, ikuti panduan ini untuk memeriksa kesesuaian lahan 
                                            berdasarkan parameter spesifik untuk tanaman padi, jagung, dan kedelai! Dengan panduan ini, 
                                            Anda akan semakin mudah menentukan lokasi yang tepat untuk tanaman yang Anda pilih 
                                            berdasarkan kondisi lahan yang ada.                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div>
                                        <ol>
                                            <li>
                                                <p style="text-align: justify">Masuk ke <a href="{{ route('admin.keslahmap') }}" style="color:blue"> Cek Kesesuaian Lahan</a></p>
                                            </li>
                                            <li>
                                                <p style="text-align: justify">
                                                    Di halaman Cek Kesesuaian Lahan terdapat beberapa fitur, meliputi:
                                                    <ul>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Pusatkan peta</strong></span><i class="fa-solid fa-house"></i>
                                                                <br>
                                                                Fungsi : Memusatkan dan mengembalikan ke tampilan utama peta.
                                                                <br>
                                                                Cara Menggunakan : Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-house"></i></button> untuk kembali ke posisi awal peta.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Perbesar peta</strong></span><i class="fa-solid fa-plus"></i>
                                                                <br>
                                                                Fungsi: Memperbesar tampilan peta untuk melihat detail lebih lanjut.                                                               
                                                                <br>
                                                                Cara Menggunakan: Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-plus"></i></button> untuk zoom in pada area yang diinginkan.                                                            
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Perkecil peta</strong></span><i class="fa-solid fa-minus"></i>
                                                                <br>
                                                                Fungsi: Memperkecil tampilan peta untuk melihat area yang lebih luas.                                                                
                                                                <br>
                                                                Cara Menggunakan: Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-minus"></i></button> untuk zoom out dan mendapatkan gambaran umum.                                                            
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Kursor</strong></span><i class="fa-solid fa-arrow-pointer"></i>
                                                                <br>
                                                                Fungsi : Mengatur ulang tombol yang telah digunakan.
                                                                <br>
                                                                Cara Menggunakan: Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-arrow-pointer"></i></button> untuk mengatur ulang pengaturan tampilan peta.                                                            
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Hitung area</strong></span><i class="fa-solid fa-ruler-combined"></i>
                                                                <br>
                                                                Fungsi : Menghitung luas area yang digambar di peta.
                                                                <br>
                                                                Cara Menggunakan: Gambar batas area yang diinginkan, lalu klik <button type="button" class="btn btn-white"><i class="fa-solid fa-ruler-combined"></i></button> untuk melihat hasil luasnya.                                                            
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Info</strong></span><i class="fa-solid fa-info"></i>
                                                                <br>
                                                                Fungsi : Menampilkan informasi detail lahan sawah dengan cara mengklik peta.
                                                                <br>
                                                                Cara Menggunakan: Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-info"></i></button>, kemudian klik pada area peta untuk mendapatkan informasi tentang lahan tersebut.                                                                                                                        
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Diagram</strong></span><i class="fa-solid fa-chart-pie"></i>
                                                                <br>
                                                                Fungsi : Menampilkan informasi dalam bentuk diagram/grafik kesesuaian lahan sawah untuk tanaman padi, jagung, dan kedelai.
                                                                <br>
                                                                Cara Menggunakan: Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-chart-pie"></i></button> untuk melihat grafik yang menunjukkan data luas lahan sawah yang sesuai untuk jenis tanaman padi, jagung, dan kedelai.                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Unduh data</strong></span><i class="fa-solid fa-download"></i>
                                                                <br>
                                                                Fungsi : Mengunduh data geospasial yang ada di peta dalam format GeoJSON..
                                                                <br>
                                                                Cara Menggunakan: Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-download"></i></button> untuk mengunduh data yang ditampilkan pada layer peta.                                                            
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Ubah layer</strong></span><i class="fa-solid fa-layer-group"></i>
                                                                <br>
                                                                Fungsi : Mengubah layer peta yang ditampilkan.
                                                                <br>
                                                                Cara Menggunakan : Klik tombol <button type="button" class="btn btn-white" 
                                                                style="background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAACE1BMVEX///8A//8AgICA//8AVVVAQID///8rVVVJtttgv98nTmJ2xNgkW1ttyNsmWWZmzNZYxM4gWGgeU2JmzNNr0N1Rwc0eU2VXxdEhV2JqytQeVmMhVmNoydUfVGUgVGQfVGQfVmVqy9hqy9dWw9AfVWRpydVry9YhVmMgVGNUw9BrytchVWRexdGw294gVWQgVmUhVWPd4N6HoaZsy9cfVmQgVGRrytZsy9cgVWQgVWMgVWRsy9YfVWNsy9YgVWVty9YgVWVry9UgVWRsy9Zsy9UfVWRsy9YgVWVty9YgVWRty9Vsy9aM09sgVWRTws/AzM0gVWRtzNYgVWRuy9Zsy9cgVWRGcHxty9bb5ORbxdEgVWRty9bn6OZTws9mydRfxtLX3Nva5eRix9NFcXxOd4JPeINQeIMiVmVUws9Vws9Vw9BXw9BYxNBaxNBbxNBcxdJexdElWWgmWmhjyNRlx9IqXGtoipNpytVqytVryNNrytZsjZUuX210k5t1y9R2zNR3y9V4lp57zth9zdaAnKOGoaeK0NiNpquV09mesrag1tuitbmj1tuj19uktrqr2d2svcCu2d2xwMO63N+7x8nA3uDC3uDFz9DK4eHL4eLN4eIyYnDX5OM5Z3Tb397e4uDf4uHf5uXi5ePi5+Xj5+Xk5+Xm5+Xm6OY6aHXQ19fT4+NfhI1Ww89gx9Nhx9Nsy9ZWw9Dpj2abAAAAWnRSTlMAAQICAwQEBgcIDQ0ODhQZGiAiIyYpKywvNTs+QklPUlNUWWJjaGt0dnd+hIWFh4mNjZCSm6CpsbW2t7nDzNDT1dje5efr7PHy9PT29/j4+Pn5+vr8/f39/f6DPtKwAAABTklEQVR4Xr3QVWPbMBSAUTVFZmZmhhSXMjNvkhwqMzMzMzPDeD+xASvObKePPa+ffHVl8PlsnE0+qPpBuQjVJjno6pZpSKXYl7/bZyFaQxhf98hHDKEppwdWIW1frFnrxSOWHFfWesSEWC6R/P4zOFrix3TzDFLlXRTR8c0fEEJ1/itpo7SVO9Jdr1DVxZ0USyjZsEY5vZfiiAC0UoTGOrm9PZLuRl8X+Dq1HQtoFbJZbv61i+Poblh/97TC7n0neCcK0ETNUrz1/xPHf+DNAW9Ac6t8O8WH3Vp98f5lCaYKAOFZMLyHL4Y0fe319idMNgMMp+zWVSybUed/+/h7I4wRAG1W6XDy4XmjR9HnzvDRZXUAYDFOhC1S/Hh+fIXxen+eO+AKqbs+wAo30zDTDvDxKoJN88sjUzDFAvBzEUGFsnADoIvAJzoh2BZ8sner+Ke/vwECuQAAAABJRU5ErkJggg==');
                                                                     background-repeat: no-repeat; width:30px; height:30px;">
                                                                </button>
                                                                , pilih layer yang diinginkan dari daftar untuk mengganti tampilan peta sesuai kebutuhan.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Cek lokasi terkini</strong></span><i class="fa-solid fa-location-dot fa-xs"></i>
                                                                <br>
                                                                Fungsi : Menampilkan lokasi terkini berdasarkan geolocation atau dengan memasukkan koordinat latitude dan longitude untuk mencari kesesuaian lahan sesuai lokasi terkini.
                                                                <br>
                                                                Cara Menggunakan : 
                                                                <br>
                                                                <ol>
                                                                    <li>
                                                                        <p style="text-align: justify">Aktifkan geolocation pada perangkat Anda.</p>
                                                                    </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Klik tombol <button type="button" class="btn btn-white"><i class="fa-solid fa-location-dot fa-xs"></i></button> untuk mendapatkan titik lokasi pengguna secara otomatis.</p>
                                                                    </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Jika Anda ingin memasukkan lokasi secara manual, isikan koordinat latitude dan longitude pada kolom yang tersedia.</p>
                                                                    </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Klik tombol <b>Cari</b> untuk mengetahui informasi kesesuaian lahan bagi tanaman padi, jagung, dan kedelai sesuai lokasi yang Anda cari.</p>                                                                    
                                                                    </li>
                                                                </ol>
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><span class="me-2"><strong>Cek kesesuaian lahan</strong></span><i class="fa-solid fa-magnifying-glass-location"></i> 
                                                                <br>
                                                                Fungsi : Menampilkan informasi kesesuaian lahan sawah berdasarkan jenis tanaman, lokasi area, dan parameter iklim, tanah, dan topografi.
                                                                <br>
                                                                Cara Menggunakan : Pilih layer yang diinginkan dari daftar untuk mengganti tampilan peta sesuai kebutuhan.
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </p>
                                            </li>
                                            <li>
                                                <p style="text-align: justify">Cara memeriksa kesesuaian lahan sawah berdasarkan jenis tanaman, lokasi, dan parameter untuk tanaman padi, jagung, dan kedelai.
                                                    <ol>
                                                        <li>
                                                            <p style="text-align: justify"><strong>Berdasarkan Jenis Tanaman</strong>
                                                                <ol>
                                                                    <li>
                                                                        <p style="text-align: justify">Pilih tombol <span class="me-2"><strong>Cek Kesesuaian Lahan</strong></span><i class="fa-solid fa-magnifying-glass-location"></i>                                                                     </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Pilih jenis tanaman yang ingin dievaluasi.</p>
                                                                    </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Masukkan parameter yang relevan dan nilai yang diinginkan.</p>
                                                                    </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Klik tombol <b>Cari</b> untuk mendapatkan informasi kesesuaian lahan.</p>
                                                                    </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Hasil pencarian akan ditampilkan pada peta dan ditambahkan ke legenda sebagai keterangan.</p>
                                                                    </li>
                                                                </ol>
                                                                <p style="text-align: justify">
                                                                    <b>Catatan:</b>
                                                                    <br>
                                                                    Jika Anda ingin melihat informasi tentang setiap lahan yang muncul dari pencarian kesesuaian lahan, cukup klik area yang ditampilkan pada peta. Dengan begitu, informasi terkait lahan yang Anda klik akan muncul dalam bentuk popup. 
                                                                </p>
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><strong>Berdasarkan Area yang Dipilih</strong>
                                                                <ol>
                                                                    <li>
                                                                        <p style="text-align: justify">Pilih tombol <span class="me-2"><strong>Cek Kesesuaian Lahan</strong></span><i class="fa-solid fa-magnifying-glass-location"></i>                                                                     
                                                                    </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Pilih tab <b>Pilih Area</b></p>
                                                                    </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Pilih kapanewon yang ingin dicari.</p>
                                                                    </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Klik tombol <b>Pilih Area</b> untuk menggambar area yang diinginkan.</p>
                                                                    </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Hasil pencarian akan ditampilkan pada popup.</p>
                                                                    </li>
                                                                </ol>
                                                            </p>                                                        
                                                        </li>
                                                        <li>
                                                            <p style="text-align: justify"><strong>Berdasarkan Parameter</strong>
                                                                <ol>
                                                                    <li>
                                                                        <p style="text-align: justify">Pilih tombol <span class="me-2"><strong>Cek Kesesuaian Lahan</strong></span><i class="fa-solid fa-magnifying-glass-location"></i>                                                                     
                                                                    </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Pilih tab <b>Parameter</b></p>
                                                                    </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Masukkan salah satu parameter yang ingin Anda gunakan</p>
                                                                    </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Klik tombol <b>Cari</b> untuk menampilkan lokasi lahan dan titik penanda yang menunjukkan kesesuaian lahan untuk padi, jagung, atau kedelai.</p>
                                                                    </li>
                                                                    <li>
                                                                        <p style="text-align: justify">Hasil pencarian akan tersedia di legenda.</p>
                                                                    </li>
                                                                </ol>
                                                                <p style="text-align: justify">
                                                                    <b>Catatan:</b>
                                                                    <br>
                                                                    Jika Anda ingin melihat informasi tentang setiap lahan yang muncul dari pencarian kesesuaian lahan, cukup klik marker yang ditampilkan pada peta. Dengan begitu, informasi terkait lahan yang Anda klik akan muncul dalam bentuk popup. 
                                                                </p>
                                                            </p>      
                                                        </li>
                                                    </ol>
                                                </p>
                                                
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                    </section>

                    <section id="panduannilailahan" class="panduannilailahan" style="padding-top:40px;">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-center">Nilai Kesesuaian Lahan</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p style="text-align: justify">
                                            Yuk, ikuti panduan ini untuk menentukan potensi lahan dalam mendukung jenis tanaman padi, jagung, dan kedelai. Dengan panduan ini, Anda akan semakin mudah 
                                            dalam penilaian kesesuaian lahan sawah. <br>
                                            <b>Pastikan Anda sudah memiliki keseluruhan nilai data parameter!</b>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div>
                                        <ol>
                                            <li>
                                                <p style="text-align: justify">Masuk ke <a href="{{ route('login') }}" style="color:blue"> Login</a> atau pilih tombol
                                                    <a class="btn btn-outline-warning" role="button"><b style="color:black">Masuk</b></a>
                                                    
                                                    . Silakan login/masuk terlebih dahulu!
                                                </p>
                                            </li>
                                            <li>
                                                <p style="text-align: justify">Pilih layanan <b>Nilai Kesesuaian Lahan</b>.</p>
                                            </li>
                                            <li>
                                                <p style="text-align: justify">Gambar area pada peta dan lengkapi formulir. <b>Setiap kolom pada formulir WAJIB diisi.</b>
                                                <br>
                                                Berikut ini formulir pengisiannya:
                                                <br>
                                                <img src="{{ asset('/img/form-nilai.png') }}" alt="">
                                                </p>
                                            </li>
                                            <li>
                                                <p style="text-align: justify">Klik tombol <button class="btn btn-primary" type="submit">Evaluasi</button>.</p>
                                            </li>
                                            <li>
                                                <p style="text-align: justify">Lihat hasil kesesuaian di halaman Dashboard.</p>
                                            </li>
                                        </ol>
                                        <p style="text-align: justify"><b>Catatan:</b>
                                            Pastikan Anda mengisi semua kolom di form agar proses evaluasi berjalan dengan lancar.
                                        </p>
                                    </div>
                                </div>
    
                                {{-- <div class="d-flex justify-content-between align-items-center mb-3">
                                    <p>Tonton cara mengakses Peta Lahan Sawah</p>
                                </div> --}}
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#to-nilai-keslah').on('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Anda harus Masuk!',
                text: 'Silakan Masuk terlebih dahulu untuk mengakses halaman ini.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        });
    });
</script>
@endsection