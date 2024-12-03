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
    <div class="wrapper wrapper-login">
        <div class="container container-login animated fadeIn">
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
            <h3 class="text-center">Masuk</h3>
            <form class="login-form needs-validation" action="{{ route('login.process') }}" method="POST">
                @csrf
                <div class="form-group form-floating">
                    <input id="uname" name="uname" type="text" class="form-control input-border-bottom"
                        value="{{ old('uname') }}" @error('uname') is-invalid @enderror required>
                    <label for="uname">Username</label>
                </div>
                <div class="form-group form-floating">
                    <input id="pwd" name="pwd" type="password" class="form-control input-border-bottom"
                        required @error('pwd') is-invalid @enderror">
                    <label for="pwd">Password</label>
                    <div class="show-password">
                        <i class="icon-eye"></i>
                    </div>
                </div>
                <div class="row form-sub m-0">
                    <div class="form-check">
                        <small class="text-small fst-italic" for="rememberme">*Login menggunakan akun yang sama dengan
                            sistem
                            HRD</small>
                    </div>
                </div>
                <div class="form-action mb-3">
                    <button type="submit" class="btn btn-primary w-100 btn-login">Masuk</button>
                </div>
            </form>
        </div>
    </div>

    @include('pages.layouts.js')
</body>

</html>
