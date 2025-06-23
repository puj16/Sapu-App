<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sapu.App - Login</title>
    <!-- Favicons -->
    <link href="assets/img/logo.jpg" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

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
  
  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

    <link href="assets/css/auth.css" rel="stylesheet">
</head>
<body>

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

    <div class="wrapper">
        <div class="title-text">
          <div class="title login">Login Petani</div>
          <div class="title signup">Login Petugas</div>
        </div>
        <div class="form-container">
          <div class="slide-controls">
            <input type="radio" name="slide" id="login" checked>
            <input type="radio" name="slide" id="signup">
            <label for="login" class="slide login">Petani</label>
            <label for="signup" class="slide signup">Petugas</label>
            <div class="slider-tab"></div>
          </div> 
          <div class="form-inner">
          {{-- login petani --}}
            <form action="/loginPetani" method="POST" class="login">
               @csrf 
              <div class="field">
                <input type="text" placeholder="NIK" name="nik" required>
              </div>
              <div class="field">
                <input type="password" placeholder="Password" name="password" required>
              </div>
              <div class="pass-link"><a href="#">Forgot password?</a></div>
              <div class="field btn">
                <div class="btn-layer"></div>
                <input type="submit" value="Login">
              </div>
              <div class="signup-link">Not a member? <a href="">Signup now</a></div>
            </form>

          {{-- login petugas --}}
            <form action="/login_post" method="POST" class="signup">
              @csrf 
                <div class="field">
                  <input type="text" placeholder="Username" name="username" required>
                </div>
                <div class="field">
                  <input type="password" placeholder="Password" name="password" required>
                </div>
                <div class="pass-link"><a href="#">Forgot password?</a></div>
                <div class="field btn">
                  <div class="btn-layer"></div>
                  <input type="submit" value="Login">
                </div>
                <div class="signup-link">Not a member? <a href="">Signup now</a></div>
            </form>
          </div>
        </div>
      </div>
      <script src="assets/js/auth.js"></script>
      <script src="assets/js/jquery.min.js"></script>
      <script src="assets/js/bootstrap.bundle.min.js"></script>


</body>
</html>