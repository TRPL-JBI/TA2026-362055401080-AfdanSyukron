<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="https://poliwangi.ac.id/wp-content/uploads/2021/02/logo-poliwangi.png">
  <link rel="icon" type="image/png" href="https://poliwangi.ac.id/wp-content/uploads/2021/02/logo-poliwangi.png">
  <title>
    {{ config('app.name') }}
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />
</head>

<style>

    #login
    {
        background-image: url("{{asset('assets/img/poliwangi2.jpg')}}");
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
        position: relative;
    }
    #login::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(0, 0, 0, 0.4); /* Dark overlay for better contrast */
        z-index: 0;
    }
    #login > .container-fluid, #login > .container {
        position: relative;
        z-index: 1;
    }
    .divider:after,
    .divider:before {
        content: "";
        flex: 1;
        height: 1px;
        background: #eee;
    }
    .h-custom {
        height: calc(100% - 73px);
    }
    @media (max-width: 450px) {
        .h-custom {
            height: 100%;
        }
    }
    .password-wrapper {
        position: relative;
    }
    .password-wrapper .toggle-password {
        position: absolute;
        top: 11px; /* Moved slightly more up to be perfectly centered */
        right: 15px;
        cursor: pointer;
        z-index: 100;
        color: #adb5bd;
    }
    .password-wrapper .toggle-password:hover {
        color: #6c757d;
    }
</style>

<section class="vh-100" id="login">
  <div class="container-fluid h-100 px-4 px-md-5">
    <div class="row d-flex justify-content-end align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-5 col-xl-4 me-lg-5 me-xl-5">
        <div class="card p-4 p-md-5" style="border-radius: 20px; background-color: #ffffff; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
            @if(session('error'))
            <div class="alert alert-danger text-white">
                <b>Uppsss!!!</b> {{session('error')}}
            </div>
            @endif
        <form action="{{ route('actionlogin') }}" method="post">
            @csrf
          <!-- <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
            <p class="lead fw-normal mb-0 me-3">Sign in with</p>
            <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-floating mx-1">
              <i class="fab fa-facebook-f"></i>
            </button>

            <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-floating mx-1">
              <i class="fab fa-twitter"></i>
            </button>

            <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-floating mx-1">
              <i class="fab fa-linkedin-in"></i>
            </button>
          </div>

          <div class="divider d-flex align-items-center my-4">
            <p class="text-center fw-bold mx-3 mb-0">Or</p>
          </div> -->

          <div class="card-header pb-0 text-center">
            <img src="https://poliwangi.ac.id/wp-content/uploads/2021/02/logo-poliwangi.png" alt="Logo Poliwangi" height="100px" class="mb-3">
            <h2 class="small-spacing">SIPMAS POLIWANGI</h2>
          </div>


          <div class="divider d-flex align-items-center my-4">
            <p class="text-center fw-bold fs-4 mx-3 mb-0">Login</p>
          </div>

          <!-- Email input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="nim/nip" name="nip" id="form3Example3" class="form-control form-control-lg"
              placeholder="Enter username" />
            <label class="form-label" for="form3Example3">NIM/NIP</label>
          </div>

          <!-- Password input -->
          <div data-mdb-input-init class="form-outline mb-3 password-wrapper">
            <input type="password" name="password" id="form3Example4" class="form-control form-control-lg"
              placeholder="Enter password" />
            <span class="toggle-password" id="togglePassword">
              <i class="fas fa-eye" id="eyeIcon"></i>
            </span>
            <label class="form-label" for="form3Example4">Password</label>
          </div>

          <div class="text-center text-lg-start mt-4 pt-2">
            <button  type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
            <!-- <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="#!"
                class="link-danger">Register</a></p> -->
          </div>

        </form>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#form3Example4');

    togglePassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // toggle the eye icon
        const icon = this.querySelector('i');
        if (type === 'password') {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });
</script>

</body>
</html>