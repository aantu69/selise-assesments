<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicons -->
    <link href="{{ asset('images/favicon.png') }}" rel="icon" type="image/ico" sizes="32x32">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Assessment') }}</title>



    <!-- Fonts -->
    {{-- <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script> --}}
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <style>
        body {
            background: #f7f9ff;
            font-family: 'Josefin Sans', sans-serif;
            font-size: 16px;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.4;
            color: #555;
        }
    </style>
</head>

<body>

    @yield('content')

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
</body>

</html>
