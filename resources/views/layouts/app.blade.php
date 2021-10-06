<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta http-equiv="Content-Type" content="text/html">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
        <meta name="author" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<title>{{ $title }}</title>

		<!-- Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

		<!-- Icons -->
		<link href="{{ $asset('vendor/nucleo/css/nucleo.css?v=1.0.0') }}" rel="stylesheet">
		<link href="{{ $asset('vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

		<link href="{{ $asset('vendor/select2/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
		
		<!-- Argon CSS -->
		<link type="text/css" href="{{ $asset('css/argon.min.css?v=1.2.0') }}" rel="stylesheet">
		<script src="{{ $asset('vendor/jquery/dist/jquery.min.js') }}"></script>
		<script src="{{ $asset('vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="{{ $asset('css/custom-datatable.css') }}">
        <link rel="stylesheet" href="{{ $asset('css/custom.css') }}">
        
		<link rel="stylesheet" href="{{ $asset('vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
		<link rel="stylesheet" href="{{ $asset('vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}">
		<link rel="stylesheet" href="{{ $asset('vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">

		<!-- Datepicker -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

		{{-- sweet alert2 --}}
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>
  		<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css" id="theme-styles">
		
		{{-- swiper --}}
		<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
		<link rel="stylesheet" href="{{ $asset('css/swiper-custom.css')}}" />
		<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
	</head>
    <body class="bg-white">
        <!-- Sidenav -->
        <nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
            <div class="scrollbar-inner">
                <!-- Brand -->
                <div class="sidenav-header  d-flex  align-items-center">
                    <a class="navbar-brand" href="#">
                        <img src="{{$asset('/images/carta-logo.png')}}" alt="..."  class="navbar-brand-img" style="max-height: 3rem; !important;">
                    </a>
                    <div class=" ml-auto ">
                        <!-- Sidenav toggler -->
                        <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="navbar-inner">
                    <!-- Collapse -->
                    <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                        <!-- Nav items -->
                        <ul class="navbar-nav">
                            {!! request()->session()->get('main_menu') !!}
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Main content -->
        <div class="main-content" id="panel">
            <!-- Topnav -->
            <nav class="navbar navbar-top navbar-expand navbar-dark  border-bottom" style="border-color: #ef4d41 !important; border-width: 3px !important">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <h6 class="h2 text-gray-dark d-none d-xl-block d-lg-block d-md-block mb-0 text-uppercase">{{ $active_menu }}</h6>
                        <!-- Navbar links -->
                        <ul class="navbar-nav align-items-center  ml-md-auto ">
                            <li class="nav-item d-xl-none">
                                <!-- Sidenav toggler -->
                                <div class="pr-3 sidenav-toggler" data-action="sidenav-pin" data-target="#sidenav-main">
                                    <div class="sidenav-toggler-inner">
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
                            <li class="nav-item dropdown">
                                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="media align-items-center">
                                        <span class="avatar avatar-sm rounded-circle">
                                            {{ substr(strtoupper(session('user_name')), 0, 1)}}
                                        </span>
                                        <div class="media-body  ml-2  d-none d-lg-block">
                                            <span class="mb-0 text-sm text-dark font-weight-bold">{{ session('user_name') }}</span>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu  dropdown-menu-right ">
                                    <div class="dropdown-header noti-title">
                                        <h6 class="text-overflow m-0">Welcome!</h6>
                                    </div>
                                    <a href="{{url('auth/logout')}}" class="dropdown-item">
                                        <i class="ni ni-user-run"></i>
                                        <span>Logout</span>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            @yield('content')
            <div class="container-fluid">
                <!-- Footer -->
                <footer class="footer pt-0 bg-white">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6">
                            <div class="copyright text-center text-lg-left text-muted">
                                Copyright © {{ date('Y') }} <span class="font-weight-bold ml-1 text-uppercase">Carta</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <!-- Core -->
		<script src="{{ $asset('vendor/js-cookie/js.cookie.js') }}"></script>
		<script src="{{ $asset('vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
		<script src="{{ $asset('vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js')}}"></script>
		<!-- Optional JS -->
		<script src="{{ $asset('vendor/select2/select2/dist/js/select2.min.js') }}"></script>
		<script src="{{ $asset('vendor/chart.js/dist/Chart.min.js') }} "></script>
		<script src="{{ $asset('vendor/chart.js/dist/Chart.extension.js') }}"></script>
		<script src="{{ $asset('vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
		<script src="{{ $asset('vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
		<script src="{{ $asset('vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
		<script src="{{ $asset('vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
		<script src="{{ $asset('vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
		<script src="{{ $asset('vendor/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
		<script src="{{ $asset('vendor/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
		<script src="{{ $asset('vendor/datatables.net-select/js/dataTables.select.min.js') }}"></script>
        <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/fixedcolumns/3.3.3/js/dataTables.fixedColumns.min.js"></script>
		<script src="{{ $asset('js/argon.min.js?v=1.2.0') }}"></script>
		<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
		<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
		@include('sweetalert::alert')
		@include('global')
    </body>
</html>