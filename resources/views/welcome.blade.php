<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ecovault</title>
    {{-- Niceadmin Assets --}}
    <!-- Custom CSS -->
    <link href="{{ asset('assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <style>
        body {
            position: relative;
            margin: 0;
            min-height: 100vh;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: 
                /* linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.4)),  */
                linear-gradient(135deg, rgba(238, 242, 247, 0.8), rgba(208, 225, 249, 0.8)),
                url('{{ asset('bg-1.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            z-index: -1;
        }

        .logo-container img {
            max-width: 140px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .logo-container img:hover {
            transform: scale(1.05);
        }

        .logo-text {
            color: #2c3e50;
            font-weight: 600;
        }

        .main-title {
            color: #2c3e50;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            display: inline-block;
        }

        .main-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60%;
            height: 3px;
            background: #7460ee;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">

            <a class="navbar-brand" href="#">
                <img src="{{ asset('favicon_io/favicon.ico') }}" width="30" height="30" alt="">
                PENRO Marinduque
            </a>
            {{-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <div class="collapse navbar-collapse " id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto "></ul>
                @if (Route::has('login'))
                    <ul class="navbar-nav my-2 my-lg-0">
                        @auth
                            <li class="nav-item ">
                                <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item ">
                                <a href="{{ route('login') }}" class="nav-link">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                            @endif
                        @endauth
                    </ul>
                @endif
            </div> --}}
        </div>
    </nav>

    <div class="logo-container text-center my-5">
        <div class="row justify-content-center">
            <img src="{{ asset('LOGO.png')}}" alt="DENR Logo" class="img-fluid mb-3">
            <img src="{{ asset('Bagong_Pilipinas_logo.png')}}" alt="Bagong Pilipinas Logo" class="img-fluid mb-3">
        </div>
        <h4 class="logo-text mb-2">Department of Environment and Natural Resources</h4>
        <h5 class="logo-text mb-4">PENRO MARINDUQUE</h5>
    </div>

    <div class="text-center mb-5">
        <h2 class="main-title">QR-Based Electronic Document Authentication System</h2>
    </div>

    @if (Route::has('login'))
        <div class="d-flex justify-content-center g-1">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-outline-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary mr-2">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                @endif
            @endauth
        </div>
    @endif
    
</body>
</html>