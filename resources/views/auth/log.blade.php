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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">  
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.1/css/glightbox.min.css">  
  <link href="https://cdn.jsdelivr.net/npm/swiper@9.4.1/swiper-bundle.min.css" rel="stylesheet">  
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



  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section pt-3">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6">
                <div class="hero-image" data-aos="zoom-out" data-aos-delay="300">
                  <img src="assets/img/log.png" alt="Hero Image" class="img-fluid">
    
                </div>
          </div>

          <div class="col-lg-6">
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger text-white">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        
        
            <div class="hero-content features" data-aos="fade-up" data-aos-delay="200">
                <div class="container">
                    <h1 class="mb-3">
                        Login ! <br>
                      </h1>
        
                      <div class="d-flex justify-content-lg-start justify-content-center">
                        <ul class="nav nav-tabs" data-aos="fade-up" data-aos-delay="100">
                          <li class="nav-item">
                            <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#features-tab-1">
                              <h4>Petani</h4>
                            </a>
                          </li><!-- End tab nav item -->
                      
                          <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-2">
                              <h4>Petugas</h4>
                            </a><!-- End tab nav item -->
                          </li>
                        </ul>
                      </div>

                      <div class="tab-content" data-aos="fade-up" data-aos-delay="200">
            
                      <div class="tab-pane fade active show" id="features-tab-1">
                        <div class="contact-form" data-aos="fade-up" data-aos-delay="300">
                            <form action="/loginPetani" method="post"  data-aos="fade-up" data-aos-delay="200">
                                
                              <div class="row gy-4">
                                @csrf
                                <div class="input-group col-12">
                                  <input type="text" id="nik" name="nik" class="form-control border-0 border-bottom" placeholder="Masukkan NIK" required>
                                </div>
              
                                <div class="col-12 ">
                                  <input type="password" id="password_petani" class="form-control border-0 border-bottom" name="password" placeholder="Masukkan Password" required>
                                </div>
              
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agreement" required>
                                        <label class="form-check-label" for="agreement">
                                            Saya menyetujui syarat dan ketentuan yang berlaku.
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="hero-buttons">
                                    <button type="submit"  id="submitButton1" class="btn me-0 me-sm-2 mx-1" style="background: #f73f48; color: white;" disabled>Masuk Akun</button>
                                </div>

              
                              </div>
                            </form>
              
                          </div>
                      </div><!-- End tab content item -->
            
                      <div class="tab-pane fade" id="features-tab-2">
                        <div class="contact-form" data-aos="fade-up" data-aos-delay="300">
                            <form action="/login_post" method="post"  data-aos="fade-up" data-aos-delay="200">
                                
                              <div class="row gy-4">
                                @csrf
                                <div class="col-12">
                                  <input type="text" id="username" name="username" class="form-control border-0 border-bottom" placeholder="Masukkan Username " required>
                                </div>
              
                                <div class="col-12 ">
                                  <input type="password" class="form-control border-0 border-bottom" id="password" name="password" placeholder="Masukkan Password" required>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agreement1" required>
                                        <label class="form-check-label" for="agreement">
                                            Saya menyetujui syarat dan ketentuan yang berlaku.
                                        </label>
                                    </div>
                                </div>
                               
                                <div class="hero-buttons">
                                    <button type="submit"  id="submitButton2" class="btn me-0 me-sm-2 mx-1" style="background: #f73f48; color: white; " disabled>Masuk Akun</button>
                                  </div>
              
                              </div>
                            </form>
              
                          </div>
                      </div><!-- End tab content item -->
            
            
                    </div>
            
                  </div>
            </div>
          </div>
        </div>



      </div>

    </section><!-- /Hero Section -->

  </main>



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

  <script>
    document.addEventListener("DOMContentLoaded", function () {
        const nikInput = document.getElementById("nik");
        const passwordInput = document.getElementById("password_petani");
        const agreementCheckbox = document.getElementById("agreement");
        const submitButton = document.getElementById("submitButton1");

        function toggleSubmitButton() {
            // Aktifkan tombol hanya jika semua field diisi dan checkbox dicentang
            if (nikInput.value.trim() !== "" && passwordInput.value.trim() !== "" && agreementCheckbox.checked) {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
            }
        }

        // Tambahkan event listener untuk memantau perubahan
        nikInput.addEventListener("input", toggleSubmitButton);
        passwordInput.addEventListener("input", toggleSubmitButton);
        agreementCheckbox.addEventListener("change", toggleSubmitButton);
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const nikInput = document.getElementById("username");
        const passwordInput = document.getElementById("password");
        const agreementCheckbox = document.getElementById("agreement1");
        const submitButton = document.getElementById("submitButton2");

        function toggleSubmitButton() {
            // Aktifkan tombol hanya jika semua field diisi dan checkbox dicentang
            if (nikInput.value.trim() !== "" && passwordInput.value.trim() !== "" && agreementCheckbox.checked) {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
            }
        }

        // Tambahkan event listener untuk memantau perubahan
        nikInput.addEventListener("input", toggleSubmitButton);
        passwordInput.addEventListener("input", toggleSubmitButton);
        agreementCheckbox.addEventListener("change", toggleSubmitButton);
    });
</script>


</body>

</html>