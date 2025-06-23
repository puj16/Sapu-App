<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="Tooplate">

        <title>Sapu.App - Petani</title>
        <!-- Favicons -->
        <link href="{{ asset ('assets/img/logo.jpg') }}" rel="icon">
        <!-- CSS FILES -->      
        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

        <link href="{{ asset('assets/css/bootstrap-icons.css') }}" rel="stylesheet">

        <link href="{{ asset('assets/css/apexcharts.css') }}" rel="stylesheet">

        <link href="{{ asset('assets/css/tooplate-mini-finance.css') }}" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        @yield('header')

<!--

Tooplate 2135 Mini Finance

https://www.tooplate.com/view/2135-mini-finance

Bootstrap 5 Dashboard Admin Template

-->

    </head>
    
    <body>
        <header class="navbar sticky-top flex-md-nowrap">
            <div class="col-md-3col-lg-3me-0 px-3 fs-6">
                <a class="navbar-brand d-flex align-items-center" href="index.html">
                    <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" class="logo">
                    <strong class="d-none d-md-block ms-2">Aplikasi Pengelolaan <br> <span style="color:#247cff;">Penyaluran Pupuk</span></strong>
                </a>
            </div>

            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            
            <div class="navbar-nav pe-1" >
                <div class="nav-item text-nowrap d-flex align-items-center " >
                    <div class="dropdown ps-3">
                        <a class="nav-link dropdown-toggle text-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="navbarLightDropdownMenuLink">
                            <button onclick="askForPermission()" class="btn btn-danger"><i class="bi-bell"></i></button>
                            
                        </a>

                    </div>



                    <div class="dropdown px-3">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi-person-circle me-1"></i>{{ Auth::guard('petani')->user()->nama }}
                        </a>
                        <ul class="dropdown-menu bg-white shadow">
                            <li>
                                <div class="dropdown-menu-profile-thumb d-flex">
                                    <img src="images/profile/profile.jpg" class="profile-image img-fluid me-3" alt="">
                                    <div class="d-flex flex-column">
                                        <small>{{ Auth::guard('petani')->user()->nama }}</small>
                                        <a href="#">{{ Auth::guard('petani')->user()->nik }}</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/petani-profile') }}">
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
                </div>
            </div>
        </header>

        <div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-2 col-lg-2 d-md-block sidebar collapse">
                    <div class="position-sticky py-4 px-3 sidebar-sticky">
                        <ul class="nav flex-column h-100">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" 
                                   aria-current="page" href="/dashboard">
                                    <i class="bi-house-fill me-2"></i>
                                    Overview
                                </a>
                            </li>
                
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('petani-profile') ? 'active' : '' }}" 
                                   href="/petani-profile">
                                    <i class="bi-person me-2"></i>
                                    Petani
                                </a>
                            </li>
                
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('lahan') ? 'active' : '' }}" 
                                   href="/lahan">
                                    <i class="bi-award me-2"></i>
                                    Lahan
                                </a>
                            </li>
                
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('pengajuan-index') ? 'active' : '' }}" 
                                   href="/pengajuan-index">
                                    <i class="bi-sunrise me-2"></i>
                                    Pengajuan
                                </a>
                            </li>
                
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('penyaluran-show') ? 'active' : '' }}" 
                                   href="/penyaluran-show">
                                    <i class="bi-shield-check me-2"></i>
                                    Penyaluran
                                </a>
                            </li>
                
                            <li class="nav-item featured-box mt-lg-5 mt-4 mb-4">
                                <a class="btn custom-btn" href="/">Back to Home</a>
                            </li>
                
                            <li class="nav-item border-top mt-auto pt-2">
                                <form action="{{ route('petani.logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="nav-link custom-logout-button">
                                        <i class="bi-box-arrow-left me-2"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>

                <main class="main-wrapper col-md-10 ms-sm-auto py-4 col-lg-10 px-md-4 border-start">
                    <div class="title-group mb-3">
                        <h1 class="h2 mb-0">@yield('header')</h1>
                    </div>

                    @yield('content')

                    <footer class="site-footer">
                        <div class="container">
                            <div class="row">
                                
                                <div class="col-lg-12 col-12">
                                    <p class="copyright-text">Copyright © Mini Finance 2048 
                                    - Design: <a rel="sponsored" href="https://www.tooplate.com" target="_blank">Tooplate</a></p>
                                </div>

                            </div>
                        </div>
                    </footer>

                </main>

            </div>
        </div>

        <!-- JAVASCRIPT FILES -->
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

        {{-- <script src="{{ URL::asset('assets/js/jquery-3.7.1.min.js') }}"></script> --}}
<script>
            
    navigator.serviceWorker.register('service-worker.js');
    
    function askForPermission() {
        console.log("Meminta izin notifikasi...");
        Notification.requestPermission().then((permission) => {
            console.log("Permission result:", permission);
    
            if (permission === 'granted') {
                console.log("Izin diberikan. Menunggu service worker siap...");
    
                navigator.serviceWorker.ready.then((sw) => {
                    console.log("Service worker siap. Mendaftarkan subscription...");
    
                    sw.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: "BKb-3rQok_QrP9KkjbWxRubtdVGbvXqWY2DNFN89oCLf4fB_0w4aLm2tP0QFi4T9PLQoTxdcPOG0pYEppywW-KA"
                    }).then((subscription) => {
                        console.log("Subscription berhasil:", subscription);
                        alert("Subscription berhasil");
    
                        // Simpan ke server
                        saveSub(JSON.stringify(subscription));
                    }).catch((err) => {
                        console.error("Gagal subscribe:", err);
                        alert("Gagal subscribe: " + err.message);
                    });
                }).catch((err) => {
                    console.error("Service worker tidak siap:", err);
                    alert("Service worker tidak siap: " + err.message);
                });
            } else {
                console.warn("Izin notifikasi ditolak.");
                alert("Izin notifikasi ditolak.");
            }
        });
    }
    
    function saveSub(sub) {
        console.log("Menyimpan subscription ke server...", sub);
        alert("Mengirim subscription ke server...");
    
        const url = 'save-push-notification-sub';
        alert("URL yang digunakan: " + url);
        console.log("URL yang digunakan:", url);
    
        if (typeof $ === 'undefined') {
            console.error("jQuery is not loaded!");
            alert("jQuery is not loaded!");
    
            return;
        }
        $.ajax({
            type: 'post',
            url: url,
            data: {
                '_token': "{{ csrf_token() }}",
                'sub': sub
            },
            success: function(data) {
                console.log("Subscription berhasil disimpan ke server:", data);
                alert("Berhasil disimpan ke server!");
            },
            error: function(xhr, status, error) {
            console.error("Gagal menyimpan ke server:", {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                error: error
            });
            alert("Gagal menyimpan ke server: " + xhr.responseText + xhr.status);
        }
        });
    }
            function sendNotification() {
                event.preventDefault();
    
                $.ajax({
                    type: 'post',
                    url: 'send-push-notification',
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'title': $("#title").val(),
                        'body': $("#body").val(),
                        'idOfProduct': $("#idOfProduct").val(),
                    },
                    success: function(data) {
                        alert('send Successfull');
                        console.log(data);
                    }
                });
            }
        </script>
        @stack('scripts')
    </body>
</html>