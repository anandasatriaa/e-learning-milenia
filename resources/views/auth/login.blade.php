<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Login | Milenia Learning Center</title>
    <!-- Favicons -->
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon" />
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    @include('pages.layouts.css')
</head>

<body class="login">
    <!-- Navbar Header -->
    <nav class="navbar navbar-expand-lg border-bottom p-1" style="background-color: #EBFAFF">
        <a href="{{ route('landing.page') }}" class="logo ms-5">
            <img src="{{ asset('img/milenia-logo.png') }}" alt="navbar brand" class="navbar-brand" height="70">
        </a>
    </nav>
    <!-- End Navbar -->
    <div class="wrapper wrapper-login">
        <div class="card p-5 animated fadeIn">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <h1 class="text-center fw-bold" style="color: #1B7F97">Selamat Datang di Online Learning</h1>
            <h3 class="text-center text-muted fs-6 mb-5">Silahkan Login menggunakan Username dan Password Kantor yang Anda miliki</h3>
            <form class="login-form needs-validation" action="{{ route('login.process') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input id="uname" name="uname" type="text" class="form-control border border-black rounded"
                        placeholder="Username" value="{{ old('uname') }}" @error('uname') is-invalid @enderror
                        required>
                    {{-- <label for="uname">Username</label> --}}
                </div>
                <div class="form-group d-flex align-items-center position-relative">
                    <input id="pwd" name="pwd" type="password"
                        class="form-control border border-black rounded" placeholder="Password" required
                        @error('pwd') is-invalid @enderror>
                    {{-- <label for="pwd">Password</label> --}}
                    <div class="show-password position-absolute" style="right: 17px; cursor: pointer;">
                        <i class="icon-eye"></i>
                    </div>
                </div>
                <div class="form-action my-3 mx-2">
                    <button type="submit" class="btn btn-secondary w-100 btn-login">Masuk</button>
                </div>
            </form>
        </div>
    </div>

    @include('pages.layouts.js')
</body>

</html>
