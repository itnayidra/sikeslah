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
                            <h1 style="color:#ffc451;"><strong>Mengenal Kesesuaian Lahan</strong></span>!</h1>
                        </div>
                        <p style="color:white; margin-top: 15px">
                            <strong></strong>
                            <br>
                            <ul style="list-style:none; line-height: 2;">
                                <li>
                                    <i class="fa-brands fa-readme" style="color:white"></i>
                                    <a href="#info-pengertian" class="active-link"><span class="ms-2">Pengertian</span></a>        
                                </li>
                                <li>
                                    <i class="fa-brands fa-readme" style="color:white"></i>
                                    <a href="#info-tujuan" class="active-link"><span class="ms-2">Tujuan</span></a>        
                                </li>
                                <li>
                                    <i class="fa-brands fa-readme" style="color:white"></i>
                                    <a href="#info-cara-evaluasi" class="active-link"><span class="ms-2">Cara Evaluasi Kesesuaian Lahan</span></a>        
                                </li>
                                <li>
                                    <i class="fa-brands fa-readme" style="color:white"></i>
                                    <a href="#info-syarat-evaluasi"class="active-link"><span class="ms-2">Syarat & Ketentuan Evaluasi Kesesuaian Lahan</span></a>        
                                </li>
                            </ul>
                            <br>
                        </p>
                    </div><!-- /.row -->
                </div>
                
                <div class="col-lg-8 col-12">
                    <section id="info-pengertian" class="info-pengertian" style="padding-bottom:0px; padding-top:20px">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-left">Pengertian</h3>
                            </div>
                            <div class="card-body">
                                <p style="text-align: justify; padding:10px; text-indent:50px;">Menurut Peraturan Menteri Pertanian Nomor 79 Tahun 2013 tentang Pedoman Kesesuaian Lahan pada Komoditas Tanaman Pangan, 
                                    kesesuaian lahan didefinisikan sebagai tingkat kecocokan suatu bidang lahan untuk penggunaan tanaman tertentu, baik tanaman semusim maupun tahunan.
                                    Kesesuaian lahan dibedakan menjadi kesesuaian lahan aktual dan potensial. Kesesuaian lahan aktual merupakan kesesuaian lahan saatini dalam keadaan alami, tanpa ada perbaikan lahan. Di sisi lain, 
                                    kesesuaian lahan potensial adalah kesesuaian lahan setelah dilakukan perbaikan lahan.
                                </p>
                            </div>                  
                        </div>
                    </section>

                    <section id="info-tujuan" class="info-tujuan" style="padding-top:40px; padding-bottom:0">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-left">Tujuan</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p style="text-align: justify; padding:10px; text-indent:50px;">
                                            Evaluasi lahan bertujuan untuk menentukan nilai suatu lahan guna tujuan tertentu. Tujuan dari evaluasi harus jelas. Menurut FAO (1976),
                                            evaluasi lahan perlu memperhatikan aspek ekonomi, sosial, serta lingkungan yang berkaitan dengan perencanaan tataguna lahan. 
                                            Dengan memahami kesesuaian lahan, Anda dapat memanfaatkan lahan secara optimal, mengurangi kerusakan lingkungan, dan meningkatkan hasil pertanian.                                       
                                         </p>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="info-cara-evaluasi" class="info-cara-evaluasi" style="padding-top:40px; padding-bottom:0">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-left">Cara Evaluasi/Penilaian Kesesuaian Lahan</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="justify-content-between align-items-center">
                                        <p style="text-align: justify; text-indent:50px; padding-right: 10px; padding-left:10px">
                                            <b>Evaluasi atau penilaian kesesuaian lahan</b> adalah proses menentukan tingkat kemampuan adaptasi suatu lahan ketika dimanfaatkan 
                                            untuk penggunaan lahan tertentu. Penilaian kesesuaian lahan <b>dilakukan sebelum adanya tindakan pengelolaan lahan</b> dan 
                                            dilakukan pada kondisi saat ini. Penilaian kesesuaian lahan <b>dapat dilakukan dengan beragam cara</b>, seperti perkalian 
                                            dengan parameter, penjumlahan atau menggunakan pencocokan antara kualitas lahan dan karakteristik lahan sebagai 
                                            parameter dengan kriteria kelas kesesuaian lahan berdasarkan persyaratan tumbuh tanaman atau tanaman lainnya yang dievaluasi. 
                                            <b>Evaluasi kesesuaian lahan dengan cara pencocokan (matching) dilakukan dengan mencocokan antara karakteristik lahan dengan 
                                            persyaratan penggunaan lahan termasuk persyaratan tumbuh tanaman, lingkungan, dan manajemen (Wahyunto et al., 2016)</b>. Proses 
                                            pencocokan dilakukan secara bertahap sesuai urutan kriteria kesesuaian lahannya. 
                                        </p>
                                        <p style="text-align: justify; text-indent:50px; padding-right: 10px; padding-left:10px">
                                            Penentuan kelas kesesuaian lahan menggunakan dua kelas kesesuaian lahan yang lama didasarkan pada tingkat kesesuaiannya. 
                                            <b>Jika terdapat dua kelas kesesuaian lahan yang berbeda, maka kelas kesesuaian lahan yang baru dipilih berdasarkan tingkat kesesuaiannya</b>. 
                                            Kelas kesesuaian S1 hanya dibentuk oleh kelas S1 dan S1. Sementara itu, jika terdapat dua kelas kesesuaian yang berbeda, maka kelas 
                                            kesesuaian yang lebih rendah dipilih.            
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="info-syarat-evaluasi" class="info-syarat-evaluasi" style="padding-top:40px;">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-left">Syarat & Parameter Evaluasi Kesesuaian Lahan</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="justify-content-between align-items-center">
                                        <p style="text-align: justify; text-indent:50px; padding-right: 10px; padding-left:10px">
                                            Berdasarkan SNI 8474:2018 tentang Penyusunan Peta Kesesuaian Lahan untuk Komoditas Pertanian Strategis Semidetil Skala 1:50.000, 
                                            kriteria kesesuaian lahan merupakan nilai parameter persyaratan tumbuh yang dibutuhkan oleh sebuah komoditas pertanian tertentu. 
                                            Nilai parameter setiap kriteria digunakan untuk proses pencocokan dengan satuan lahan. <b>Kriteria kesesuaian lahan untuk jenis tanaman pertanian berbeda-beda</b>.
                                        </p>
                                        <p style="text-align: justify; text-indent:50px; padding-right: 10px; padding-left:10px">
                                            <b>Variabel persyaratan tumbuh tanaman yang digunakan dalam evaluasi kesesuaian lahan, antara lain kondisi temperatur berupa temperatur rata-rata tahunan, ketersediaan air berupa curah hujan 
                                                dan jumlah bulan basah/kering, ketersediaan oksigen berupa drainase, kondisi perakaran berupa tekstur dan kedalaman tanah, retensi hara mencakup KTK liat, kejenuhan basa, pH H2O, dan c-organik, 
                                                bahaya erosi, dan bahaya banjir.</b> Persyaratan utamanya terdiri atas temperatur, kelembaban, oksigen, dan hara (Djaenudin et al., 2011). Persyaratan lainnya berupa media perakaran yang ditentukan 
                                            oleh drainase, tekstur, dan kedalaman efektif. 
                                        </p>
                                        <p>
                                            <ol>
                                                <li>
                                                    <p style="text-align: justify; padding-right: 10px; margin-bottom:1px; font-weight:bold;">Kondisi temperatur</p>
                                                    <p style="text-align: justify; padding-right: 10px;">
                                                    Kondisi temperatur dibedakan menjadi temperatur tanah dan udara yang berpengaruh terhadap proses fisiologis tanaman. Akan tetapi, pada webGIS ini hanya menggunakan temperatur udara.
                                                    Temperatur yang terlalu tinggi atau rendah dapat menghambat pertumbuhan tanaman pada proses fotosintesis dan respirasi. Setiap tanaman memiliki kisaran temperatur optimal yang berbeda-beda. 
                                                    </p>
                                                </li> 
                                                <li>
                                                    <p style="text-align: justify; padding-right: 10px; margin-bottom:1px; font-weight:bold;">Bulan basah dan bulan kering</p>
                                                    <p style="text-align: justify; padding-right: 10px;">
                                                        Bulan basah memiliki curah hujan >200 mm, sedangkan bulan kering < 100 mm. Parameter ini menunjukkan ketersediaan air bagi tanaman.
                                                    </p>
                                                </li> 
                                                <li>
                                                    <p style="text-align: justify; padding-right: 10px; margin-bottom:1px; font-weight:bold;">Drainase</p>
                                                    <p style="text-align: justify; padding-right: 10px;">
                                                        Drainase yang baik mampu meningkatkan ketersediaan oksigen dan mendukung pertumbuhan tanaman. Drainase merupakan hubungan antara laju perkolasi air ke dalam tanah dan aerasi udara dalam tanah.
                                                        Drainase yang baik memiliki kondisi aerasi tanah cukup baik sehingga tanah memiliki cukup oksigen yang membantu akar tanaman berkembang dengan baik dan menyerap unsur hara secara optimal.
                                                    </p>
                                                </li> 
                                                <li>
                                                    <p style="text-align: justify; padding-right: 10px; margin-bottom:1px; font-weight:bold;">Kondisi perakaran</p>
                                                    <p style="text-align: justify; padding-right: 10px;">
                                                        Kondisi perakaran mencakup tekstur tanah dan kedalaman tanah. Akan tetapi, khusus tanaman padi sawah irigasi terdapat variabel drainase pada kondisi perakaran. Tekstur merupakan gabungan komposisi partikel tanah halus dengan diameter ≤2 mm yakni pasir, debu, dan liat.
                                                        Tekstur tanah dapat membatasi perkembangan akar dan penyerapan air serta nutrisi. Sementara itu, kedalaman tanah baik bagi pertumbuhan akar tanaman. Apabila keladaman tanah relatif tipis maka akan menghambat perkembangan akar.
                                                    </p>
                                                </li> 
                                                <li>
                                                    <p style="text-align: justify; padding-right: 10px; margin-bottom:1px; font-weight:bold;">Retensi hara</p>
                                                    <p style="text-align: justify; padding-right: 10px;">
                                                        Retensi hara mencakup <b>kapasitas tukar kation (KTK) liat, kejenuhan basa, pH H2O, dan c-organik</b>. Retensi hara berupa kemampuan tanah untuk menyimpan nutrisi yang dibutuhkan tanaman. Bahan organik atau c-organik adalah seluruh karbon di dalam tanah yang berasal dari sisa tanaman atau tumbuhan dan hewan yang telah mati. 
                                                        Pengukuran pH dilakukan dengan menggunakan penilaian H2O. Nilai pH atau reaksi tanah yang optimal untuk lahan sawah irigasi berkisar antara 6.5-7.0 (normal). Kejenuhan basa juga mempengaruhi tingkat kesuburan tanah. Persentase nilai kejenuhan basa dapat menunjukkan tingkat kesuburan tanah. 
                                                        Kapasitas tukar kation (KTK) merupakan sifat kimia tanah yang berhubungan erat dengan kesuburan tanah. Tanah dengan kadar liat atau bahan organik yang tinggi mempunyai nilai KTK yang lebih tinggi daripada tanah berpasir atau tanah berbahan organik rendah. 
                                                    </p>
                                                </li> 
                                                <li>
                                                    <p style="text-align: justify; padding-right: 10px; margin-bottom:1px; font-weight:bold;">Bahaya erosi</p>
                                                    <p style="text-align: justify; padding-right: 10px;">
                                                        Bahaya erosi dikategorikan menjadi lima kelas yakni sangat ringan, ringan, sedang, berat, dan sangat berat. Bahaya erosi berpengaruh terhadap kemampuan tanah untuk menyerap dan menahan air. Bahaya erosi dapat diketahui dari kemiringan lereng.
                                                        Hal itu disebabkan kecuraman lereng, panjang lereng, dan bentuk lereng akan mempengaruhi besarnya erosi dan aliran permukaan. 
                                                    </p>
                                                </li> 
                                                <li>
                                                    <p style="text-align: justify; padding-right: 10px; margin-bottom:1px; font-weight:bold;">Bahaya banjir</p>
                                                    <p style="text-align: justify; padding-right: 10px;">
                                                        Bahaya banjir digunakan sebagai bahan pertimbangan risiko dan dampak potensial dari banjir terhadap kondisi lahan dan pertumbuhan tanaman. 
                                                    </p>
                                                </li> 
                                            </ol>
                                        </p>
                                    </div>
                                </div>
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
   
</script>
@endsection