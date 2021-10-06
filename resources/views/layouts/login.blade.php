<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title></title>

    <!-- Scripts -->
    <script src="{{ $asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ $asset('css/app.css') }}" rel="stylesheet">

    <!-- Argon CSS -->
    <link type="text/css" href="{{ $asset('css/argon-pro.css') }}" rel="stylesheet">

    <!-- Core -->
    <script src="{{ $asset('vendor/js-cookie/js.cookie.js') }}"></script>
    <script src="{{ $asset('vendor/jquery/dist/jquery.min.js') }}"></script> 
    <script src="{{ $asset('vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Argon JS -->
	  <script src="{{ $asset('js/argon.min.js') }}"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	
	  <!-- Icons -->
    <link href="{{ $asset('vendor/nucleo/css/nucleo.css?v=1.0.0') }}" rel="stylesheet">
	<link href="{{ $asset('vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    {{-- sweet alert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css" id="theme-styles">
</head>
<body class="g-sidenav-show g-sidenav-pinned">
    <div class="main-content bg-white">
        <div class="header py-6 py-lg-7 pt-lg-8">
            <div class="container">
                <div class="header-body text-center mb-7">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                            <img src="{{url('/images/carta-logo.png')}}" alt="..." style="width: 200px">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt--9 pb-5">
            @yield('content')
        </div>
    </div>
    @include('sweetalert::alert')
</body>
</html>
