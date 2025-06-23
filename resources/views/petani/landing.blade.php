<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sapu-App</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/logo.jpg" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  
  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/css/tooplate-mini-finance.css" rel="stylesheet">


  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
  <!-- =======================================================
  * Template Name: iLanding
  * Template URL: https://bootstrapmade.com/ilanding-bootstrap-landing-page-template/
  * Updated: Nov 12 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="assets/img/logo.jpg" alt="">
        <h1 class="sitename">Sapu-App</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Beranda</a></li>
          <li><a href="#features">RDKK</a></li>
          <li><a href="#about">Fitur</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      @guest('petani')
      <a class="btn-getstarted" href="/login">Get Started</a>
      @endguest
      @auth('petani')
      <div class="dropdown">
        <a class="btn-getstarted" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi-person-circle me-1"></i>{{ Auth::guard('petani')->user()->nama }}
        </a>
        <ul class="dropdown-menu bg-white shadow">
            <li>
                <a class="dropdown-item" href="/petani-profile">
                    <i class="bi-person me-2"></i>
                    Profile
                </a>
            </li>
            <li class="border-top mt-3 pt-2 mx-4">
                <form action="{{ route('petani.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item ms-0 me-0" href="{{ route('petani.logout') }}">
                        <i class="bi-box-arrow-left me-2"></i>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
    @endauth
    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
              <div class="company-badge mb-4">
                <i class="bi bi-gear-fill me-2"></i>
                Sapu-App
              </div>

              <h2 class="mb-4">
                Aplikasi Pengelolaan<br> 
                <span style="color: #f73f48;">Penyaluran Pupuk</span>
              </h2>

              <p class="mb-4 mb-md-5">
                Aplikasi ini dirancang untuk membantu proses pengelolaan penyaluran pupuk.
                Mari wujudkan pertanian maju berkelanjutan.
              </p>

              <div class="hero-buttons">
                @guest('petani')
                <a href="{{url('/login')}}" class="btn btn-primary me-0 me-sm-2 mx-1">Get Started</a> 
                @endguest
                @auth('petani')
                <a href="{{url('/petani-profile')}}" class="btn btn-primary me-0 me-sm-2 mx-1">Back to Dashboard</a>
                @endauth
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="hero-image" data-aos="zoom-out" data-aos-delay="300">
              <img src="assets/img/illustration.png" alt="Hero Image" class="img-fluid">

              {{-- <div class="customers-badge">
                <div class="customer-avatars">
                  <img src="assets/img/avatar-1.webp" alt="Customer 1" class="avatar">
                  <img src="assets/img/avatar-2.webp" alt="Customer 2" class="avatar">
                  <img src="assets/img/avatar-3.webp" alt="Customer 3" class="avatar">
                  <img src="assets/img/avatar-4.webp" alt="Customer 4" class="avatar">
                  <img src="assets/img/avatar-5.webp" alt="Customer 5" class="avatar">
                  <span class="avatar more">12+</span>
                </div>
                <p class="mb-0 mt-2">12,000+ lorem ipsum dolor sit amet consectetur adipiscing elit</p>
              </div> --}}
            </div>
          </div>
        </div>

        <div class="row stats-row gy-4 mt-5" data-aos="fade-up" data-aos-delay="500">
          <div class="col-lg-3 col-md-6">
            <div class="stat-item">
              <div class="stat-icon">
                <i class="bi bi-trophy"></i>
              </div>
              <div class="stat-content">
                <h4>3x Won Awards</h4>
                <p class="mb-0">Vestibulum ante ipsum</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="stat-item">
              <div class="stat-icon">
                <i class="bi bi-briefcase"></i>
              </div>
              <div class="stat-content">
                <h4>6.5k Faucibus</h4>
                <p class="mb-0">Nullam quis ante</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="stat-item">
              <div class="stat-icon">
                <i class="bi bi-graph-up"></i>
              </div>
              <div class="stat-content">
                <h4>80k Mauris</h4>
                <p class="mb-0">Etiam sit amet orci</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="stat-item">
              <div class="stat-icon">
                <i class="bi bi-award"></i>
              </div>
              <div class="stat-content">
                <h4>6x Phasellus</h4>
                <p class="mb-0">Vestibulum ante ipsum</p>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Hero Section -->

    <section id="features" class="features-cards section">

      <div class="container section-title" data-aos="fade-up">
          <h2>RDKK</h2>
          <p>Berikut data RDKK terbaru. Hanya 5 data teratas yang ditampilkan, untuk kesulurah data klik lihat lebih banyak!</p>
      </div>
      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-12 col-12">
            <div class="custom-block bg-white p-2">
                <div class="row align-items-center">
                    @if ($rdkk->isEmpty())
                        <div class="col-lg-6 col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="mb-4 text-black section-title">Data tidak tersedia</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 d-flex justify-content-end">
                            <div class="position-absolute top-0 end-0 p-3">
                                <form method="GET" action="{{ route('dashboard') }}" class="row g-2 d-flex justify-content-end">
                                    <!-- Dropdown Tahun -->
                                    <div class="col-lg-5 col-md-6 col-sm-12">
                                        <select name="tahun" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                                            @foreach($tahunrdkk->pluck('tahun')->unique()->sortDesc() as $t)
                                                <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            
                                    <!-- Dropdown Komoditi -->
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <select name="komoditi" id="komoditi" class="form-select form-select-sm" onchange="this.form.submit()">
                                            @foreach($komoditirdkk->pluck('komoditi')->unique()->sortDesc() as $k)
                                                <option value="{{ $k }}" {{ $komoditi == $k ? 'selected' : '' }}>{{ $k }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            
                                    <!-- Dropdown Periode -->
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <select name="periode" id="periode" class="form-select form-select-sm" onchange="this.form.submit()">
                                            @foreach($perioderdkk->pluck('periode')->unique()->sortDesc() as $p)
                                                <option value="{{ $p }}" {{ $periode == $p ? 'selected' : '' }}>{{ $p }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    @else
                    <div class="row">
                      <!-- Section Title -->
                      <div class="col-lg-6 col-12 section-title pb-3">
                          <p class="d-flex flex-wrap mt-1 mb-1">
                              <strong class="me-2">Tahun:</strong>
                              <span>{{ $tahun }}</span>
                          </p>
                          <p class="d-flex flex-wrap mt-1 mb-1">
                              <strong class="me-2">Periode:</strong>
                              <span>{{ $periode }}</span>
                          </p>
                          <p class="d-flex flex-wrap mt-1 mb-1">
                              <strong class="me-2">Komoditi:</strong>
                              <span>{{ $komoditi }}</span>
                          </p>
                      </div>
                  
                      <!-- Dropdown Filters -->
                      <div class="col-lg-6 col-12 d-flex justify-content-lg-end">
                          <form method="GET" action="{{ route('petani.landing') }}" class="row g-2 w-100">
                              <!-- Dropdown Tahun -->
                              <div class="col-12 col-md-4">
                                  <select name="tahun" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                                      @foreach($tahunrdkk->pluck('tahun')->unique()->sortDesc() as $t)
                                          <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                      @endforeach
                                  </select>
                              </div>
                  
                              <!-- Dropdown Komoditi -->
                              <div class="col-12 col-md-4">
                                  <select name="komoditi" id="komoditi" class="form-select form-select-sm" onchange="this.form.submit()">
                                      @foreach($komoditirdkk->pluck('komoditi')->unique()->sortDesc() as $k)
                                          <option value="{{ $k }}" {{ $komoditi == $k ? 'selected' : '' }}>{{ $k }}</option>
                                      @endforeach
                                  </select>
                              </div>
                  
                              <!-- Dropdown Periode -->
                              <div class="col-12 col-md-4">
                                  <select name="periode" id="periode" class="form-select form-select-sm" onchange="this.form.submit()">
                                      @foreach($perioderdkk->pluck('periode')->unique()->sortDesc() as $p)
                                          <option value="{{ $p }}" {{ $periode == $p ? 'selected' : '' }}>{{ $p }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </form>
                      </div>
                  </div>
                    
                    <div class="col-lg-12 col-12 pt-3">
                        <div class="table-responsive">
                            <table class="account-table table custom-table" id="scheduleTable1">
                        
                                <thead style="border: 1px solid black;text-align: center; ">
                                    <tr>
                                        <th scope="col" rowspan="3" style="border: 1px solid black;">NO</th>
                                        <th scope="col" rowspan="3" style="border: 1px solid black;">NIK</th>
                                        <th scope="col" rowspan="3" style="border: 1px solid black;">Nama</th>
                                        <th scope="col" rowspan="3" style="border: 1px solid black;">Rencana Tanam (Ha)</th>
                                        <th scope="col" colspan="{{ $uniqueNamaPupuk->count() * 4 }}" style="border: 1px solid black;">Amount</th>
                                    </tr>
                                    <tr>
                                        @foreach ($uniqueNamaPupuk as $namaPupuk)
                                        <th scope="col" colspan="4" style="border: 1px solid black;">{{ $namaPupuk }}</th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach ($uniqueNamaPupuk as $mtPupuk)
                                        <th scope="col" style="border: 1px solid black;">MT-1</th>
                                        <th scope="col" style="border: 1px solid black;">MT-2</th>
                                        <th scope="col" style="border: 1px solid black;">MT-3</th>
                                        <th scope="col" style="border: 1px solid black;">JML</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rdkk as $nik => $data)
                                        @php
                                            $firstItem = $data->first(); // Ambil item pertama dalam grup
                                        @endphp
                                        <tr>
                                            <td style="border: 1px solid black;">{{ $loop->iteration }}</td>
                                            <td style="border: 1px solid black;">
                                                {{ $firstItem->nik }}
                                            </td>
                                            <td style="border: 1px solid black;">
                                                {{ $firstItem->petani->nama ?? 'Petani tidak ditemukan' }}
                                            </td>
                                            <td style="border: 1px solid black;">
                                                {{ $firstItem->pengajuan->luasan ?? 'Pengajuan tidak ditemukan' }}
                                            </td>
                                
                                            @foreach ($uniqueNamaPupuk as $pupukNama)
                                                @php
                                                    $rdkkItem = $data->where('pupuk.nama_pupuk', $pupukNama)->first();
                                                @endphp
                                                <td style="border: 1px solid black;">{{ $rdkkItem->volume_pupuk_mt1 ?? 0 }}</td>
                                                <td style="border: 1px solid black;">{{ $rdkkItem->volume_pupuk_mt2 ?? 0 }}</td>
                                                <td style="border: 1px solid black;">{{ $rdkkItem->volume_pupuk_mt3 ?? 0 }}</td>
                                                <td style="border: 1px solid black;">{{ 
                                                    ($rdkkItem->volume_pupuk_mt1 ?? 0) + 
                                                    ($rdkkItem->volume_pupuk_mt2 ?? 0) + 
                                                    ($rdkkItem->volume_pupuk_mt3 ?? 0) 
                                                }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                                
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            {{-- Tambahkan di bawah tabel RDKK --}}
          @guest('petani')
          <div class="text-center mt-0">
              <a href="{{ request()->getSchemeAndHttpHost() }}/login" class="btn btn-primary " style="background:#0d83fd;border-color:#0d83fd;border-radius: 50px; color: white; padding: 10px 20px; text-decoration: none;">
                  Lihat Lebih Banyak
                  <i class="bi-arrow-up-right-circle-fill ms-2"></i>
              </a>
          </div>
          @endguest
          @auth('petani')
          <div class="text-center mt-0">
            <a href="/dashboard?tahun={{ $tahun }}&komoditi={{ $komoditi }}&periode={{ $periode }}" class="btn btn-primary " style="background:#0d83fd;border-color:#0d83fd;border-radius: 50px; color: white; padding: 10px 20px; text-decoration: none;">
              Lihat Lebih Banyak
              <i class="bi-arrow-up-right-circle-fill ms-2"></i>
            </a>
          </div>
          @endauth


        </div>

      </div>

    </section><!-- /Features Cards Section -->

    <!-- Features Section -->
    <section id="about" class="features section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Fitur</h2>
        <p>Pengajuan dan Penyaluran pupuk merupakan 2 fitur yang memiliki fungsi penting aplikasi ini</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="d-flex justify-content-center">

          <ul class="nav nav-tabs" data-aos="fade-up" data-aos-delay="100">

            <li class="nav-item">
              <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#features-tab-1">
                <h4>Pengajuan</h4>
              </a>
            </li><!-- End tab nav item -->

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-2">
                <h4>Penyaluran</h4>
              </a><!-- End tab nav item -->

            </li>

          </ul>

        </div>

        <div class="tab-content" data-aos="fade-up" data-aos-delay="200">

          <div class="tab-pane fade active show" id="features-tab-1">
            <div class="row">
              <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                <h3>Pengajuan Pupuk</h3>
                <p class="fst-italic">
                  Petani melakukan pengajuan pupuk ditahun ini, untuk medapatkan pupuk pada tahun depan. Pengajuan hanya dapat dilakukan setelah petani melengkapi profil dan data lahan. Berikut langkah - langkah pengajuan:
                </p>
                <ul>
                  <li><i class="bi bi-1-circle"></i> <span>Petani melengkapi profile dan data lahan (KTP, KK, SPPT)</span></li>
                  <li><i class="bi bi-2-circle"></i> <span>Membuat pengajuan dengan mengisikan from pengajuan.</span></li>
                  <li><i class="bi bi-3-circle"></i> <span>Tunggu pengajuan divalidasi petugas poktan.</span></li>
                  <li><i class="bi bi-4-circle"></i> <span>Jika tidak valid, cek catatan lalu edit data pengajuan.</span></li>
                  <li><i class="bi bi-check2-all"></i> <span>Data Valid, pengajuan sukses. Tunggu info RDKK dan penyaluran ditahun depan</span></li>
                </ul>
              </div>
              <div class="col-lg-6 order-1 order-lg-2 text-center">
                <img src="assets/img/landing-ajuan.png" alt="" class="img-fluid">
              </div>
            </div>
          </div><!-- End tab content item -->

          <div class="tab-pane fade" id="features-tab-2">
            <div class="row">
              <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                <h3>Penyaluran Pupuk</h3>
                <p class="fst-italic">
                  Petani yang melakukan pengajuan pada tahun sebelumnya, dapat mendapatkan pupuk ditahun ini. Pupuk akan disalurkan berdasarkan data RDKK yang sudah dibuat dan disimpan secara permanen. Berikut langkah - langkah terkait penyaluran:
                </p>
                <ul>
                  <li><i class="bi bi-1-circle"></i> <span>Petani mengakses halaman penyaluran </span></li>
                  <li><i class="bi bi-2-circle"></i> <span>Periksa pupuk dan jumlahnya, yang dapat diterima ditahun ini.</span></li>
                  <li><i class="bi bi-3-circle"></i> <span>Periksa juga total harga yang harus dibayarkan.</span></li>
                  <li><i class="bi bi-4-circle"></i> <span>Petani dapat membayarkan pupuk secara online maupun offline.</span></li>
                  <li><i class="bi bi-4-circle"></i> <span>Ambil pupuk, jangka waktu pengambilan adalah sampai tahun penyaluran berakhir.</span></li>
                  <li><i class="bi bi-check2-all"></i> <span>Petugas Poktan akan mengkonfirmasi pupuk yang telah diambil. Penyaluran selesai</span></li>
                </ul>
              </div>
              <div class="col-lg-6 order-1 order-lg-2 text-center">
                <img src="assets/img/landing-salur.png" alt="" class="img-fluid">
              </div>
            </div>
          </div><!-- End tab content item -->


        </div>

      </div>

    </section><!-- /Features Section -->

    <!-- Features Cards Section -->

    <!-- Call To Action Section -->
    <section id="call-to-action" class="call-to-action section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row content justify-content-center align-items-center position-relative">
          <div class="col-lg-8 mx-auto text-center">
            <h2 class="display-4 mb-4">Izinkan website mengirim notifikasi RDKK</h2>
            <p class="mb-4">Login ke akun anda, beri izin notifikasi untuk mendapatkan informasi RDKK dan menerima penyaluran.</p>
            <a href="#" class="btn btn-cta">Login Sekarang</a>
          </div>

          <!-- Abstract Background Elements -->
          <div class="shape shape-1">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path d="M47.1,-57.1C59.9,-45.6,68.5,-28.9,71.4,-10.9C74.2,7.1,71.3,26.3,61.5,41.1C51.7,55.9,35,66.2,16.9,69.2C-1.3,72.2,-21,67.8,-36.9,57.9C-52.8,48,-64.9,32.6,-69.1,15.1C-73.3,-2.4,-69.5,-22,-59.4,-37.1C-49.3,-52.2,-32.8,-62.9,-15.7,-64.9C1.5,-67,34.3,-68.5,47.1,-57.1Z" transform="translate(100 100)"></path>
            </svg>
          </div>

          <div class="shape shape-2">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path d="M41.3,-49.1C54.4,-39.3,66.6,-27.2,71.1,-12.1C75.6,3,72.4,20.9,63.3,34.4C54.2,47.9,39.2,56.9,23.2,62.3C7.1,67.7,-10,69.4,-24.8,64.1C-39.7,58.8,-52.3,46.5,-60.1,31.5C-67.9,16.4,-70.9,-1.4,-66.3,-16.6C-61.8,-31.8,-49.7,-44.3,-36.3,-54C-22.9,-63.7,-8.2,-70.6,3.6,-75.1C15.4,-79.6,28.2,-58.9,41.3,-49.1Z" transform="translate(100 100)"></path>
            </svg>
          </div>

          <!-- Dot Pattern Groups -->
          <div class="dots dots-1">
            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
              <pattern id="dot-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                <circle cx="2" cy="2" r="2" fill="currentColor"></circle>
              </pattern>
              <rect width="100" height="100" fill="url(#dot-pattern)"></rect>
            </svg>
          </div>

          <div class="dots dots-2">
            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
              <pattern id="dot-pattern-2" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                <circle cx="2" cy="2" r="2" fill="currentColor"></circle>
              </pattern>
              <rect width="100" height="100" fill="url(#dot-pattern-2)"></rect>
            </svg>
          </div>

          <div class="shape shape-3">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path d="M43.3,-57.1C57.4,-46.5,71.1,-32.6,75.3,-16.2C79.5,0.2,74.2,19.1,65.1,35.3C56,51.5,43.1,65,27.4,71.7C11.7,78.4,-6.8,78.3,-23.9,72.4C-41,66.5,-56.7,54.8,-65.4,39.2C-74.1,23.6,-75.8,4,-71.7,-13.2C-67.6,-30.4,-57.7,-45.2,-44.3,-56.1C-30.9,-67,-15.5,-74,0.7,-74.9C16.8,-75.8,33.7,-70.7,43.3,-57.1Z" transform="translate(100 100)"></path>
            </svg>
          </div>
        </div>

      </div>

    </section>
    <!-- /Call To Action Section -->

  </main>

  <footer id="footer" class="footer">

    <div class="container footer-top ">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about justify-content-center">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">iLanding</span>
          </a>
          <div class="footer-contact pt-3">
            <p>A108 Adam Street</p>
            <p>New York, NY 535022</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
            <p><strong>Email:</strong> <span>info@example.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>


      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">Sapu-App</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
