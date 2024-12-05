<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title') | Milenia Learning Center</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon" />
    @include('admin.layouts.css')
</head>

<body>
    <div class="wrapper">

        @include('admin.layouts.sidebar')

        <div class="main-panel">
            @include('admin.layouts.navbar')
            <div class="container">
                @yield('content')
            </div>

            @include('admin.layouts.footer')
        </div>
    </div>
    @include('admin.layouts.js')
</body>

</html>
