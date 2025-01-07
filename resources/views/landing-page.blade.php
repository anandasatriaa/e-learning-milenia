<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Landing Page | Milenia Learning Center</title>
    <!-- Favicons -->
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon" />
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    @include('pages.layouts.css')

    <style>
        .hover-effect:hover {
            background-color: #dcf5fd;
            /* Warna abu-abu terang */
            border-radius: 5px;
            /* Sudut rounded */
            transition: background-color 0.2s ease;
            /* Animasi transisi */
        }

        /* Untuk layar dengan lebar maksimum 1024px */
        @media (max-width: 1024px) {
            .responsive-heading {
                font-size: 80px;
                /* Sesuaikan ukuran font di layar tablet */
            }
        }

        /* Untuk layar dengan lebar maksimum 768px */
        @media (max-width: 768px) {
            .responsive-heading {
                font-size: 60px;
                /* Sesuaikan ukuran font di layar tablet kecil atau lebih kecil */
            }
        }

        /* Untuk layar dengan lebar maksimum 425px (ponsel) */
        @media (max-width: 425px) {
            .responsive-heading {
                font-size: 50px;
                /* Sesuaikan ukuran font di layar ponsel */
                text-align: center;
            }

            .selamat {
                font-size: 15px;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar Header -->
    <nav class="navbar navbar-expand-lg border-bottom p-1" style="background-color: #EBFAFF">
        <a href="{{ route('landing.page') }}" class="logo ms-5">
            <img src="{{ asset('img/milenia-logo.png') }}" alt="navbar brand" class="navbar-brand" height="70">
        </a>

        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center me-5">
            <li class="">
                <a class="nav-link d-flex align-items-center p-2 hover-effect" href="{{ route('login') }}">

                    <i class="fas fa-user-circle fs-3"></i>
                    <span class="fw-bold ms-1 fs-5">Log In</span>
                </a>
            </li>
        </ul>
    </nav>
    <!-- End Navbar -->

    <div class="m-2 m-md-5 py-md-5">
        <div class="row m-md-5">
            <div class="col-12 col-md-6 my-auto">
                <h1 class="fw-bold" style="font-size: 100px; line-height: 1.2;">
                    <span style="color: #FF914D; display: block;" class="responsive-heading">ONLINE</span>
                    <span style="color: #462664; display: block;" class="responsive-heading">LEARNING</span>
                </h1>
                <h3 class="text-muted selamat">Selamat Datang di Milenia Learning Center</h3>
            </div>
            <div class="col-12 col-md-6 text-end">
                <img src="{{ asset('img/landing-art.png') }}" alt="" class="img-fluid" width="500px">
            </div>
        </div>
    </div>

    <div class="p-5" style="background-color: #EBFAFF">
        <h1 class="fw-bold text-center">Tutorial Penggunaan</h1>
        <!-- Video Local -->
        <div class="d-flex justify-content-center mt-4">
            <video class="img-fluid" controls width="700px">
                <source src="{{ asset('img/bumper_watermark.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>

    @include('pages.layouts.js')
</body>



</html>
