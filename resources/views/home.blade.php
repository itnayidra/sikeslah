@extends('layout.mainapp')

@section('head')
<style>
    .video-container {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* Rasio aspek 16:9 */
    height: 0;
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        padding: 20px;
    }

    .info-card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        text-align: center;
        transition: transform 0.2s;
    }

    .info-card:hover {
        transform: translateY(-5px);
    }

    .icon {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .learn-more {
        display: block;
        margin-top: 10px;
        color: #007bff;
    }
    @media (max-width:1200px){
        .info-grid {
            display: flex;
            flex-direction: column; /* Menyusun kartu dalam kolom untuk tampilan mobile */
            gap: 15px; /* Jarak antar kartu */
            }

        .info-card {
            background: #f9f9f9; /* Latar belakang kartu */
            border-radius: 8px; /* Sudut kartu melengkung */
            padding: 15px; /* Ruang di dalam kartu */
            text-align: center; /* Teks di tengah */
        }

        .icon {
            font-size: 2em; /* Ukuran ikon yang lebih besar */
            margin-bottom: 10px; /* Jarak antara ikon dan judul */
        }

        .learn-more {
            display: inline-block;
            margin-top: 10px;
            color: #007bff; /* Warna tautan */
        }

        .cta {
            text-align: center;
            margin-top: 20px; /* Jarak atas untuk tombol */
        }

        .btn-primary {
            padding: 10px 20px; /* Ruang dalam tombol */
            font-size: 1em; /* Ukuran font tombol */
        }
        .accordion {
            margin-bottom: 20px; /* Jarak bawah untuk accordion */
        }

        .video-container {
            max-width: 100%; /* Agar video tidak melampaui lebar layar */
            overflow: hidden; /* Untuk menyembunyikan bagian video yang keluar */
        }

        .video-container video {
            width: 100%; /* Agar video memenuhi lebar kontainer */
            height: auto; /* Memastikan rasio aspek video terjaga */
        }

        .accordion-button {
            background-color: #007bff; /* Warna latar belakang tombol accordion */
            color: white; /* Warna teks tombol */
        }

        .accordion-body {
            background-color: #f1f1f1; /* Warna latar belakang konten accordion */
            color: #333; /* Warna teks konten */
        }
    }

</style>
@endsection

@section('content')
<main class="main">
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
  
        <div class="container">
  
            <div class="row justify-content-center text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="col-xl-12 col-lg-12">
                    <h2>SiKeslah:</h2>
                    <h2>Sistem Informasi <br>
                        Kesesuaian Lahan Sawah</h2>
                    <p>Cari tahu kesesuaian lahanmu untuk pertanian padi, jagung atau kedelai di Kabupaten Sleman!</p>
                    <button style="font-size:24px; margin-top:50px" type="button" class="btn btn-outline-warning" onclick="scrollToAbout()"><b>Mulai</b></button>
                    {{-- <p class="d-inline-flex gap-4">
                        <a class="btn btn-outline-warning" role="button" href="#about" style="color:#ffc451; font-size:24px"><b>Mulai</b></a>
                        {{-- <a href="Layanan" class="btn-get-peta scrollto">Layanan</a> --}}
                   
                </div>
            </div>
        </div>
  
    </section><!-- /Hero Section -->

    <!-- Services Section -->
    <section id="services" class="services section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2 style="color:white">Layanan</h2>
        </div><!-- End Section Title -->
  
        <div class="container">
  
            <div class="row gy-4">
    
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="service-item position-relative">
                    <div class="icon">
                        <i class="fa-regular fa-map"></i>
                    </div>
                    <a href="{{ route('lbsmap') }}" class="stretched-link">
                    <h3>Peta Lahan Sawah</h3>
                    </a>
                    <p>Ketersediaan lahan sawah semakin menipis akibat alih fungsi lahan untuk pembangunan. Lihat ketersediaan lahan sawah di Kabupaten Sleman!</p>
                </div>
                </div><!-- End Service Item -->
    
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="service-item position-relative">
                    <div class="icon">
                        <i class="fa-solid fa-magnifying-glass-location"></i>
                    </div>
                    <a href="{{ route('keslahmap') }}" class="stretched-link">
                    <h3>Cek Kesesuaian Lahan</h3>
                    </a>
                    <p>Cek kesesuaian lahan adalah langkah penting untuk memastikan lahan cocok digunakan sesuai kebutuhan khususnya pertanian. Dapatkan informasi kesesuaian lahanmu!</p>
                </div>
                </div><!-- End Service Item -->
    
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300" id="to-nilai-keslah" style="cursor: pointer;">
                <div class="service-item position-relative">
                    <div class="icon">
                        <i class="fa-solid fa-clipboard-question"></i>
                    </div>
                    {{-- @if(auth()->check())  <!-- Memeriksa apakah pengguna sudah login -->
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.add-area') }}" class="stretched-link"><h3>Nilai Kesesuaian Lahan</h3></a>
                        @else
                            <a href="{{ route('user.add-area') }}" class="stretched-link"><h3>Nilai Kesesuaian Lahan</h3></a>
                        @endif
                    @else --}}
                    <!-- Opsional: Tautan untuk pengguna yang belum login -->
                    <a href="#" class="stretched-link"><h3>Nilai Kesesuaian Lahan</h3></a>
                    {{-- @endif --}}

                    {{-- <a href="{{ route('add-area') }}" class="stretched-link"> --}}
                    {{-- <h3>Nilai Kesesuaian Lahan</h3>
                    </a> --}}
                    <p>Nilai kesesuaian lahan adalah penentu dalam mengukur potensi suatu lahan untuk dimanfaatkan secara optimal. Lakukan evaluasi kesesuaian lahanmu!</p>
                </div>
                </div><!-- End Service Item -->
            </div>
  
        </div>
  
    </section><!-- /Services Section -->

    <!-- About Section -->
    <section id="about" class="about section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2 style="color:white">Panduan & Tentang Sikeslah</h2>
        </div><!-- End Section Title -->
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="accordion w-100 mx-auto" id="accordionPetunjuk">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    1. Apa itu SiKeslah?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionPetunjuk">
                                <div class="accordion-body">
                                <p style="text-align: justify">
                                    <strong>SiKeslah</strong> adalah aplikasi WebGIS yang dirancang untuk membantu petani, perencana wilayah, dan masyarakat umum dalam mengetahui dan 
                                    mengevaluasi kesesuaian lahan untuk pertanian di Kabupaten Sleman.
                                    Dengan SiKeslah, Anda bisa mengevaluasi kesesuaian lahan pertanian secara mendalam berdasarkan berbagai parameter seperti iklim, topografi, dan 
                                    kualitas tanah. WebGIS ini adalah mitra andal Anda untuk memastikan kesesuaian lahan pertanian di Kabupaten Sleman!
                                </p>
                                </div>
                            </div>
                        </div>
                            
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                2. Fitur Utama
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionPetunjuk">
                                <div class="accordion-body">
                                    {{-- <ul>
                                        <li><p>Peta ketersediaan lahan sawah dan kesesuaian lahan untuk tanaman padi, jagung, dan kedelai di Kabupaten Sleman.</p></li>
                                    </ul>
                                    <ul>
                                        <li><p>Pencarian lokasi lahan berdasarkan kelas kesesuaian lahan, jenis tanaman, dan parameter.</p></li>
                                    </ul>
                                    <ul>
                                        <li><p>Pencarian lokasi lahan berdasarkan pemilihan titik dan area.</p></li>
                                    </ul>
                                    <ul>
                                        <li><p>Evaluasi kesesuaian lahan berdasarkan parameter iklim, topografi, dan tanah.</p></li>
                                    </ul> --}}
                                    <ul>
                                        <li>
                                            <p style="text-align: justify"><strong>Peta Ketersediaan Lahan:</strong> Menampilkan peta lengkap lahan sawah beserta tingkat kesesuaian lahan untuk berbagai tanaman seperti padi, jagung, dan kedelai. Dapatkan informasi visual yang komprehensif tentang kondisi lahan di Kabupaten Sleman.
                                                <a href="{{ route('lbsmap') }}" style="color:blue"> Kunjungi laman >></a>
                                            </p>
                                        </li>
                                        <li>
                                            <p style="text-align: justify"><strong>Pencarian Lahan:</strong> Cari lokasi terbaik untuk bercocok tanam berdasarkan kelas kesesuaian lahan, jenis tanaman, atau parameter lain sesuai kebutuhan Anda.
                                                <a href="{{ route('keslahmap') }}" style="color:blue"> Kunjungi laman >></a>
                                            </p>
                                        </li>
                                        <li>
                                            <p style="text-align: justify"><strong>Evaluasi Detail:</strong> Analisis lahan secara spesifik berdasarkan titik dan area yang Anda pilih.
                                                <a href="{{ route('keslahmap') }}" style="color:blue"> Kunjungi laman >></a>
                                            </p>
                                        </li>
                                        <li>
                                            <p style="text-align: justify" id="to-nilai-keslah2"><strong>Penilaian Kesesuaian Lahan:</strong> Dapatkan evaluasi terperinci mengenai kesesuaian lahan berdasarkan faktor iklim, topografi, dan kondisi tanah untuk memaksimalkan hasil pertanian Anda.
                                                <a href="#" class="stretched-link" style="color: blue"> Kunjungi laman >></a>
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                3. Panduan Penggunaan
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionPetunjuk">
                                <div class="accordion-body">
                                    <ol>
                                        <li>
                                            <p>
                                                Masuk/Login akun (Opsional)
                                                <ul>
                                                    <li>
                                                        <p style="text-align: justify">
                                                            Anda dapat mengakses sebagian besar fitur tanpa login. Namun, untuk mendapatkan akses penuh dan menggunakan seluruh fitur, silakan login terlebih dahulu.
                                                        </p>
                                                    </li>
                                                </ul>
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                Akses Menu Layanan untuk menemukan berbagai fitur utama:
                                                <ul>
                                                    <li>
                                                        <p style="text-align: justify">Peta Lahan Sawah - Eksplorasi peta dan temukan informasi lahan.</p>
                                                    </li>
                                                    <li>
                                                        <p style="text-align: justify">
                                                            Cek Kesesuaian Lahan - Ketahui apakah lahan sawah Anda sesuai untuk jenis tanaman padi, jagung, atau kedelai.
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p style="text-align: justify">
                                                            Nilai Kesesuaian Lahan - Dapatkan hasil penilaian kesesuaian lahan dari data yang Anda miliki.
                                                        </p>
                                                    </li>
                                                </ul>                       
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                Pelajari petunjuk penggunaan SiKeslah.
                                                <a href="{{ route('panduan') }}" style="color:blue;"><span class="ms-2">Lihat panduan selengkapnya >></span></a>   
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                Pahami lebih lanjut tentang Evaluasi Kesesuaian Lahan
                                                <a href="{{ route('info') }}" style="color:blue;"><span class="ms-2">Pelajari selengkapnya >></span></a>   
                                            </p>
                                        </li>
                                    </ol>
                                </div>
                                {{-- <div class="accordion-body">
                                    <ol>
                                        <li>
                                            <p>
                                                Panduan Login dan Daftar di SiKeslah.
                                                <a href="{{ route('panduan') }}" style="color:blue;"><span class="ms-2">Lihat panduan selengkapnya</span></a>  
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                Cara melihat dan memahami peta lahan sawah. 
                                                <a href="{{ route('panduan') }}" style="color:blue;"><span class="ms-2">Lihat panduan selengkapnya</span></a>  
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                Panduan mengecek kesesuaian lahan berdasarkan parameter tertentu.
                                                <a href="{{ route('panduan') }}" style="color:blue;"><span class="ms-2">Lihat panduan selengkapnya</span></a>   
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                Informasi tentang penilaian kesesuaian lahan.
                                                <a href="{{ route('panduan') }}" style="color:blue;"><span class="ms-2">Lihat panduan selengkapnya</span></a>   
                                            </p>
                                        </li>
                                    </ol>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 d-flex align-items-center justify-content-center">
                    <div class="video-container">
                        <iframe src="https://www.youtube.com/embed/L4JqQh3NzAg" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>                
            </div>
        </div>
        

  
    </section><!-- /About Section -->

    <!-- Information Section -->
    <section id="information" class="informasi section">
        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2 style="color:white;">Info</h2>
            <h3 style="color:white; text-align:center;">Mengenal Kesesuaian Lahan</h3>
        </div><!-- End Section Title -->

        {{-- Section Content Info --}}
        <div class="container">
            <div class="info-grid">
                <div class="info-card">
                    <i class="icon icon-definition"></i>
                    <h3>Pengertian</h3>
                    <p style="text-align: justify; padding:10px">Menurut Peraturan Menteri Pertanian Nomor 79 Tahun 2013 tentang Pedoman Kesesuaian Lahan pada Komoditas Tanaman Pangan, 
                        kesesuaian lahan didefinisikan sebagai tingkat kecocokan suatu bidang lahan untuk penggunaan tanaman tertentu, baik tanaman semusim maupun tahunan.</p>
                    <a href="{{ route('info') }}" class="learn-more">Selengkapnya >></a>
                </div>
                <div class="info-card">
                    <i class="icon icon-target"></i>
                    <h3>Tujuan</h3>
                    <p style="text-align: justify; padding:10px">Evaluasi kesesuaian lahan bertujuan untuk mengidentifikasi penggunaan lahan yang paling cocok untuk meningkatkan produktivitas dan keberlanjutan. 
                        Hal ini membantu dalam pengambilan keputusan yang lebih baik dalam penggunaan lahan.</p>
                    <a href="{{ route('info') }}" class="learn-more">Selengkapnya >></a>
                </div>
                {{-- <div class="info-card">
                    <i class="icon icon-benefit"></i>
                    <h3>Manfaat</h3>
                    <p style="text-align: justify; padding:10px">Dengan memahami kesesuaian lahan, Anda dapat memanfaatkan lahan secara optimal, mengurangi kerusakan lingkungan, dan meningkatkan hasil pertanian. 
                        Manfaat ini juga mencakup peningkatan ketahanan pangan dan pengelolaan sumber daya yang lebih baik.</p>
                    <a href="#manfaat" class="learn-more">Selengkapnya >></a>
                </div> --}}
                <div class="info-card">
                    <i class="icon icon-method"></i>
                    <h3>Cara Evaluasi</h3>
                    <p style="text-align: justify; padding:10px">Proses evaluasi kesesuaian lahan meliputi analisis berbagai faktor seperti iklim, tanah, topografi, dan penggunaan lahan saat ini. Langkah-langkah ini 
                        memastikan bahwa keputusan yang diambil berbasis data dan relevan dengan kondisi lapangan.</p>
                    <a href="{{ route('info') }}" class="learn-more">Selengkapnya >></a>
                </div>
                <div class="info-card">
                    <i class="icon icon-method"></i>
                    <h3>Syarat & Ketentuan Evaluasi</h3>
                    <p style="text-align: justify; padding:10px">
                        Berdasarkan SNI: 8474:2018 tentang Penyusunan Peta Kesesuaian Lahan untuk Komoditas Pertanian Strategis Semidetil Skala 1:50.000, 
                        kriteria kesesuaian lahan merupakan nilai parameter persyaratan tumbuh yang dibutuhkan oleh sebuah komoditas pertanian tertentu. 
                    </p>
                    <a href="{{ route('info') }}" class="learn-more">Selengkapnya >></a>
                </div>
            </div>
        </div>       
        {{-- End Section Content --}}


    </section><!-- /Education Section -->


</main>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#to-nilai-keslah').on('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Anda harus Masuk akun!',
                text: 'Silakan Masuk terlebih dahulu untuk mengakses halaman ini.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        });
        $('#to-nilai-keslah2').on('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Anda harus Masuk akun!',
                text: 'Silakan Masuk terlebih dahulu untuk mengakses halaman ini.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        });
    });
    function scrollToAbout() {
        document.getElementById("about").scrollIntoView({
            behavior: "smooth"
        });
    }
</script>
@endsection


