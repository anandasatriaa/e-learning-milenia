<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title') | Milenia Learning Center</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon" />
    @include('pages.layouts.css')
</head>

<body>
    <div class="wrapper">

        @include('pages.layouts.sidebar')

        <div class="main-panel">
            @include('pages.layouts.navbar')
            <div class="container">
                @yield('content')
            </div>

            @include('pages.layouts.footer')
        </div>
    </div>
    @include('pages.layouts.js')
</body>

</html>
